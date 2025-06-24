setTimeout(function () {
    alert("Sesja wygasła. Zaloguj się ponownie.")
    window.location.href = '/login?msg=inactive';
}, 600000);