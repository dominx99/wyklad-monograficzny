var empiricalDistributionChart = null;

document.querySelector('#submit-button').addEventListener('click', (e) => {
    e.preventDefault();

    var input = document.getElementById('input');

    fetch('/lista1/zadanie3/oblicz', {
        method: 'POST',
        body: JSON.stringify({
            values: input.value.split(' ').map(Number),
        }),
    }).then(function(response) {
        response.json().then(function(data) {
            destroyChart();
            renderChart(data);
            setResult(data.komlogorov_distance);
        })
    });
})

function setResult(komlogorovDistance) {
    var result = document.getElementById('result');

    result.innerHTML = `Odległość Kołmogorowa: ${komlogorovDistance}`;
    document.getElementById('result-card').classList.remove('d-none');
}

function destroyChart() {
    if (empiricalDistributionChart !== null) {
        empiricalDistributionChart.destroy();
    }
}

function renderChart(data) {
    const x = data.empirical_distribution.map(bin => bin.x);
    const empiricalY = data.empirical_distribution.map(bin => bin.y);
    const uniformY = data.uniform_distribution.map(bin => bin.y);

    // Create the histogram chart
    var ctx = document.getElementById('canvas').getContext('2d');
    empiricalDistributionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: x,
            datasets: [{
                label: 'Dystrybuanta empiryczna',
                data: empiricalY,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgb(255, 99, 132)',
                stepped: true,
                fill: false,
                pointHoverRadius: 10,
            }, {
                label: 'Dystrybuanta rozkładu jednostajnego',
                data: uniformY,
                borderColor: 'rgba(0, 123, 255, 0.7)',
                fill: false,
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    grid: {
                        display: false,
                    }
                }
            },
        }
    });
}

function fillInput() {
    var input = document.getElementById('input');
    input.value = "76 80 93 78 80 78 80";
}

function onLoad() {
    fillInput();
}

if (document.readyState !== 'loading') {
    onLoad();
} else {
    document.addEventListener('DOMContentLoaded', onLoad);
}
