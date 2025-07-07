@extends('layouts.app')

@section('title', 'Szklarnia')

@section('content')
    <div class="container">
        <div class="facade wrapper">
            <h1>Szklarnia</h1>
            <div class="facade__img"></div>
        </div>
        <div class="monitoring wrapper">
            <h2 class="title">Monitoring</h2>
            <div class="greenhouse__container">
                <div class="greenhouse__field">
                    <span class="greenhouse__field-desc">Temperatura zewnętrzna</span>
                    <span class="greenhouse__field-value">22°C</span>
                </div>
                <div class="greenhouse__field">
                    <span class="greenhouse__field-desc">Temperatura wewnątrz</span>
                    <span class="greenhouse__field-value">25°C</span>
                </div>
            </div>
            <div class="greenhouse__field">
                <span class="greenhouse__field-desc">Drzwi</span>
                <span class="greenhouse__field-value">Otwarte</span>
            </div>
        </div>
        <div class="controls wrapper">
            <h2 class="title">Sterowanie</h2>
            <div class="controls__container">
                <button class="controls__container-btn">Włącz przednią wentylację</button>
                <button class="controls__container-btn">Włącz tylną wentylację</button>
            </div>
        </div>
        <div class="irrigation wrapper">
            <h2 class="title">Nawadnianie</h2>
            <div class="greenhouse__container">
                <div class="greenhouse__field">
                    <span class="greenhouse__field-desc">Status</span>
                    <span class="greenhouse__field-value">Włączone</span>
                </div>
                <div class="greenhouse__field">
                    <span class="greenhouse__field-desc">Dzienne zużycie wody</span>
                    <span class="greenhouse__field-value">15L</span>
                </div>
            </div>
        </div>
    </div>
@endsection