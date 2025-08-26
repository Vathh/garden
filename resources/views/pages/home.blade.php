@extends('layouts.app')

@include('partials.icons')

@section('title', 'Mój ogród')

@section('content')
    <div class="container">
        <div class="header__container">
            <h1 class="header__container-title">Mój ogród</h1>
            <p class="header__container-comment">Witaj ponownie {{ $userName }}! Twój ogród ma się świetnie.</p>
        </div>

        <div class="monitoring wrapper">
            <h2 class="title">Przegląd ogrodu</h2>
            <div class="overview__container">
                <div class="overview__container-field">
                    <span class="overview__container-field-title">Rośliny</span>
                    <span class="overview__container-field-value">12</span>
                    <span class="overview__container-field-diff">+2</span>
                </div>
                <div class="overview__container-field">
                    <span class="overview__container-field-title">Zadania</span>
                    <span class="overview__container-field-value">3</span>
                    <span class="overview__container-field-diff">-1</span>
                </div>
                <div class="overview__container-field">
                    <span class="overview__container-field-title">Nawadnianie</span>
                    <span class="overview__container-field-value">2</span>
                    <span class="overview__container-field-diff">+1</span>
                </div>
            </div>
        </div>

        <div class="sections wrapper">
            <h2 class="title">Sekcje</h2>
            <div class="sections__container">
                <div class="sections__field">
                    <div class="sections__field-img img-one"></div>
                    <span class="sections__field-title">Szklarnia</span>
                </div>
                <div class="sections__field">
                    <div class="sections__field-img img-two"></div>
                    <span class="sections__field-title">Narzędziownia</span>
                </div>
                <div class="sections__field">
                    <div class="sections__field-img img-three"></div>
                    <span class="sections__field-title">Kompost</span>
                </div>
                <div class="sections__field">
                    <div class="sections__field-img img-four"></div>
                    <span class="sections__field-title">Grządki</span>
                </div>
            </div>
        </div>

        <div class="tasks wrapper">
            <h2 class="title">Zadania</h2>
            <div class="tasks__container">
                <div class="tasks__container-row">
                    <div class="tasks__container-row-icon">
                        <svg><use xlink:href="#leaf"></use></svg>
                    </div>
                    <div class="tasks__container-row-description">
                        <span class="tasks__container-row-description-subject">Podlewanie pomidorow</span>
{{--                        <span class="tasks__container-row-description-activity">Podlewanie</span>--}}
                    </div>
                    <div class="tasks__container-row-deadline">
                        <span class="tasks__container-row-deadline-value">Dziś</span>
                    </div>
                </div>
                <div class="tasks__container-row">
                    <div class="tasks__container-row-icon">
                        <svg><use xlink:href="#leaf"></use></svg>
                    </div>
                    <div class="tasks__container-row-description">
                        <span class="tasks__container-row-description-subject">Nawozenie sałaty</span>
{{--                        <span class="tasks__container-row-description-activity">Nawożenie</span>--}}
                    </div>
                    <div class="tasks__container-row-deadline">
                        <span class="tasks__container-row-deadline-value">Jutro</span>
                    </div>
                </div>
                <div class="tasks__container-row">
                    <div class="tasks__container-row-icon">
                        <svg><use xlink:href="#leaf"></use></svg>
                    </div>
                    <div class="tasks__container-row-description">
                        <span class="tasks__container-row-description-subject">Przycinanie róż</span>
{{--                        <span class="tasks__container-row-description-activity">Przycinanie</span>--}}
                    </div>
                    <div class="tasks__container-row-deadline">
                        <span class="tasks__container-row-deadline-value">Za 2 dni</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection