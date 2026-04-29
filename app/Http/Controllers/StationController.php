<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $stations = \App\Models\Station::all();
        return view('admin.dashboard', compact('stations'));
    }
}
