<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorageRequirementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'requireable_id' => ['required', 'integer'],
            'requireable_type' => ['required', 'string'],
            'temperature_min' => ['nullable', 'numeric'],
            'temperature_max' => ['nullable', 'numeric'],
            'humidity_min' => ['nullable', 'numeric'],
            'humidity_max' => ['nullable', 'numeric'],
            'lighting_level_id' => ['nullable', 'integer', 'exists:lighting_levels,id'],
            'ventilation_level' => ['nullable', 'string'],
            'fire_safety_class' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
