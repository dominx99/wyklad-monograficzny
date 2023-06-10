document.querySelector('#submit-button').addEventListener('click', (e) => {
    e.preventDefault();

    var input = document.getElementById('input');

    fetch('/lista2/zadanie1/oblicz', {
        method: 'POST',
        body: JSON.stringify({
            values: input.value.split(' ').map(Number),
            alpha: document.getElementById('alpha').value,
            excludeDuplicates: document.getElementById('exclude-duplicates').checked,
        }),
    }).then(function(response) {
        response.json().then(function(data) {
            setResult(data);
        })
    });
})

function setResult(data) {
    document.getElementById('table').innerHTML = data.table;
    document.getElementById('result-mean').innerHTML = `x<sub>śr</sub>=` + data.mean;
    document.getElementById('result-s').innerHTML = `S<sub>1</sub>=` + data.s;
    document.getElementById('result-s1').innerHTML = `S<sub>1</sub>=` + data.s1;

    if (data.maximum > data.criticalValue) {
        document.getElementById('result-value').innerHTML = `d<sub>${data.n}</sub>=` + data.maximum + ' > ' + `k<sub>${data.n}</sub>=` +  data.criticalValue;
        document.getElementById('result-message').innerHTML = 'hipotezę H odrzucamy';
    } else {
        document.getElementById('result-value').innerHTML = `d<sub>${data.n}</sub>=` + data.maximum + ' < ' + `k<sub>${data.n}</sub>=` +  data.criticalValue;
        document.getElementById('result-message').innerHTML = 'nie ma podstaw do odrzucenia hipotezy o normalności';
    }

    document.getElementById('result-card').classList.remove('d-none');
}

function fillInput() {
    var input = document.getElementById('input');
    input.value = "11.1 5.4 18.0 7.8 15.2 13.5 15.2 20.0 7.8";
}

function onLoad() {
    fillInput();
}

if (document.readyState !== 'loading') {
    onLoad();
} else {
    document.addEventListener('DOMContentLoaded', onLoad);
}
