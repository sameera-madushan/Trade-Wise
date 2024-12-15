<?php

namespace Package\XAuth\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'key' => $this->id,
            'data' => $this->name,
            'label' => $this->display_name,
            'parent_id' => $this->parent_id,
            'children' => PermissionResource::collection($this->whenLoaded('subPermissions')),
        ];
    }
}
