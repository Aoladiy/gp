<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockMovementRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'part_item_id' => ['nullable', 'integer', 'exists:part_items,id'],
            'stock_movement_type_id' => ['nullable', 'integer', 'exists:stock_movement_types,id'],
            'from_location_id' => ['nullable', 'integer', 'exists:storage_locations,id'],
            'to_location_id' => ['nullable', 'integer', 'exists:storage_locations,id'],
            'moved_at' => ['required', 'date'],
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
