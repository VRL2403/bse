<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrokerHouses;
use Illuminate\Http\Request;

class BrokerHousesController extends Controller
{
    public function broker_houses(Request $request)
    {
        $broker_houses = BrokerHouses::where('status', '=', 1)
            ->orderBy('broker_name', 'asc')
            ->get();
        return view('broker_houses', compact('broker_houses'));
    }
}
