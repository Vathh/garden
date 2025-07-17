@extends('layouts.app')

@section('title', 'Szklarnia')

@section('content')
    <div class="container">
        <div class="facade wrapper">
            <h1>Szklarnia</h1>
        </div>
        <div class="facade__img"></div>
        @include('pages.greenhousePartials.greenhouseOverview')
    </div>
@endsection