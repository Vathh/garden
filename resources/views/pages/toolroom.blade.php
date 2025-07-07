@extends('layouts.app')

@section('title', 'Narzędziownia')

@section('content')
    <div class="container">
        <div class="subcontainer">
            <div class="subcontainer__row">
                <p class="subcontainer__row-title">Drzwi</p>
                <span class="subcontainer__row-value">Zamknięte</span>
            </div>
            <div class="subcontainer__row">
                <p class="subcontainer__row-title">Światło</p>
                <span class="subcontainer__row-value">Wyłączone</span>
            </div>
        </div>
        <form action="/zones" method="GET" class="container__button">
            <button type="submit" class="container__button-btn">Wstecz</button>
        </form>
    </div>
@endsection