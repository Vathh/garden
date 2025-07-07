@extends('layouts.app')

@section('title', 'Logowanie')

@section('content')
    <div class="container__centered">
        <form name="loginForm" class="form" action="/login" method="POST">
            <div class="loginForm__container">
                <h1 class="loginForm__container-header">Zaloguj się</h1>

                <label class="loginForm__container-label" for="login"><b>Login</b></label>
                <input class="loginForm__container-input" type="text" placeholder="Wprowadź login" name="login" id="login" required>

                <label class="loginForm__container-label" for="password"><b>Password</b></label>
                <input class="loginForm__container-input" type="password" placeholder="Wprowadź hasło" name="password" id="password" required>

                <button class="loginForm__container-btn" type="submit" name="loginBtn">Zaloguj</button>
            </div>
        </form>
        <p class="loginForm__question">Nie masz jeszcze konta? <a href="/register" class="loginForm__link">Zarejestruj się</a></p>
    </div>
@endsection