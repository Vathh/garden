@extends('layouts.app')

@section('title', 'Zarządzanie kontem')

@section('content')
    <div class="container">

        <h1 class="reports__header">Raporty dobowe</h1>

        <form class="perPage__form" method="GET">
            <label class="perPage__form-label" for="per_page">Pokaż na stronę:</label>
            <select class="perPage__form-select" name="per_page" id="per_page" onchange="this.form.submit()">
                @foreach ($perPageOptions as $opt)
                    <option class="perPage__form-option" value="{{ $opt }}" @if ($perPage == $opt) selected @endif>{{ $opt }}</option>
                @endforeach
            </select>
            <input type="hidden" name="page" value="1">
        </form>

        @if(count($files) === 0)
            <p class="noFiles">Brak plików.</p>
        @else
            <div class="reports">
                @foreach($files as $date => $report)
                        <div class="reports__container">
                            <p class="reports__container-date">{{ DateTime::createFromFormat('Ymd', $date)->format('Y-m-d') }}</p>
                            <span class="reports__container-menu">
                                @if (isset($report['pdf']))
                                    <span>Raport PDF</span>
                                    <a class="reports__container-link container__button-btn" href="{{ $url . '/' . rawurlencode($report['pdf']) }}" download>Pobierz</a>
                                    <form class="reports__container-deleteForm" method="POST" action="/reports/delete" onsubmit="return confirm('Na pewno chcesz usunąć ten raport?');">
                                        <input type="hidden" name="fileName" value="{{ $report['pdf'] }}">
                                        <button class="reports__container-link container__button-btn" type="submit">Usuń</button>
                                    </form>
                                @else
                                    —
                                @endif
                            </span>
                            <div class="reports__container-separator"></div>
                            <span class="reports__container-menu">
                                @if (isset($report['xlsx']))
                                    <span>Raport Excel</span>
                                    <a class="container__button-btn reports__container-link" href="{{ $url . '/' . rawurlencode($report['xlsx']) }}" download>Pobierz</a>
                                    <form class="reports__container-deleteForm" method="POST" action="/reports/delete" onsubmit="return confirm('Na pewno chcesz usunąć ten raport?');">
                                        <input type="hidden" name="fileName" value="{{ $report['xlsx'] }}">
                                        <button class="container__button-btn reports__container-link" type="submit">Usuń</button>
                                    </form>
                                @else
                                    —
                                @endif
                            </span>
                        </div>
                @endforeach
            </div>
        @endif

        <div class="pagination">
            @if ($page > 1)
                <a class="pagination__link" href="?page={{ $page - 1 }}&per_page={{ $perPage }}">&#171; Poprzednia</a>
            @endif

            @for ($i = 1; $i <= $totalPages; $i++)
                <a class="pagination__link {{ $i === $page ? 'pagination__link-active' : '' }} " href="?page={{ $i }}&per_page={{ $perPage }}">
                    {{ $i }}
                </a>
            @endfor

            @if ($page < $totalPages)
                <a class="pagination__link" href="?page={{ $page + 1 }}&per_page={{ $perPage }}">Następna &#187;</a>
            @endif
        </div>
    </div>
@endsection