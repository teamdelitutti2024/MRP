<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchesController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('name', 'asc')->get();
        return view('branches.index', compact('branches'));
    }

    public function detail(Branch $branch)
    {
        return view('branches.detail', compact('branch'));
    }
}
