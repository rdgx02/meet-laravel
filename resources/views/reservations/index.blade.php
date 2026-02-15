@extends('layouts.app')

@section('title', 'Agendamentos')

@section('content')
    <h2>Agendamentos</h2>

    <p>
        <a href="{{ route('reservations.create') }}">
            Novo Agendamento
        </a>
    </p>

    @if ($reservations->isEmpty())
        <p>Nenhum agendamento cadastrado.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Sala</th>
                    <th>Título</th>
                    <th>Solicitante</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $r)
                    <tr>
                        <td>{{ $r->date_br }}</td>
                        <td>{{ $r->start_time_br }}</td>
                        <td>{{ $r->end_time_br }}</td>
                        <td>{{ $r->room?->name }}</td>
                        <td>{{ $r->title }}</td>
                        <td>{{ $r->requester }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection