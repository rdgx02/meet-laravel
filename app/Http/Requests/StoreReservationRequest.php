<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Sem login por enquanto: permitimos qualquer acesso.
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id'     => ['required', 'exists:rooms,id'],
            'date'        => ['required', 'date', 'after_or_equal:today'],
            'start_time'  => ['required'],
            'end_time'    => ['required', 'after:start_time'],
            'title'       => ['required', 'string', 'max:255'],
            'requester'   => ['required', 'string', 'max:255'],
            'contact'     => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'Selecione uma sala.',
            'room_id.exists' => 'Sala inválida.',
            'date.required' => 'Informe a data.',
            'date.after_or_equal' => 'A data não pode ser no passado.',
            'start_time.required' => 'Informe o horário de início.',
            'end_time.required' => 'Informe o horário de fim.',
            'end_time.after' => 'O horário de fim deve ser após o horário de início.',
            'title.required' => 'Informe o título do agendamento.',
            'requester.required' => 'Informe o solicitante.',
        ];
    }
}