var chart = null;

document.querySelector('#submit-button').addEventListener('click', function(e) {
    e.preventDefault();

    const form = document.querySelector('#form');
    const type = form.type.value;
    var values = [
        document.querySelector(`#${type}-value1`).value
    ];

    if (document.querySelector(`#${type}-value2`)) {
        values.push(document.querySelector(`#${type}-value2`).value);
    }

    const data = {
        operator: type,
        mean: document.querySelector('#mean').value,
        standardDeviation: document.querySelector('#standardDeviation').value,
        values: values,
    }

    fetch('/lista1/zadanie1/oblicz', {
        body: JSON.stringify(data),
        method: 'POST',
    }).then(function(response) {
        response.json().then(function(data) {
            console.log('render');

            document.querySelector('#result').innerHTML = 'Wynik to: ' + data.probability;
            document.querySelector('#result-card').classList.remove('d-none');

            destroyChart();
            renderChart(data.meta);
        });
    })
})

function results() {
    const datasets = [
        {
            mean: 1,
            standardDeviation: 2,
            operator: 'more',
            values: [0],
        },
        {
            mean: 1,
            standardDeviation: 2,
            operator: 'between',
            values: [-1, 2],
        },
        {
            mean: 1,
            standardDeviation: 2,
            operator: 'less',
            values: [3],
        },
        {
            mean: 1,
            standardDeviation: 2,
            operator: 'sigma3',
            values: [1],
        },
    ];

    datasets.forEach(function(dataset, index) {
        fetch('/lista1/zadanie1/oblicz', {
            body: JSON.stringify(dataset),
            method: 'POST',
        }).then(function(response) {
            response.json().then(function(data) {
                document.querySelector(`#result${index + 1}`).innerHTML = data.probability;
            });
        })
    })
}

function destroyChart() {
    if (chart !== null) {
        chart.destroy();
    }
}

function renderChart(meta) {
    console.log(meta);
    var ctx = document.getElementById('chart').getContext('2d');
    const labels = meta.probabilityDensityFunction.map(value => value.x);
    const probabilityDensityFunction = meta.probabilityDensityFunction.map(value => value.y);
    const probability = meta.probability.map(value => value.y);

    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: `N(${meta.mean}, ${meta.standardDeviation})`,
                    data: probabilityDensityFunction,
                    borderColor: 'black',
                    borderWidth: 1,
                    fill: false
                },
                {
                    label: `N(${meta.mean}, ${meta.standardDeviation})`,
                    data: probability,
                    backgroundColor: 'rgba(160, 32, 240, .5)',
                    fill: true
                }
            ]
        },
        options: {
            plugins: {
                colorschemes: {
                    scheme: 'tableau.ClassicMedium10'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

if (document.readyState !== 'loading') {
    results();
} else {
    document.addEventListener('DOMContentLoaded', results);
}
