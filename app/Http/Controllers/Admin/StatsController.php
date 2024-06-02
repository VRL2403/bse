<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ledger;
use Illuminate\Http\Request;
use DB;

class StatsController extends Controller
{
    public function getStats(Request $request)
    {
        $active_round = DB::table("active_round")->where('status', 1)->pluck('id')->first();
        // $complete_data = Ledger::where('status', 1)
        // ->leftJoin('companies', 'companies.')
    }
}
