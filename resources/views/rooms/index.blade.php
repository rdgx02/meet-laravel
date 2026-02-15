@extends('layouts.app')

@section('title', 'Salas')

@section('content')
    <h2>Salas</h2>

    @if ($rooms->isEmpty())
        <p>Nenhuma sala cadastrada.</p>
    @else
        <ul>
            @foreach ($rooms as $room)
                <li>
                    {{ $room->name }}
                    @if (!$room->is_active)
                        (inativa)
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
@endsection