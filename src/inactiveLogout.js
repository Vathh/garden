setTimeout(function () {
    alert("Sesja wygasła. Zaloguj się ponownie.")
    window.location.href = 'logout.php';
}, 10000);