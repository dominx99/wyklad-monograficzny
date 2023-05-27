document.querySelector('#submit-button').addEventListener('click', function (e) {
    e.preventDefault();

    const form = document.querySelector('#form');
    const type = form.type.value;
    console.log('type', type);
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
    }).then(function (response) {
        response.json().then(function (data) {
            document.querySelector('#result').innerHTML = 'Wynik to: ' + data.probability;
            document.querySelector('#result-card').classList.remove('d-none');
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

    datasets.forEach(function (dataset, index) {
        fetch('/lista1/zadanie1/oblicz', {
            body: JSON.stringify(dataset),
            method: 'POST',
        }).then(function (response) {
            response.json().then(function (data) {
                document.querySelector(`#result${index + 1}`).innerHTML = data.probability;
            });
        })
    })
}

if (document.readyState !== 'loading') {
    results();
} else {
    document.addEventListener('DOMContentLoaded', results);
}
