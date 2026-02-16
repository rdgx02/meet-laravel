@extends('layouts.app')

@section('title', 'Detalhes do Agendamento')

@section('content')
    <h2>Detalhes do Agendamento</h2>

    <p>
        <a href="{{ route('reservations.index') }}">Voltar para lista</a>
        |
        <a href="{{ route('reservations.edit', $reservation) }}">Editar</a>
    </p>

    <hr>

    <p><strong>Data:</strong> {{ $reservation->date_br }}</p>
    <p><strong>Início:</strong> {{ $reservation->start_time_br }}</p>
    <p><strong>Fim:</strong> {{ $reservation->end_time_br }}</p>
    <p><strong>Sala:</strong> {{ $reservation->room?->name }}</p>
    <p><strong>Título:</strong> {{ $reservation->title }}</p>
    <p><strong>Solicitante:</strong> {{ $reservation->requester }}</p>
    <p><strong>Contato:</strong> {{ $reservation->contact ?? '-' }}</p>

    <hr>

    <form method="POST"
          action="{{ route('reservations.destroy', $reservation) }}"
          onsubmit="return confirm('Tem certeza que deseja excluir este agendamento?');">
        @csrf
        @method('DELETE')
        <button type="submit">Excluir</button>
    </form>
@endsection