<?php

namespace Package\XConfig\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class ConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('ADD_CONFIGURATIONS') || auth()->user()->can('EDIT_CONFIGURATIONS');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if ($this->configuration){
            //update rule
            $name_rule = ['required', Rule::unique('configurations', 'name')->ignore($this->configuration)];
            $display_name_rule = ['required', Rule::unique('configurations', 'display_name')->ignore($this->configuration)];
        } else {
            //store rule
            $name_rule = ['required', Rule::unique('configurations', 'name')];
            $display_name_rule = ['required', Rule::unique('configurations', 'display_name')];
        }

        return [
            'name' => $name_rule,
            'display_name' => $display_name_rule,
            'config_type' => ['required', Rule::in(['DD', 'TX'])],
            'value' => '',
            'key_array' => '',
            'value_array' => '',
            'category_id' => 'required|exists:configuration_category,id',
            'status' => 'nullable',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson()) {
            $response = response()->json([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ]);
        } else {
            $response = redirect()
                ->back()
                ->withErrors($validator)
                ->with('message', ['type' => 'warn', 'message' => 'The given data was invalid.']);
        }

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
