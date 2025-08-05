<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
        color: #333;
    }

    .chart__header {
        text-align: center;
        color: #007BFF;
        font-size: 20px;
        margin-bottom: 20px;
    }

    .chart__section {
        margin-bottom: 40px;
    }

    .chart__section-header {
        font-size: 14px;
        color: #444;
        margin-bottom: 10px;
    }

    .chart__section-imgContainer {
        text-align: center;
        margin-bottom: 10px;
    }

    .summary {
        margin-top: 20px;
        border-top: 1px solid #ccc;
        padding-top: 15px;
    }

    .summary__header {
        font-size: 14px;
        color: #444;
        margin-bottom: 10px;
    }

    .summary__table {
        width: 100%;
        border-collapse: collapse;
    }

    .summary__table-th,
    .summary__table-td {
        text-align: left;
        padding: 6px 10px;
        border: 1px solid #ccc;
    }

    .summary__table-th {
        background-color: #f0f0f0;
        width: 40%;
    }
</style>

<h1 class="chart__header">Raport Temperatur</h1>

<div class="chart__section">
    <h2 class="chart__section-header">Wykres dzienny</h2>
    <div class="chart__section-imgContainer">
        <img src="data:image/png;base64,<?= $base64DayChart ?>" style="width: 100%; max-height: 500px;" />
    </div>
</div>

<div class="chart__section">
    <h2 class="chart__section-header">Wykres tygodniowy</h2>
    <div class="chart__section-imgContainer">
        <img src="data:image/png;base64,<?= $base64WeekChart ?>" style="width: 100%; max-height: 500px;" />
    </div>
</div>

<div class="chart__section" style="page-break-before: always">
    <h2 class="chart__section-header">Wykres miesięczny</h2>
    <div class="chart__section-imgContainer">
        <img src="data:image/png;base64,<?= $base64MonthChart ?>" style="width: 100%; max-height: 500px;" />
    </div>
</div>

<div class="summary">
    <h2 class="summary__header">Podsumowanie</h2>
    <table class="summary__table">
        <tr class="summary__table-tr">
            <th class="summary__table-th">Łączna liczba pomiarów</th>
            <td class="summary__table-td"><?= $totalMeasurements ?></td>
        </tr>
        <tr class="summary__table-tr">
            <th  class="summary__table-th">Średnia temperatura</th>
            <td class="summary__table-td"><?= $averageTemperature ?>°C</td>
        </tr>
        <tr class="summary__table-tr">
            <th  class="summary__table-th">Najwyższa temperatura</th>
            <td class="summary__table-td"><?= $maxTemperature ?>°C</td>
        </tr>
        <tr class="summary__table-tr">
            <th  class="summary__table-th">Najniższa temperatura</th>
            <td class="summary__table-td"><?= $minTemperature ?>°C</td>
        </tr>
        <tr class="summary__table-tr">
            <th  class="summary__table-th">Najcieplejszy dzień</th>
            <td class="summary__table-td"><?= $hottestDay ?> (<?= $hottestDayAverage ?>°C)</td>
        </tr>
    </table>
</div>