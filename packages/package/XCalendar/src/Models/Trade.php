<?php

namespace Package\XCalendar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trade extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'sqlite_user';

    protected $fillable = [
        'timestamp', 'curruncy_pair', 'buy_value', 'buy_price',
        'sell_value', 'sell_price', 'bought_amount', 'position', 'pnl', 'timestamp'
    ];
}
