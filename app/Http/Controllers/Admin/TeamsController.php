<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teams;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    public function teams(Request $request)
    {
        $teams = Teams::where('status', '=', 1)
            ->orderBy('team_name', 'asc')
            ->get();
        return view('teams', compact('teams'));
    }
}
