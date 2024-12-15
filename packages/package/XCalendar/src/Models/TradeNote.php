<?php

namespace Package\XCalendar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TradeNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'trade_id', 'quill_content'
    ];
}
