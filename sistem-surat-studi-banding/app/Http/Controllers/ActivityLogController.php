<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $logs = ActivityLog::query()->orderBy('created_at', 'desc')->paginate(25);
        return view('activity_logs.index', compact('logs'));
    }
}
