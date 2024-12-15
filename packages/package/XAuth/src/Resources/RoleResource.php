<?php

namespace Package\XAuth\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Package\XAuth\Models\Permission;


class RoleResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'permissions' => PermissionResource::collection( $this->whenLoaded('permissions')),
        ];
    }
}
