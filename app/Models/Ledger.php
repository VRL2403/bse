<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ledger extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'team_id',
        'company_id',
        'quantity',
        'value'
    ];
    protected $table = 'ledger';
}
