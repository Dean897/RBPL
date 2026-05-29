<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Archive::query();

        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('archive_number', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($category = $request->input('category')) {
            // Backward-compatibility: old incoming archives may still use category "surat".
            if ($category === 'surat-masuk') {
                $query->whereIn('category', ['surat-masuk', 'surat']);
            } else {
                $query->where('category', $category);
            }
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate(DB::raw('COALESCE(archived_at, created_at)'), '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate(DB::raw('COALESCE(archived_at, created_at)'), '<=', $to);
        }

        $sort = $request->input('sort_by', 'archived_at');
        $order = $request->input('order', 'desc');

        $archives = $query->orderBy($sort, $order)->paginate(15)->withQueryString();

        return view('archives.index', compact('archives'));
    }

    public function show(Archive $archive)
    {
        $this->authorize('view', $archive);
        return view('archives.show', compact('archive'));
    }

    public function preview(Archive $archive)
    {
        $this->authorize('view', $archive);

        $path = Storage::disk('public')->path($archive->file_path);

        return response()->file($path, [
            'Content-Type' => $archive->mime_type ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function previewRaw(Archive $archive)
    {
        $this->authorize('view', $archive);

        $disk = Storage::disk('public');
        if (!$disk->exists($archive->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $content = $disk->get($archive->file_path);

        return response($content, 200, [
            'Content-Type' => $archive->mime_type ?: 'application/pdf',
            'Content-Length' => strlen($content),
            'Content-Disposition' => 'inline; filename="' . basename($archive->file_path) . '"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    public function download(Archive $archive)
    {
        $this->authorize('download', $archive);

        $this->logActivity($archive, 'downloaded', ['file' => $archive->file_name]);

        return response()->download(
            storage_path('app/public/' . $archive->file_path),
            $archive->file_name
        );
    }

    public function destroy(Archive $archive)
    {
        $this->authorize('delete', $archive);

        $this->logActivity($archive, 'deleted', ['title' => $archive->title]);

        Storage::disk('public')->delete($archive->file_path);
        $archive->delete();

        return redirect()->route('archives.index')->with('success', 'Arsip dihapus.');
    }

    public function print(Archive $archive)
    {
        $this->authorize('view', $archive);
        // simple print view that embeds the PDF
        return view('archives.print', compact('archive'));
    }

    private function logActivity(Archive $archive, string $action, array $metadata): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => Archive::class,
            'model_id' => $archive->id,
            'metadata' => json_encode($metadata),
        ]);
    }
}
