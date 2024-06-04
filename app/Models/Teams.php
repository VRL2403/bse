<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teams extends Model
{
    use SoftDeletes;
    protected $table = 'teams';

    public function ledger()
    {
        return $this->hasMany(Ledger::class);
    }

    public function cashLedger()
    {
        return $this->hasOne(CashLedger::class);
    }
}
