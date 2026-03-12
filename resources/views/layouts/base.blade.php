<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" />
    <title>{{ config('app.name', 'COLEGIADOS') }}</title>

    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body
    class="m-0 antialiased"
    style="--brand-primary: {{ auth()->user()?->currentInstitution?->primary_color ?? '#0f766e' }}; --brand-secondary: {{ auth()->user()?->currentInstitution?->secondary_color ?? '#b45309' }};"
>
    {{ $slot }}

    @livewireScripts
</body>
</html>
