@extends('layouts.app')

@section('title', 'Salas')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Salas</h2>
        <p class="text-sm text-gray-600 mb-6">Lista de salas disponiveis para agendamento.</p>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Nome</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($rooms as $room)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $room->name }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if ($room->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Ativa</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">Inativa</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-5 text-sm text-gray-600">Nenhuma sala cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
