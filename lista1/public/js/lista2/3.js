document.querySelector('#submit-button').addEventListener('click', (e) => {
    e.preventDefault();

    var input = document.getElementById('input');

    fetch('/lista2/zadanie3/oblicz', {
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
    var result = document.getElementById('result');
    var alpha = document.getElementById('alpha').value;

    var resultMessages = [
        `Estymator współczynnika asymetrii = <b>${data.skew}</b>`,
        `Estymator współczynnika skupienia (kurtozy) = <b>${data.kurtosis}</b>`,
        `Wartość p dla testu asymetrii = ${data.skew_test.p_value}`,
        `Wartość p dla testu skupienia (kurtozy) = ${data.kurtosis_test.p_value}`,
    ];

    if (data.skew_test.p_value > alpha) {
        resultMessages.push('Na podstawie testu asymetrii <strong>nie ma podstaw</strong> do odrzucenia hipotezy o normalności');
    } else {
        resultMessages.push('Na podsatwie testu asymetrii <strong>są podstawy</strong> do odrzucenia hipotezy o normalności')
    }

    if (data.kurtosis_test.p_value > alpha) {
        resultMessages.push('Na podsatwie testu skupienia (kurtozy) <strong>nie ma podstaw</strong> do odrzucenia hipotezy o normalności');
    } else {
        resultMessages.push('Na podsatwie testu skupienia (kurtozy) <strong>są podstawy</strong> do odrzucenia hipotezy o normalności')
    }

    result.innerHTML = resultMessages.join('<br><br>');

    document.getElementById('result-card').classList.remove('d-none');
}

function fillInput() {
    var input = document.getElementById('input');
    input.value = "60 63 64 66 67 68 69 70 70 71 72 72 74 74 80 80 81 81 81 81 81 82 83 84 84 85 85 85 89 89 90 93 95 98";
}

function onLoad() {
    fillInput();
}

if (document.readyState !== 'loading') {
    onLoad();
} else {
    document.addEventListener('DOMContentLoaded', onLoad);
}
