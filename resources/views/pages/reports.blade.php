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

        @if(count($pdfFiles) === 0)
            <p>Brak plików.</p>
        @else
            <div class="reports">
                @foreach($excelFiles as $excelFIle)
                        <div class="reports__container">
                            <p>Data</p>
                            <span>Pobierz EXCEL</span>
                            <span>Pobierz PDF</span>
                        </div>
                @endforeach
            </div>
        @endif


    </div>
@endsection