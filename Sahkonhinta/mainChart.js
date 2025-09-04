const barColors = "gray";
new Chart("electricityPriceChart", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [
      {
        label: "Verollinen",
        data: priceValues,
        borderColor: barColors,
        backgroundColor: "rgba(0, 0, 0, 0)",
        fill: false,
        tension: 0.1
      },
      {
        label: "Veroton",
        data: priceTaxlessValues,
        borderColor: "black",
        backgroundColor: "rgba(0,0,0,0)",
        fill: false,
        tension: 0.1
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: true
      },
      title: {
        display: false,
        text: "Pörssisähkön tuntihinnat tänään sentteinä"
      }
    },
    scales: {
      y: {
        beginAtZero: true
      },
      x: {
        ticks: {
          autoSkip: true,
          maxTicksLimit: 24
        }
      }
    }
  }
});