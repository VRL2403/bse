<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamBrokerHousesTagging extends Model
{
    use SoftDeletes;
    protected $table = 'teams_broker_houses_tagging';
}
