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
                <label class="todo__form-container-label" for="deadline">Termin</label>
                <input class="todo__form-container-input" id="deadline" name="deadline" type="date">
            </div>
            <button type="submit" class="todo__form-container-btn">Dodaj</button>
        </form>

        @if (empty($todos))
            <p class="noFiles">Brak zadań.</p>
        @else
            <div class="todo__container">
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