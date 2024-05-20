<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoundStats extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'team_id',
        'round_id',
        'total_buy_value',
        'total_sell_value',
        'net_value'
    ];
    protected $table = 'round_stats';
}
