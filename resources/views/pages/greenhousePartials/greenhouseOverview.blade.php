<div class="overview component greenhouseComponentHidden" id="overview">
    <div class="monitoring wrapper">
        <div class="monitoring__img"></div>
        <h2 class="title">Monitoring</h2>
        <div class="greenhouse__container">
            <div class="greenhouse__field">
                <span class="greenhouse__field-desc">Temperatura zewnętrzna</span>
                <span class="greenhouse__field-value">22°C</span>
            </div>
            <div class="greenhouse__field">
                <span class="greenhouse__field-desc">Temperatura wewnątrz</span>
                <span class="greenhouse__field-value">{{ $internalTemperature }}°C</span>
            </div>
        </div>
        <div class="greenhouse__field">
            <span class="greenhouse__field-desc">Drzwi</span>
            <span class="greenhouse__field-value">Otwarte</span>
        </div>
    </div>
    <div class="irrigation wrapper">
        <h2 class="title">Nawadnianie</h2>
        <div class="greenhouse__container">
            <div class="greenhouse__field">
                <span class="greenhouse__field-desc">Status</span>
                <span class="greenhouse__field-value">Włączone</span>
            </div>
            <div class="greenhouse__field">
                <span class="greenhouse__field-desc">Dzienne zużycie wody</span>
                <span class="greenhouse__field-value">15L</span>
            </div>
        </div>
    </div>
</div>