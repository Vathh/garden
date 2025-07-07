@extends('layouts.app')

@section('title', 'Strefy')

@section('content')
    <div class="container__centered">
        <h1>Strefy</h1>
        <form action="/zones/greenhouse" method="GET" class="container__button">
            <button type="submit" class="container__button-btn">Szklarnia</button>
        </form>

        <form action="/zones/toolroom" method="GET" class="container__button">
            <button type="submit" class="container__button-btn">Narzędziownia</button>
        </form>
    </div>
@endsection