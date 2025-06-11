setTimeout(function () {
    alert("Sesja wygasła. Zaloguj się ponownie.")
    window.location.href = '/logout';
}, 600000);