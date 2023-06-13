document.querySelector('#submit-button').addEventListener('click', (e) => {
    e.preventDefault();

    var input = document.getElementById('input');

    fetch('/lista3/zadanie3/oblicz', {
        method: 'POST',
        body: JSON.stringify({
            values: input.value.split(' ').map(Number),
            alpha: document.getElementById('alpha').value,
        }),
    }).then(function(response) {
        response.json().then(function(data) {
            setResult(data);
        })
    });
})

function setResult(data) {
    document.getElementById('result-sign').innerHTML = data.sign_result;
    document.getElementById('result-turning_points').innerHTML = data.turning_points_result;

    document.getElementById('result-card').classList.remove('d-none');
}

function fillInput() {
    var input = document.getElementById('input');
    input.value = "-9.5 -30.1 22.1 5.8 8.0 11.6 -9.8 30.6 -14.7 3.5 -4.8 13.4 -4.1 27.0 10.6 -5.3 16.3 -23.9 -1.3 -15.7 20.2 15.2 -6.0 -11.8 -9.3 5.8";
}

function onLoad() {
    fillInput();
}

if (document.readyState !== 'loading') {
    onLoad();
} else {
    document.addEventListener('DOMContentLoaded', onLoad);
}
