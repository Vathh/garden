<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <script src="/js/inactiveLogout.js"></script>
</head>
<body>
<main>
    <?php include __DIR__ . "/partials/nav.php";
            include __DIR__ . "/partials/icons.php"?>
    <div class="container">
        <div class="header__container">
            <h1 class="header__container-title">Mój ogród</h1>
            <p class="header__container-comment">Witaj ponownie Konradzie! Twój ogród ma się świetnie.</p>
        </div>

        <div class="overview wrapper">
            <h2 class="title">Przegląd ogrodu</h2>
            <div class="overview__container">
                <div class="overview__container-field">
                    <span class="overview__container-field-title">Rośliny</span>
                    <span class="overview__container-field-value">12</span>
                    <span class="overview__container-field-diff">+2</span>
                </div>
                <div class="overview__container-field">
                    <span class="overview__container-field-title">Zadania</span>
                    <span class="overview__container-field-value">3</span>
                    <span class="overview__container-field-diff">-1</span>
                </div>
                <div class="overview__container-field">
                    <span class="overview__container-field-title">Nawadnianie</span>
                    <span class="overview__container-field-value">2</span>
                    <span class="overview__container-field-diff">+1</span>
                </div>
            </div>
        </div>

        <div class="sections wrapper">
            <h2 class="title">Sekcje</h2>
            <div class="sections__container">
                <div class="sections__field">
                    <div class="sections__field-img img-one"></div>
                    <span class="sections__field-title">Szklarnia</span>
                </div>
                <div class="sections__field">
                    <div class="sections__field-img img-two"></div>
                    <span class="sections__field-title">Narzędziownia</span>
                </div>
                <div class="sections__field">
                    <div class="sections__field-img img-three"></div>
                    <span class="sections__field-title">Kompost</span>
                </div>
                <div class="sections__field">
                    <div class="sections__field-img img-four"></div>
                    <span class="sections__field-title">Grządki</span>
                </div>
            </div>
        </div>

        <div class="tasks wrapper">
            <h2 class="title">Zadania</h2>
            <div class="tasks__container">
                <div class="tasks__container-row">
                    <div class="tasks__container-row-icon">
                        <svg><use xlink:href="#leaf"></use></svg>
                    </div>
                    <div class="tasks__container-row-description">
                        <span class="tasks__container-row-description-subject">Pomidory</span>
                        <span class="tasks__container-row-description-activity">Podlewanie</span>
                    </div>
                    <div class="tasks__container-row-deadline">
                        <span class="tasks__container-row-deadline-value">Dziś</span>
                    </div>
                </div>
                <div class="tasks__container-row">
                    <div class="tasks__container-row-icon">
                        <svg><use xlink:href="#leaf"></use></svg>
                    </div>
                    <div class="tasks__container-row-description">
                        <span class="tasks__container-row-description-subject">Sałata</span>
                        <span class="tasks__container-row-description-activity">Nawożenie</span>
                    </div>
                    <div class="tasks__container-row-deadline">
                        <span class="tasks__container-row-deadline-value">Jutro</span>
                    </div>
                </div>
                <div class="tasks__container-row">
                    <div class="tasks__container-row-icon">
                        <svg><use xlink:href="#leaf"></use></svg>
                    </div>
                    <div class="tasks__container-row-description">
                        <span class="tasks__container-row-description-subject">Róże</span>
                        <span class="tasks__container-row-description-activity">Przycinanie</span>
                    </div>
                    <div class="tasks__container-row-deadline">
                        <span class="tasks__container-row-deadline-value">Za 2 dni</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
