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
    function formatString($str)
    {
        // Replace underscores with spaces
        $str = str_replace('_', ' ', $str);
        $str = str_replace('price', '', $str);
        if (strpos($str, 'round') === false) {
            // Append the target word to the existing text
            $str .= ' round';
        }
        // Convert to sentence case
        $str = strtolower($str);
        $str = ucfirst($str);

        return $str;
    }

    public function config(Request $request)
    {
        $rounds = DB::table("active_round")->select('id', 'round_name')->get()->toArray();
        $active_round = DB::table("active_round")->where('status', 1)->pluck('round_name')->first();
        $active_round_display_name = $this->formatString($active_round);
        return view('config', compact('rounds', 'active_round_display_name'));
    }

    public function calculateStats(Request $request)
    {
        CashLedger::truncate();
        Ledger::truncate();
        $teams = Teams::select('id', 'team_name', 'virtual_amount')->where('status', 1)->get();
        // $activeRound = DB::table("active_round")->where('status', 1)->pluck('id')->first();
        foreach ($teams as $team) {
            $orders = Orders::where('team_id', $team->id)
                // ->where('round_id', $activeRound)
                ->get();
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
                    $cash->cash_in_hand -= $order->brokerage;
                    $holding->quantity += $order->buy_quantity;
                }

                if ($order->sell_quantity > 0) {
                    $cash->cash_in_hand += $order->sell_value;
                    $cash->cash_in_hand -= $order->brokerage;
                    $holding->quantity -= $order->sell_quantity;
                }

                $cash->save();
                $holding->save();
            }
        }
        return response()->json(['message' => 'Stats updated successfully.']);
    }

    public function changeActiveRound(Request $request)
    {
        $selected_round = (int)$request->route('id');
        $active_round = DB::table('active_round')->update(['status' => 0]);
        $update_round = DB::table('active_round')->where('id', $selected_round)->update(['status' => 1]);
        return response()->json(['message' => 'Active Round Updated successfully.']);
    }

    public function ledger(Request $request)
    {
        $active_round = DB::table("active_round")->where('status', 1)->pluck('round_name')->first();
        $teamsData = DB::select('SELECT t.team_name AS TeamName,
                    ROUND(cl.cash_in_hand,2)  AS CashLedger,
                    ROUND(SUM(l.quantity * c.opening_bell_price),2) AS PortfolioValue,
                    ROUND((cl.cash_in_hand + SUM(l.quantity * c.opening_bell_price)),2) AS Total
                    FROM teams t
                    JOIN ledger l ON t.id = l.team_id
                    JOIN companies c ON l.company_id = c.id
                    JOIN cash_ledger cl ON t.id = cl.team_id
                    GROUP BY t.team_name, cl.cash_in_hand
                    ORDER BY Total DESC;');

        return view('stats', compact('teamsData'));
    }

    public function holdings(Request $request)
    {
        $teams = Teams::select('id', 'team_name')->where('status', 1)->get();
        return view('holdings', compact('teams'));
    }

    public function teamHoldings(Request $request)
    {
        $selected_team = (int)$request->route('id');
        $active_round = DB::table("active_round")->where('status', 1)->pluck('round_name')->first();
        $holdings = DB::select('SELECT c.company_name, ' . $active_round . ' AS current_value, SUM(l.quantity * c.' . $active_round . ') AS total_value, SUM(l.quantity) AS quantity FROM ledger l JOIN companies c ON l.company_id = c.id WHERE l.team_id = ' . $selected_team . ' GROUP BY c.company_name, c.' . $active_round . ';');
        return response()->json(['holdings' => $holdings, 'status' => 200]);
    }

    public function resetGame(Request $request)
    {
        Orders::truncate();
        Ledger::truncate();
        CashLedger::truncate();
        DB::table('active_round')->update(['status' => 0]);
        DB::table('active_round')->where('id', 1)->update(['status' => 1]);
        return response()->json(['message' => 'Game reset successfully.']);
    }
}
