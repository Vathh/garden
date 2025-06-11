document.addEventListener("DOMContentLoaded", function () {
    const form = document.forms["registerForm"];

    form.addEventListener("submit", function (event) {
        const login = form["login"].value;
        const email = form["email"].value;
        const password = form["password"].value;
        const passwordRepeat = form["passwordRepeat"].value;
        let errors = [];

        if (login.length < 5 || !/^[a-zA-Z0-9]+$/.test(login)) {
            errors.push("Login musi mieć co najmniej 5 znaków i zawierać tylko litery oraz cyfry.");
        }

        if (!/^\S+@\S+\.\S+$/.test(email)) {
            errors.push("Niepoprawny adres email.");
        }

        if (
            password.length < 5 ||
            !/[A-Z]/.test(password) ||
            !/[a-z]/.test(password) ||
            !/[0-9]/.test(password)
        ) {
            errors.push("Hasło musi mieć co najmniej 5 znaków, zawierać wielką i małą literę oraz cyfrę.");
        }

        if(password !== passwordRepeat){
            errors.push("Hasła muszą być takie same.");
        }

        if (errors.length > 0) {
            event.preventDefault();
            alert(errors.join("\n"));
        }
    });
});