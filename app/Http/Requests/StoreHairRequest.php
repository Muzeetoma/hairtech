<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Hair;

class StoreHairRequest extends FormRequest
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
            'description'=> 'required|min:10',
            'length'=> 'required|integer|min:1',
            'color' => [Rule::in(Hair::hairColors()), 'required'],
            'price'=> 'required|integer',
            'discount'=> 'required|integer',
            'details' => 'array|required',
            'details.*.detail' => 'required|string',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'type' => [Rule::in(Hair::hairTypes()),'required'],
        ];
    }
}
