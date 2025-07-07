@php use App\Core\Auth; @endphp
@include('partials.icons')

<nav class="nav">
    <ul class="nav__list">
        <li class="nav__list-item">
            <span class="nav__list-logo">
                <svg><use xlink:href="#logo" /></svg> Garden Manager
            </span>
        </li>
        @if (Auth::isLoggedIn())
        <li class="nav__list-item">
            <a href="/" class="nav__list-link">
                <svg><use xlink:href="#home" /></svg> Strona główna
            </a>
        </li>
        <li class="nav__list-item">
            <a href="/zones" class="nav__list-link">
                <svg><use xlink:href="#zone" /></svg> Strefy
            </a>
        </li>
        <li class="nav__list-item">
            <a href="/account" class="nav__list-link">
                <svg><use xlink:href="#account" /></svg> Moje konto
            </a>
        </li>

        <li class="nav__list-item">
            <form action="/logout" method="POST" class="nav__list-link">
                <button type="submit" class="nav__list-logoutBtn">
                    <svg><use xlink:href="#logout" /></svg> Wyloguj
                </button>
            </form>
        </li>
        @else
        <li class="nav__list-item">
            <a href="/register" class="nav__list-link">
                <svg><use xlink:href="#register" /></svg> Zarejestruj się
            </a>
        </li>
        @endif
    </ul>
</nav>
