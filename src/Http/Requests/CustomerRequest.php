<?php

namespace Kainotomo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'exists:App\Models\Portfolio\Commodity,guid'],
            'terms' => ['nullable', 'exists:App\Models\Portfolio\Billterm,guid'],
            'taxtable' => ['nullable', 'exists:App\Models\Portfolio\Taxtable,guid'],
            'discount_num' => ['required', 'integer'],
            'credit_num' => ['required', 'integer'],
        ];
    }
}
