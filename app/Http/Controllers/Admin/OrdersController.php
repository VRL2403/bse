<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrokerHouses;
use App\Models\Companies;
use App\Models\Ledger;
use App\Models\Orders;
use App\Models\TeamBrokerHousesTagging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class OrdersController extends Controller
{
    function formatString($str)
    {
        // Replace underscores with spaces
        $str = str_replace('_', ' ', $str);
        $str = str_replace('price', 'round', $str);
        // Convert to sentence case
        $str = strtolower($str);
        $str = ucfirst($str);

        return $str;
    }

    public function brokers(Request $request)
    {
        $selected_broker = Session::get('selected_broker');
        $broker_houses = BrokerHouses::where('status', '=', 1)
            ->when($selected_broker != null, function ($query) use ($selected_broker) {
                $query->where('id', $selected_broker);
            })
            ->orderBy('broker_name', 'asc')
            ->get();
        $active_round = DB::table("active_round")->where('status', 1)->pluck('round_name')->first();
        $active_round_id = DB::table("active_round")->where('status', 1)->pluck('id')->first();
        $companies = Companies::where('status', 1)->select('id', 'company_name', $active_round . ' as price')->get();
        $active_round_display_name = $this->formatString($active_round);
        return view('orders', compact('broker_houses', 'companies', 'active_round_display_name', 'active_round_id'));
    }

    public function teamsTagged(Request $request)
    {
        $selected_broker = $request->broker_id;
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
        $check = Ledger::where('team_id', $request->team_id)
            ->where('company_id', $request->company_id)->pluck('quantity')->first();
        if ($check >= (int)$request->sell_quantity) {
            $response['status'] = 500;
            $response['message'] = 'You don\'t own such quantity';
            return json_encode($response);
        } else {
            $response['status'] = 200;
            $response['message'] = '';
            return json_encode($response);
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
        ]);

        // Save each order in the database
        foreach ($request->orders as $orderData) {
            Orders::create($orderData);
        }

        // Return a success response
        return response()->json(['message' => 'Orders saved successfully.']);
    }
}
