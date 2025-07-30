@extends('layouts.app')

@section('title', 'Zarządzanie kontem')

@section('content')
    <div class="container__centered">
        <form action="/changePassword" method="GET" class="container__button">
            <button type="submit" class="container__button-btn">Zmień hasło</button>
        </form>
    </div>
@endsection

{{--@section('content')--}}
{{--    <a href="/podglad-raportu" target="_blank">🔍 Podgląd raportu</a>--}}
{{--@endsection--}}