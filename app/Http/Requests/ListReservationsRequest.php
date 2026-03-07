<?php

namespace App\Http\Requests;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;

class ListReservationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Reservation::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'in:10,20,50,100'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'q' => ['nullable', 'string', 'max:255'],
            'only_future' => ['nullable', 'boolean'],
        ];
    }
}
