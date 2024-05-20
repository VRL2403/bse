<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CashLedger;
use App\Models\Orders;
use App\Models\Ledger;
use App\Models\RoundStats;
use App\Models\Teams;
use DB;

class CreateStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $roundId;

    public function __construct($roundId)
    {
        $this->roundId = $roundId;
    }

    public function handle()
    {
        // Get the orders for this round
        $orders = Orders::where('round_id', $this->roundId)->get();

        // Calculate the round-wise stats
        $stats = $orders->groupBy('team_id')->map(function ($teamOrders, $teamId) {
            return [
                'total_buy_value' => $teamOrders->where('buy_quantity', '>', 0)->sum('buy_value'),
                'total_sell_value' => $teamOrders->where('sell_quantity', '>', 0)->sum('sell_value'),
            ];
        });

        // Store the round-wise stats
        foreach ($stats as $teamId => $stat) {
            RoundStats::create([
                'round_id' => $this->roundId,
                'team_id' => $teamId,
                'total_buy_value' => $stat['total_buy_value'],
                'total_sell_value' => $stat['total_sell_value'],
                'net_value' => $stat['total_sell_value'] - $stat['total_buy_value'],
            ]);
        }

        // Update the cash ledger
        foreach ($stats as $teamId => $stat) {
            $team = Teams::find($teamId);
            $cashInHand = $team->virtual_amount - $stat['total_buy_value'] + $stat['total_sell_value'];
            CashLedger::create([
                'team_id' => $teamId,
                'round_id' => $this->roundId,
                'cash_in_hand' => $cashInHand,
            ]);
            $team->virtual_amount = $cashInHand;
            $team->save();
        }

        // Update the ledger
        $ledgerEntries = $orders->groupBy(['team_id', 'company_id'])->map(function ($teamOrders, $key) {
            $teamId = $key['team_id'];
            $companyId = $key['company_id'];
            return [
                'quantity' => $teamOrders->sum('buy_quantity') - $teamOrders->sum('sell_quantity'),
                'value' => $teamOrders->sum('buy_value') - $teamOrders->sum('sell_value'),
            ];
        });

        foreach ($ledgerEntries as $key => $entry) {
            $teamId = $key['team_id'];
            $companyId = $key['company_id'];
            Ledger::updateOrCreate(
                ['team_id' => $teamId, 'company_id' => $companyId],
                ['quantity' => DB::raw("quantity + {$entry['quantity']}"), 'value' => DB::raw("value + {$entry['value']}")]
            );
        }
    }
}
