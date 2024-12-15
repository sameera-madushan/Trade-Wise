<?php

namespace Package\XConfig\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Package\XConfig\Models\ConfigCategory;

class Config extends Model
{
    protected $table = 'configurations';
    protected $primaryKey = 'id';

    protected $casts = [
        'options_array' => 'array',
    ];

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function boot()
    {
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

    public function category()
    {
        return $this->hasOne(ConfigCategory::class, 'id', 'category_id');
    }

    /**
     * Save request data
     *
     * @param  array  $data
     * @return $this
     */
    public function storeData(Array $data)
    {
        $this->name = $data['name'];
        $this->display_name = $data['display_name'];
        $this->config_type = $data['config_type'];

        if ($data['config_type'] == 'DD'){
            $this->value = $data['value'] ?? '';
            $select_type_options_array = array_combine($data['key_array'], $data['value_array']);
            $this->options_array = $select_type_options_array;
        } elseif ($data['config_type'] == 'TX') {
            $this->value = $data['value'] ?? '';
        }

        $this->category_id = $data['category_id'];
        $this->status = $data['status'] ? 1 : 0;
        $this->save();

        return $this;
    }
}
