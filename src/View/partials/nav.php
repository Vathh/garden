<?php

include __DIR__ . "/icons.php"; ?>

<nav class="nav">
    <ul class="nav__list">
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
    </ul>
</nav>