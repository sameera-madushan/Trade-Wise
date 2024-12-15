<?php

namespace Package\XAuth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('EDIT_PERMISSIONS');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:permissions,name,'.$this->permission.'.id',
            'display_name' => 'nullable',
            'parent_id' => 'nullable|exists:permissions,id',
        ];
    }
}
