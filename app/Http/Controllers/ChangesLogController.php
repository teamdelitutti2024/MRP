<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeLog;

class ChangesLogController extends Controller
{
    public function index()
    {
        $changes_log = ChangeLog::orderBy('created_at', 'desc')->get();
        return view('changes_log.index', compact('changes_log'));
    }
}
