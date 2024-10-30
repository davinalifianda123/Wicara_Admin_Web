async function fetchDonutChartData() {
  try {
    const response = await fetch('http://localhost/Wicara_Admin_Web/Back-end/api_donut_chart.php'); // URL endpoint baru
    const data = await response.json();
    
    // Menyiapkan data untuk grafik
    const series = data.map(item => item.percentage);
    const labels = data.map(item => item.kejadian);

    // Memperbarui opsi grafik
    const chartOptions = getChartOptions(series, labels);
    const chart = new ApexCharts(document.getElementById("donut-chart"), chartOptions);
    chart.render();
  } catch (error) {
    console.error('Error fetching donut chart data:', error);
  }
}

const getChartOptions = (series, labels) => {
  return {
      series: series,
      colors: ["#4270C3", "#DC7274", "#CD7014"],
      chart: {
          height: "100%",
          width: "100%",
          type: "donut",
      },
      stroke: {
          colors: ["transparent"],
      },
      plotOptions: {
          pie: {
              donut: {
                  labels: {
                      show: true,
                      name: {
                          show: true,
                          offsetY: 8,
                      },
                      total: {
                          showAlways: true,
                          show: true,
                          label: "WICARA",
                          formatter: function () {
                              return ''
                          },
                      },
                      value: {
                          show: true,
                      },
                  },
                  size: "75%",
              },
          },
      },
      grid: {
          padding: {
              top: -2,
          },
      },
      labels: labels,
      dataLabels: {
          enabled: false,
      },
      legend: {
          position: "right",
          markers: {
              width: 70,
              height: 10,
          },
          itemMargin: {
              horizontal: 5,
              vertical: 5,
          },
          formatter: function(seriesName, opts) {
              return seriesName + ": " + opts.w.globals.series[opts.seriesIndex].toFixed(2) + "%"; // Format dengan 2 desimal
          }
      },
      responsive: [{
          breakpoint: 768,
          options: {
              legend: {
                  position: "bottom",
              }
          }
      }]
  }
}

// Memanggil fungsi untuk mengambil data dan menampilkan grafik
fetchDonutChartData();

const options = {
    chart: {
      height: "100%",

      type: "line",
      fontFamily: "Inter, sans-serif",
      dropShadow: {
        enabled: false,
      },
      toolbar: {
        show: false,
      },
    },
    tooltip: {
      enabled: true,
      x: {
        show: false,
      },
    },
    dataLabels: {
      enabled: true,
    },
    stroke: {
      width: 6,
    },
    grid: {
      show: true,
      strokeDashArray: 4,
      padding: {
        left: 4,
        right: 2,
        top: -26
      },
    },
    series: [
      {
        name: "Pengaduan",
        data: [120, 150, 200, 175, 125, 115],
        color: "#4270C3",
      },
      {
        name: "Laporan Kehilangan",
        data: [25, 15, 5, 10, 5, 35],
        color: "#DC7274",
      },
      {
        name: "Rating",
        data: [65, 145, 75, 95, 125, 115],
        color: "#CD7014",
      },
    ],
    legend: {
      show: true
    },
    stroke: {
      curve: 'smooth'
    },
    xaxis: {
      categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
      labels: {
        show: true,
        style: {
          fontFamily: "Inter, sans-serif",
          cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
        }
      },
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
    },
    yaxis: {
      show: true,
    },
  }
  
  if (document.getElementById("line-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.getElementById("line-chart"), options);
    chart.render();
  }
  