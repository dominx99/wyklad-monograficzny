document.querySelector('#submit-button').addEventListener('click', (e) => {
    e.preventDefault();

    var input = document.getElementById('input');

    fetch('/lista3/zadanie2/oblicz', {
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
    document.getElementById('result').innerHTML = data.result;

    document.getElementById('result-card').classList.remove('d-none');
}

function fillInput() {
    var input = document.getElementById('input');
    input.value = "18.5 12.3 20.1 1.6 14.5 15.6 17.1 35.6";
}

function onLoad() {
    fillInput();
}

if (document.readyState !== 'loading') {
    onLoad();
} else {
    document.addEventListener('DOMContentLoaded', onLoad);
}
