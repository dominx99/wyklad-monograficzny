var averagesChart = null
var mediansChart = null

document.querySelector('#submit-button').addEventListener('click', (e) => {
    e.preventDefault();

    var input = document.getElementById('input');

    fetch('/lista2/zadanie5/oblicz', {
        method: 'POST',
        body: JSON.stringify({
            values: input.value.split(' ').map(Number),
            m: document.getElementById('m').value,
        }),
    }).then(function(response) {
        response.json().then(function(data) {
            setResult(data);
            destroyAveragesChart();
            renderAveragesChart(data);
            destroyMediansChart();
            renderMediansChart(data);
        })
    });
})

function setResult(data) {
    document.getElementById('average-table').innerHTML = data.average_table;
    document.getElementById('median-table').innerHTML = data.median_table;

    document.getElementById('result-card').classList.remove('d-none');
}

function fillInput() {
    var input = document.getElementById('input');
    input.value = "6.5 10.0 24.7 17.4 14.4 -5.2 -14.1 10.4 24.0 17.0 -26.6 18.1 -15.2";
}

function onLoad() {
    fillInput();
}

if (document.readyState !== 'loading') {
    onLoad();
} else {
    document.addEventListener('DOMContentLoaded', onLoad);
}

function destroyAveragesChart() {
    if (averagesChart) {
        averagesChart.destroy();
    }
}

function renderAveragesChart(data) {
    var sequence = data.data;
    var windowSize = data.m;

    var movingAverages = data.averages;

    // Tworzenie wykresu
    var ctx = document.getElementById('averages').getContext('2d');
    averagesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: sequence.map((_, i) => i),
            datasets: [
                {
                    label: 'Xi',
                    data: sequence,
                    borderColor: 'red',
                    fill: false
                },
                {
                    label: 'Średnia ruchoma (m=' + windowSize + ')',
                    data: movingAverages,
                    borderColor: 'blue',
                    fill: false
                },
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Indeks'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Wartość'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Średnia ruchoma'
                }
            }
        }
    });
}

function destroyMediansChart() {
    if (mediansChart) {
        mediansChart.destroy();
    }
}

function renderMediansChart(data) {
    var sequence = data.data;
    var windowSize = data.m;

    var movingMedians = data.medians;

    // Tworzenie wykresu
    var ctx = document.getElementById('medians').getContext('2d');
    mediansChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: sequence.map((_, i) => i),
            datasets: [
                {
                    label: 'Xi',
                    data: sequence,
                    borderColor: 'red',
                    fill: false
                },
                {
                    label: 'Mediana ruchoma (m=' + windowSize + ')',
                    data: movingMedians,
                    borderColor: 'blue',
                    fill: false
                },
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Indeks'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Wartość'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Mediana ruchoma'
                }
            }
        }
    });
}

