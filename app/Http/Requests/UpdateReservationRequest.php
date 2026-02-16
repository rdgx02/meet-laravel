<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
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
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i', 'after:start_time'],
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
            'start_time.date_format' => 'Horário de início inválido (use HH:MM).',

            'end_time.required' => 'Informe o horário de fim.',
            'end_time.date_format' => 'Horário de fim inválido (use HH:MM).',
            'end_time.after' => 'O horário de fim deve ser após o horário de início.',

            'title.required' => 'Informe o título do agendamento.',
            'requester.required' => 'Informe o solicitante.',
        ];
    }
}