<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrokerHouses;
use App\Models\CashLedger;
use App\Models\Companies;
use App\Models\Ledger;
use App\Models\Orders;
use App\Models\TeamBrokerHousesTagging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;
use Config;

class OrdersController extends Controller
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

    public function brokers(Request $request)
    {
        $broker_houses = BrokerHouses::where('status', '=', 1)
            ->orderBy('broker_name', 'asc')
            ->get();
        $active_round = DB::table("active_round")->where('status', 1)->pluck('round_name')->first();
        $limit_flag = DB::table("active_round")->where('status', 1)->pluck('limit_flag')->first();
        $amount_alloted = Config::get('constants.ROUND_LIMITS')[$active_round];
        $active_round_id = DB::table("active_round")->where('status', 1)->pluck('id')->first();
        $amount_used = Orders::select('team_id', \DB::raw('SUM(buy_value) as sum_of_total'))
            ->where('round_id', $active_round_id)
            ->groupBy('team_id')
            ->get()->toArray();
        $cash_available = CashLedger::select('team_id', 'cash_in_hand')->get()->toArray();
        if ($limit_flag == 1) {
            foreach ($amount_used as &$amount) {
                $amount['sum_of_total'] = $amount_alloted - $amount['sum_of_total'];
            }
        } else {
            foreach ($cash_available as &$cash) {
                foreach ($amount_used as &$amount) {
                    if ($amount['team_id'] == $cash['team_id']) {
                        $cash['cash_in_hand'] = $cash['cash_in_hand'] - $amount['sum_of_total'];
                    }
                }
            }
        }
        $companies = Companies::where('status', 1)->select('id', 'company_name', $active_round . ' as price')->get();
        $active_round_display_name = $this->formatString($active_round);
        return view('orders', compact('broker_houses', 'companies', 'active_round_display_name', 'active_round_id', 'amount_used', 'amount_alloted', 'limit_flag', 'cash_available'));
    }

    public function teamsTagged(Request $request)
    {
        $selected_broker = (int)$request->route('id');
        $teams_tagged = TeamBrokerHousesTagging::leftjoin('teams', 'teams.id', 'teams_broker_houses_tagging.team_id')
            ->where('teams_broker_houses_tagging.broker_house_id', $selected_broker)
            ->where('teams.status', 1)
            ->select('teams.id', 'teams.team_name')
            ->get();
        $response['status'] = 200;
        $response['data'] = $teams_tagged;
        return json_encode($response);
    }

    public function checkActiveRound(Request $request)
    {
        $active_round = DB::table("active_round")->where('status', 1)->pluck('id')->first();
        return response()->json($active_round);
    }

    public function checkSellQuantity(Request $request)
    {
        $check = Ledger::where('team_id', (int)$request->team_id)
            ->where('company_id', (int)$request->company_id)->pluck('quantity')->first();
        if ($check == null) {
            $response['status'] = 500;
            $response['message'] = 'You don\'t own such quantity';
            return json_encode($response);
        } else {
            if ($check >= (int)$request->sell_quantity) {
                $response['status'] = 200;
                $response['message'] = '';
                return json_encode($response);
            } else {
                $response['status'] = 500;
                $response['message'] = 'You don\'t own such quantity';
                return json_encode($response);
            }
        }
    }

    public function saveOrders(Request $request)
    {
        // Validate the request data
        $request->validate([
            'orders.*.team_id' => 'required|exists:teams,id',
            'orders.*.round_id' => 'required|exists:active_round,id',
            'orders.*.company_id' => 'required|exists:companies,id',
            'orders.*.buy_quantity' => 'required|integer|min:0',
            'orders.*.sell_quantity' => 'required|integer|min:0',
            'orders.*.buy_value' => 'required|numeric|min:0',
            'orders.*.sell_value' => 'required|numeric|min:0',
            'orders.*.brokerage' => 'required|numeric|min:0',
        ]);

        // Save each order in the database
        foreach ($request->orders as $orderData) {
            Orders::create($orderData);
        }

        // Return a success response
        return response()->json(['message' => 'Orders saved successfully.']);
    }
}
