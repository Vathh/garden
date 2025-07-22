<div class="statistics component greenhouseComponentHidden wrapper" id="statistics">
    <div class="chartContainer">
        <h2 class="chartContainer__header">Temperatura wewnętrzna</h2>
        <div class="chartContainer__select">
            <button onclick="loadChart('1h')" class="chartContainer__select-btn" id="1h-btn">Ostatnia godzina</button>
            <button onclick="loadChart('6h')" class="chartContainer__select-btn" id="6h-btn">6 godzin</button>
            <button onclick="loadChart('1d')" class="chartContainer__select-btn" id="1d-btn">Dzień</button>
            <button onclick="loadChart('7d')" class="chartContainer__select-btn" id="7d-btn">Tydzień</button>
        </div>
        <canvas class="chartContainer__chart" id="temperatureChart" height="200"></canvas>
    </div>
</div>