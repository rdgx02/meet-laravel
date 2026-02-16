@extends('layouts.app')

@section('title', 'Editar Agendamento')

@section('content')
    <h2>Editar Agendamento</h2>

    @if ($errors->any())
        <div style="border: 1px solid red; padding: 10px; margin-bottom: 10px;">
            <strong>Erro ao salvar:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p>
        <a href="{{ route('reservations.show', $reservation) }}">Voltar para detalhes</a>
        |
        <a href="{{ route('reservations.index') }}">Voltar para lista</a>
    </p>

    <form method="POST" action="{{ route('reservations.update', $reservation) }}">
        @csrf
        @method('PUT')

        <p>
            <label>Sala:</label><br>
            <select name="room_id" required>
                <option value="">Selecione</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}"
                        {{ old('room_id', $reservation->room_id) == $room->id ? 'selected' : '' }}>
                        {{ $room->name }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label>Data:</label><br>
            <input type="date" name="date" value="{{ old('date', $reservation->date) }}" required>
        </p>

        <p>
            <label>Hora Início:</label><br>
            <input type="time" name="start_time" value="{{ old('start_time', $reservation->start_time) }}" required>
        </p>

        <p>
            <label>Hora Fim:</label><br>
            <input type="time" name="end_time" value="{{ old('end_time', $reservation->end_time) }}" required>
        </p>

        <p>
            <label>Título:</label><br>
            <input type="text" name="title" value="{{ old('title', $reservation->title) }}" required>
        </p>

        <p>
            <label>Solicitante:</label><br>
            <input type="text" name="requester" value="{{ old('requester', $reservation->requester) }}" required>
        </p>

        <p>
            <label>Contato:</label><br>
            <input type="text" name="contact" value="{{ old('contact', $reservation->contact) }}">
        </p>

        <p>
            <button type="submit">Salvar Alterações</button>
        </p>
    </form>
@endsection