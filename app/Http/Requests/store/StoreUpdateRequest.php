<?php

namespace App\Http\Requests\store;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
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
            'vstoreabbr' => 'required|min:2',
            'vaddress1' => 'required|min:2',
            'vcity' => 'required|min:2',
            'vstate' => 'required|min:2',
            'vzip' => 'required|min:2',
            'vphone1' => 'required|min:2',
        ];
    }

    public function messages()
    {
        return [
            'vstoreabbr.required' => 'Store Abbr Required!',
            'vaddress1.required' => 'Address Required!',
            'vcity.required' => 'City Required!',
            'vstate.required' => 'State Required!',
            'vzip.required' => 'Zip Required!',
            'vphone1.required' => 'Phone 1 Required!'
        ];
    }


}
