<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrokerHouses extends Model
{
    use SoftDeletes;
    protected $table = 'broker_houses';
}
