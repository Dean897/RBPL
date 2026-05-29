<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $agendas = Agenda::orderBy('date', 'desc')->paginate(15);
        return view('agendas.index', compact('agendas'));
    }

    public function create()
    {
        return view('agendas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'date' => 'required|date',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['created_by'] = auth()->id();

        Agenda::create($data);

        return redirect()->route('agendas.index')->with('success', 'Agenda berhasil dibuat.');
    }
}
