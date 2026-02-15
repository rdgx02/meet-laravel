<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Meet LADETEC')</title>
</head>
<body>

    <header>
        <h1>Meet LADETEC</h1>

        <nav>
            <a href="{{ route('home') }}">Início</a> |
            <a href="{{ route('rooms.index') }}">Salas</a> |
            <a href="{{ route('reservations.index') }}">Agendamentos</a>
        </nav>

        <hr>
    </header>

    <main>
        @yield('content')
    </main>

</body>
</html>