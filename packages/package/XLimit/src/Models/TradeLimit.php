<?php

namespace Package\XLimit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TradeLimit extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'sqlite_user';

    protected $fillable = [
        'date', 'limit', 'timestamp'
    ];
}
