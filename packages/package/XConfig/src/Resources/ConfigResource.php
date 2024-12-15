<?php

namespace Package\XConfig\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class ConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'config_type' => $this->config_type,
            'value' => $this->value,
            'options_array' => $this->options_array,
            'category_id' => $this->category_id,
            'status' => $this->status,
        ];
    }
}
