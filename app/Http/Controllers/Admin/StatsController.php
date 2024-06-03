<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashLedger;
use App\Models\Companies;
use App\Models\Ledger;
use App\Models\Orders;
use App\Models\Teams;
use Illuminate\Http\Request;
use DB;

class StatsController extends Controller
{
    public function config(Request $request)
    {
        $rounds = DB::table("active_round")->select('id', 'round_name')->get()->toArray();
        return view('config', compact('rounds'));
    }

    public function calculateStats(Request $request)
    {
        $teams = Teams::select('id', 'team_name', 'virtual_amount')->where('status', 1)->get();
        $activeRound = DB::table("active_round")->where('status', 1)->pluck('id')->first();
        foreach ($teams as $team) {
            $orders = Orders::where('team_id', $team->id)->where('round_id', $activeRound)->get();
            $cash = CashLedger::where('team_id', $team->id)->first();

            // If there is no cash record for the team, create a new one
            if (!$cash) {
                $cash = new CashLedger();
                $cash->team_id = $team->id;
                $cash->cash_in_hand = $team->virtual_amount;
            }

            foreach ($orders as $order) {
                $company = Companies::where('id', $order->company_id)->first();
                $holding = Ledger::where('team_id', $team->id)->where('company_id', $company->id)->first();

                // If there is no holding record for the team and company, create a new one
                if (!$holding) {
                    $holding = new Ledger();
                    $holding->team_id = $team->id;
                    $holding->company_id = $company->id;
                    $holding->quantity = 0;
                }

                if ($order->buy_quantity > 0) {
                    $cash->cash_in_hand -= $order->buy_value;
                    $holding->quantity += $order->buy_quantity;
                }

                if ($order->sell_quantity > 0) {
                    $cash->cash_in_hand += $order->sell_value;
                    $holding->quantity -= $order->sell_quantity;
                }

                $cash->save();
                $holding->save();
            }
        }
        return response()->json(['message' => 'Stats updated successfully.']);
    }
}
