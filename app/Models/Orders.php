<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    use SoftDeletes;
    protected $fillable = ['team_id', 'round_id', 'company_id', 'buy_quantity', 'sell_quantity', 'buy_value', 'sell_value'];
    protected $table = 'orders';
}
