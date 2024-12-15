<?php

namespace Package\XConfig\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class ConfigCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('ADD_CONFIGURATION_CATEGORIES') || auth()->user()->can('EDIT_CONFIGURATION_CATEGORIES');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if ($this->config_category){
            //update rule
            $name_rule = ['required', Rule::unique('configuration_category', 'name')->ignore($this->config_category)];
        } else {
            //store rule
            $name_rule = ['required', Rule::unique('configuration_category', 'name')];
        }

        return [
            'name' => $name_rule,
            'description' => 'nullable',
            'status' => 'nullable|boolean',
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
