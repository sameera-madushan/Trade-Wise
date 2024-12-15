<?php

namespace Package\XCalendar\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTradesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'curruncy_pair' => 'nullable|string',
            'buy_value' => 'nullable|numeric',
            'buy_price' => 'nullable|numeric',
            'sell_value' => 'nullable|numeric',
            'sell_price' => 'nullable|numeric',
            'bought_amount' => 'nullable|numeric',
            'position' => 'nullable|string',
            'pnl' => 'nullable|numeric',
            'editing' => 'boolean',
            'rowId' => 'nullable',

        ];
    }
}
