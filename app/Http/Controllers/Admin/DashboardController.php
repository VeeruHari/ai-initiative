<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;

class DashboardController extends Controller
{
    public function index()
    {
        $companies = Tenant::orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('companies'));
    }
}
