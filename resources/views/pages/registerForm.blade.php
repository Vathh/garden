@extends('layouts.app')

@section('title', 'Rejestracja')

@section('content')
    <div class="container__centered">
        <form name="registerForm" class="loginForm" action="/register" method="POST">
            <div class="loginForm__container">
                <h1 class="loginForm__container-header">Rejestracja</h1>

                <label class="loginForm__container-label" for="login"><b>Login</b></label>
                <input class="loginForm__container-input" type="text" placeholder="Wprowadź login" name="login" id="login" required>

                <label class="loginForm__container-label" for="email"><b>Email</b></label>
                <input class="loginForm__container-input" type="text" placeholder="Wprowadź adres e-mail" name="email" id="email" required>

                <label class="loginForm__container-label" for="password"><b>Hasło</b></label>
                <input class="loginForm__container-input" type="password" placeholder="Wprowadź hasło" name="password" id="password" required>

                <label class="loginForm__container-label" for="passwordRepeat"><b>Powtórz hasło</b></label>
                <input class="loginForm__container-input" type="password" placeholder="Powtórz hasło" name="passwordRepeat" id="passwordRepeat" required>

                <button class="loginForm__container-btn" type="submit" name="register">Stwórz konto</button>
            </div>
        </form>
        <p class="loginForm__question">Masz już konto? <a href="/login" class="loginForm__link">Zaloguj się</a></p>
    </div>
@endsection