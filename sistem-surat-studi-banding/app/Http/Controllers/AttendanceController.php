<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $attendances = Attendance::with('agenda')->orderBy('created_at', 'desc')->paginate(25);
        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $agendas = Agenda::orderBy('date', 'desc')->get();
        return view('attendances.create', compact('agendas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'agenda_id' => 'required|exists:agendas,id',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();

        Attendance::create($data);

        return redirect()->route('attendances.index')->with('success', 'Absensi tersimpan.');
    }
}
