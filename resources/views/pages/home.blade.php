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
                    <span class="overview__container-field-title">Temperatura</span>
                    <span class="overview__container-field-value">Wewnątrz : <b>{{ $internalTemperature }}</b></span>
                    <span class="overview__container-field-value">Na zewnątrz : <b>12°C</b></span>
                </div>
                <div class="overview__container-field">
                    <span class="overview__container-field-title">Zadania</span>
                    <div class="overview__container-field-subcontainer">
                        <div class="overview__container-field-subcontainer-box">
                            <span class="overview__container-field-subcontainer-box-title">Pilne</span>
                            <span class="overview__container-field-subcontainer-box-red">{{ $urgentTodosCount }}</span>
                        </div>
                        <div class="overview__container-field-subcontainer-box">
                            <span class="overview__container-field-subcontainer-box-title">Wszystkie</span>
                            <span class="overview__container-field-subcontainer-box-green">{{ $nonUrgentTodosCount }}</span>
                        </div>
                    </div>
                </div>
                <div class="overview__container-field">
                    <span class="overview__container-field-title">Nawadnianie</span>
                    <div class="overview__container-field-subcontainer">
                        <div class="overview__container-field-subcontainer-box">
                            <span class="overview__container-field-subcontainer-box-title">Niska</span>
                            <span class="overview__container-field-subcontainer-box-red">{{ $lowHumidityCount }}</span>
                        </div>
                        <div class="overview__container-field-subcontainer-box">
                            <span class="overview__container-field-subcontainer-box-title">Średnia</span>
                            <span class="overview__container-field-subcontainer-box-yellow">{{ $mediumHumidityCount }}</span>
                        </div>
                        <div class="overview__container-field-subcontainer-box">
                            <span class="overview__container-field-subcontainer-box-title">Dobra</span>
                            <span class="overview__container-field-subcontainer-box-green">{{ $highHumidityCount }}</span>
                        </div>
                    </div>
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
            @if (empty($todos))
                <p class="noFiles">Brak zadań.</p>
            @else
                <div class="tasks__container">
                    @foreach ($todos as $todo)
                        <div class="tasks__container-row">
                            <div class="tasks__container-row-icon">
                                <svg><use xlink:href="#leaf"></use></svg>
                            </div>
                            <div class="tasks__container-row-description">
                                <span class="tasks__container-row-description-subject">{{ $todo->getTitle() }}</span>
                            </div>
                            <div class="tasks__container-row-deadline">
                                <span class="tasks__container-row-deadline-value">{{ $todo->getDeadlineDiff() ?? '—' }}</span>
                            </div>
                            <div class="tasks__container-row-menu">
                                <form method="POST" action="/todos/done">
                                    <input type="hidden" name="id" value="{{ $todo->getId() }}">
                                    <input type="hidden" name="redirect" value="/">
                                    <button class="grayBtn" type="submit">
                                        Zrobione
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection