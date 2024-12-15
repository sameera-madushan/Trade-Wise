<?php

namespace Package\XConfig\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class ConfigCategory extends Model
{
    protected $table = 'configuration_category';
    protected $primaryKey = 'id';

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['name','description','status'];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $userid = 0;
            if (Auth::check())
                $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;
        });

        static::updating(function ($model) {
            $userid = 0;
            if (Auth::check())
                $userid = auth()->user()->id;
            $model->updated_by = $userid;
        });
    }
}
