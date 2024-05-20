<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashLedger extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'team_id',
        'round_id',
        'cash_in_hand'
    ];
    protected $table = 'cash_ledger';
}
