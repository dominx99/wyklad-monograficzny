document.querySelector('#submit-button').addEventListener('click', (e) => {
    e.preventDefault();

    var input = document.getElementById('input');

    fetch('/lista2/zadanie2/oblicz', {
        method: 'POST',
        body: JSON.stringify({
            values: input.value.split(' ').map(Number),
        }),
    }).then(function(response) {
        response.json().then(function(data) {
            setResult(data);
        })
    });
})

function setResult(data) {
    var resultTest = document.getElementById('result-test');
    var resultMean = document.getElementById('result-mean');
    var resultVariance = document.getElementById('result-variance');
    var resultH0 = document.getElementById('result-h0');

    resultTest.innerHTML = `W = ${data.test_statistic}`;
    resultMean.innerHTML = `Średnia = ${data.mean}`;
    resultVariance.innerHTML = `VAR(X) = ${data.variance}`;

    var level = document.getElementById('level').value;

    if (data.p_value < level) {
        resultH0.innerHTML = ` <strong>H0 odrzucone</strong>`;
    } else {
        resultH0.innerHTML = ` <strong>Nie ma powodów do odrzucenia H0</strong>`;
    }

    document.getElementById('result-card').classList.remove('d-none');
}

function fillInput() {
    var input = document.getElementById('input');
    input.value = "5.0 18.0 15.2 5.0 7.8 13.5 13.1";
}

function onLoad() {
    fillInput();
}

if (document.readyState !== 'loading') {
    onLoad();
} else {
    document.addEventListener('DOMContentLoaded', onLoad);
}
