@extends('layout')

@push('styles')
    @vite(['resources/css/admin-panel.css'])
@endpush

@section('content')
    <section class="admin-panel">

        <h1>Panel administratora</h1>
        <p>Witaj w panelu administratora. Wybierz zakładkę, aby rozpocząć.</p>
        @include('admin.partials.nav')
    </section>
@endsection
