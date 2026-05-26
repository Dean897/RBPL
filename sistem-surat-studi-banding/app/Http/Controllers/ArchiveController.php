<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArchiveRequest;
use App\Http\Requests\UpdateArchiveRequest;
use App\Models\Archive;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
            $query->where('category', $category);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('archived_at', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('archived_at', '<=', $to);
        }

        $sort = $request->input('sort_by', 'archived_at');
        $order = $request->input('order', 'desc');

        $archives = $query->orderBy($sort, $order)->paginate(15)->withQueryString();

        return view('archives.index', compact('archives'));
    }

    public function create()
    {
        $this->authorize('create', Archive::class);
        return view('archives.create');
    }

    public function store(StoreArchiveRequest $request)
    {
        $this->authorize('create', Archive::class);

        $file = $request->file('file');

        $path = $file->store('archives', 'public');

        DB::beginTransaction();
        try {
            $archive = Archive::create([
                'archive_number' => '',
                'title' => $request->title,
                'category' => $request->category,
                'description' => $request->description,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
                'archived_at' => $request->archived_at ?: now(),
                'uploaded_by' => auth()->id(),
                'is_private' => $request->boolean('is_private'),
                'allowed_roles' => $request->input('allowed_roles', []),
            ]);

            $archive->archive_number = 'AR' . now()->format('Ymd') . str_pad($archive->id, 6, '0', STR_PAD_LEFT);
            $archive->save();

            $this->logActivity($archive, 'created', ['title' => $archive->title]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // cleanup file
            Storage::disk('public')->delete($path);
            return back()->withErrors(['file' => 'Upload gagal.'])->withInput();
        }

        return redirect()->route('archives.index')->with('success', 'Arsip berhasil ditambahkan.');
    }

    public function show(Archive $archive)
    {
        $this->authorize('view', $archive);
        return view('archives.show', compact('archive'));
    }

    public function preview(Archive $archive)
    {
        $this->authorize('view', $archive);
        return Storage::disk('public')->response($archive->file_path);
    }

    public function download(Archive $archive)
    {
        $this->authorize('download', $archive);

        $this->logActivity($archive, 'downloaded', ['file' => $archive->file_name]);

        return Storage::disk('public')->download($archive->file_path, $archive->file_name);
    }

    public function edit(Archive $archive)
    {
        $this->authorize('update', $archive);
        return view('archives.edit', compact('archive'));
    }

    public function update(UpdateArchiveRequest $request, Archive $archive)
    {
        $this->authorize('update', $archive);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('archives', 'public');
            // delete old
            Storage::disk('public')->delete($archive->file_path);
            $archive->file_path = $path;
            $archive->file_name = $file->getClientOriginalName();
            $archive->file_size = $file->getSize();
            $archive->mime_type = $file->getClientMimeType();
        }

        $archive->title = $request->title;
        $archive->category = $request->category;
        $archive->description = $request->description;
        $archive->archived_at = $request->archived_at ?: $archive->archived_at;
        $archive->is_private = $request->boolean('is_private');
        $archive->allowed_roles = $request->input('allowed_roles', []);
        $archive->save();

        $this->logActivity($archive, 'updated', ['title' => $archive->title]);

        return redirect()->route('archives.index')->with('success', 'Arsip diperbarui.');
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
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => Archive::class,
            'model_id' => $archive->id,
            'metadata' => json_encode($metadata),
        ]);
    }
}
