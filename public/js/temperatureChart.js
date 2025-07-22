let chart;

function loadChart(range)
{
    const buttons = document.querySelectorAll('.chartContainer__select-btn');
    buttons.forEach(btn => btn.classList.remove('selectedRange'));
    document.getElementById(range + '-btn').classList.add('selectedRange');
    fetch(`/temperature?range=` + range)
        .then(res => res.json())
        .then(data => {
            const labels = data.map(d => d.time);
            const temps = data.map(d => d.temperature);

            if (chart) {
                chart.destroy();
            }

            const ctx = document.getElementById('temperatureChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Temperatura (°C)',
                        data: temps,
                        borderColor: 'rgb(44,44,44)',
                        backgroundColor: 'rgb(44,44,44)',
                        tension: 0.2
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Czas'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Temperatura (°C)'
                            }
                        }
                    }
                }
            });
        });
}

// Ładuj domyślnie zakres z 1 godziny
loadChart('1h');