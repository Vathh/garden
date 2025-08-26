@extends('layouts.app')

@section('title', 'Lista zadań')

@section('content')
    <div class="container">
        <h1 class="todo__header">Zadania do wykonania</h1>

        <form class="todo__form" method="POST" action="/todos">
            <div class="todo__form-container">
                <label class="todo__form-container-label" for="title">Zadanie</label>
                <input class="todo__form-container-input" id="title" name="title" type="text" required placeholder="np. Podlać paprykę" style="min-width:300px;">
            </div>
            <div class="todo__form-container">
                <label class="todo__form-container-label" for="due_date">Termin</label>
                <input class="todo__form-container-input" id="due_date" name="due_date" type="date">
            </div>
            <button type="submit" class="todo__form-container-btn">Dodaj</button>
        </form>

        @if (empty($todos))
            <p class="noFiles">Brak zadań.</p>
        @else
            <div class="todo__container">
                @foreach ($todos as $todo)
                    <div class="todo__container-box">
                        <div class="todo__container-box-item">
                            <div class="todo__container-box-item-title">
                                {{ $todo->getTitle() }}
                            </div>
                            <div class="todo__container-box-item-deadline">
                                Termin: {{ $todo->getDeadline() ?? '—' }}
                            </div>
                        </div>
                        <div class="todo__container-box-menu">
                            <form method="POST" action="/todos/toggle">
                                <input type="hidden" name="id" value="{{ $todo->getId() }}">
                                <button type="submit">
                                    Zrobione
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection