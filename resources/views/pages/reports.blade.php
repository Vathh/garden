@extends('layouts.app')

@section('title', 'Zarządzanie kontem')

@section('content')
    <div class="container">

        <h1>Raporty dobowe</h1>

        <form method="GET">
            <label for="per_page">Pokaż na stronę:</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()">
                @foreach ($perPageOptions as $opt)
                    <option value="{{ $opt }}" @if ($perPage == $opt) selected @endif>{{ $opt }}</option>
                @endforeach
            </select>
        </form>

        @if(count($files) === 0)
            <p>Brak plików.</p>
        @else
            <div class="reports">
                @foreach($files as $datetime => $report)
                        <div class="reports__container">
                            <p>{{ \DateTime::createFromFormat('Ymd_His', $datetime)->format('Y-m-d H:i:s') }}</p>
                            <span>
                                @if (isset($report['pdf']))
                                    <a href="{{ $url . rawurlencode($report['pdf']) }}" download>Pobierz PDF</a>
                                @else
                                    —
                                @endif
                            </span>
                            <span>
                                @if (isset($report['xlsx']))
                                    <a href="{{ $url . rawurlencode($report['xlsx']) }}" download>Pobierz Excel</a>
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
                <a href="?page={{ $page - 1 }}&per_page={{ $perPage }}">&#171; Poprzednia</a>
            @endif

            @for ($i = 1; $i <= $totalPages; $i++)
                <a href="?page={{ $i }}&per_page={{ $perPage }}" @if ($i === $page) @endif>
                    {{ $i }}
                </a>
            @endfor

            @if ($page < $totalPages)
                <a href="?page={{ $page + 1 }}&per_page={{ $perPage }}">Następna &#187;</a>
            @endif
        </div>
    </div>
@endsection