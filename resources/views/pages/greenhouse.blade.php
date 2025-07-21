@extends('layouts.app')

@section('title', 'Szklarnia')

@section('content')
    <div class="container">
        <div class="facade wrapper">
            <h1>Szklarnia</h1>
            <div class="componentSelect">
                <button onclick="showComponent('overview')" class="componentSelect__btn" id="overview-btn">Przegląd</button>
                <button onclick="showComponent('statistics')" class="componentSelect__btn" id="statistics-btn">Statystyki</button>
                <button onclick="showComponent('settings')" class="componentSelect__btn" id="settings-btn">Ustawienia</button>
            </div>
        </div>

        @include('pages.greenhousePartials.greenhouseOverview')
        @include('pages.greenhousePartials.greenhouseStatistics')
        @include('pages.greenhousePartials.greenhouseSettings')
    </div>
@endsection

@section('scripts')
    <script src="/js/greenhouseComponentSelect.js"></script>
    <script src="/js/temperatureChart.js"></script>
@endsection
