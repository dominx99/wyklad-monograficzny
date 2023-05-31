var chart = null;
var histogramChart = null;

document.querySelector('#histogram-button').addEventListener('click', (e) => {
    e.preventDefault();

    var input = document.getElementById('input');

    fetch('/lista1/zadanie2/oblicz', {
        method: 'POST',
        body: JSON.stringify({
            values: input.value.split(' ').map(Number),
        }),
    }).then(function(response) {
        response.json().then(function(data) {
            destroyHistogramChart();
            renderHistogramChart(data);
        })
    });
})

function destroyHistogramChart() {
    if (histogramChart) {
        histogramChart.destroy();
    }
}

function renderHistogramChart(data) {
    const binLabels = data.histogram.map(bin => bin.binLabel);
    const binValues = data.histogram.map(bin => bin.binCount);

    // Create the histogram chart
    const ctx = document.getElementById('histogram').getContext('2d');
    histogramChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: binLabels,
            datasets: [{
                label: 'Histogram',
                data: binValues,
                backgroundColor: 'rgba(0, 150, 136, .7)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'X'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'N'
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }
            }
        }
    });
}



document.querySelector('#submit-button').addEventListener('click', (e) => {
    e.preventDefault();

    var input = document.getElementById('input');

    fetch('/lista1/zadanie4/oblicz', {
        method: 'POST',
        body: JSON.stringify({
            values: input.value.split(' ').map(Number),
        }),
    }).then(function(response) {
        response.json().then(function(data) {
            setResult(data);
            destroyChart();
            renderChart(data);
        })
    });
})

function destroyChart() {
    if (chart) {
        chart.destroy();
    }
}

function renderChart(data) {
    const ctx = document.getElementById('canvas').getContext('2d');
    console.log('render');
    var data = {
        labels: ['VAR1'],
        datasets: [{
            label: 'Wykres ramkowy',
            backgroundColor: 'rgba(160, 32, 240, .5)',
            borderColor: 'rgba(160, 32, 240, 1)',
            borderWidth: 1,
            outlierColor: '#999999',
            padding: 10,
            itemRadius: 0,
            data: [
                {
                    min: data.min,
                    max: data.max,
                    q1: data.q1,
                    median: data.median,
                    q3: data.q3,
                    mean: data.mean,
                }
            ]
        }]
    };

    // Konfiguracja wykresu
    var options = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        scales: {
            x: {
                display: true,
                beginAtZero: false,
            },
            y: {
                display: true,
                beginAtZero: false,
            }
        }
    };

    chart = new Chart(ctx, {
        type: 'boxplot',
        data: data,
        options: options
    });
}

function setResult(data) {
    document.querySelector('#result-mean').innerHTML = data.mean;
    document.querySelector('#result-std').innerHTML = data.empirical_standard_deviation;
    document.querySelector('#result-skewness').innerHTML = data.skewness;
    document.querySelector('#result-kurtois').innerHTML = data.kurtosis_coeff;

    document.querySelector('#result-card').classList.remove('d-none');
}

function fillInput() {
    var input = document.getElementById('input');
    input.value = "6.2 3.5 5.4 5.2 5.1 3.1 6.8 4.8 5.6 6.1 4.7 3.5 6.4 3.8 4.9 6.1";
}

function onLoad() {
    fillInput();
}

if (document.readyState !== 'loading') {
    onLoad();
} else {
    document.addEventListener('DOMContentLoaded', onLoad);
}
