<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealerPurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'dealer_id' => [
                'required',
                'exists:dealers,id',
            ],

            'depot_id' => [
                'required',
                'exists:depots,id',
            ],

            'po_date' => [
                'required',
                'date',
            ],

            'expected_delivery_date' => [
                'nullable',
                'date',
                'after_or_equal:po_date',
            ],

            'delivery_address' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'note' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit' => [
                'nullable',
                'string',
                'max:50',
            ],

            'items.*.unit_price' => [
                'required',
                'numeric',
                'gte:0',
            ],

            'items.*.discount_amount' => [
                'nullable',
                'numeric',
                'gte:0',
            ],

            'items.*.tax_amount' => [
                'nullable',
                'numeric',
                'gte:0',
            ],

        ];
    }
}