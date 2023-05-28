var histogramChart = null;

document.querySelector('#submit-button').addEventListener('click', (e) => {
    e.preventDefault();

    var input = document.getElementById('input');

    fetch('/lista1/zadanie2/oblicz', {
        method: 'POST',
        body: JSON.stringify({
            values: input.value.split(' ').map(Number),
        }),
    }).then(function(response) {
        response.json().then(function(data) {
            destroyChart();
            renderChart(data);
        })
    });
})

function destroyChart() {
    if (histogramChart !== null) {
        histogramChart.destroy();
    }
}

function renderChart(data) {
    const binLabels = data.histogram.map(bin => bin.binLabel);
    const binValues = data.histogram.map(bin => bin.binCount);

    // Create the histogram chart
    var ctx = document.getElementById('canvas').getContext('2d');
    histogramChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: binLabels,
        datasets: [{
          label: 'Histogram',
          data: binValues,
          backgroundColor: 'rgba(0, 123, 255, 0.7)',
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

function fillInput() {
    var input = document.getElementById('input');
    input.value = "3.1 5.1 4.2 4.1 5.2 5.7 4.3 5.4 5.3 3.4 5.0 3.2 5.0 5.4 4.4 5 3.9 5.5 4.8 4.5 4.1 5.5 4.7 3.1 5.5 6.0 3.1 4.9 5.4 6.1 5.3 4.1 6.2 5.2 4.3 4.8 4.5 5.2 3.3 5.2";
}

function onLoad() {
    fillInput();
}

if (document.readyState !== 'loading') {
    onLoad();
} else {
    document.addEventListener('DOMContentLoaded', onLoad);
}
