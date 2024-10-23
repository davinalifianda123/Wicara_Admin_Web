//Presentasi Aktifitas Donat
const getChartOptions = () => {
  return {
      series: [65, 40, 5],
      colors: ["#4270C3", "#DC7274", "#CD7014"],
      chart: {
          height: 320,
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
                          fontFamily: "Inter, sans-serif",
                          offsetY: 8,
                      },
                      total: {
                          showAlways: true,
                          show: true,
                          label: "WICARA",
                          fontFamily: "Inter, sans-serif",
                          formatter: function () {
                              return ''
                          },
                      },
                      value: {
                          show: true,  // Disable the value display inside the donut
                      },
                  },
                  size: "80%",
              },
          },
      },
      grid: {
          padding: {
              top: -2,
          },
      },
      labels: ["Pengaduan", "Kehilangan", "Rating"],
      dataLabels: {
          enabled: false,
      },
      legend: {
          position: "right",  // Move legend to the right
          fontFamily: "Inter, sans-serif",
          markers: {
              width: 70,
              height: 10,
          },
          itemMargin: {
              horizontal: 10,
              vertical: 5,
          },
          formatter: function(seriesName, opts) {
              return seriesName + ": " + opts.w.globals.series[opts.seriesIndex] + "%";
          }
      },
      responsive: [{
          breakpoint: 768,
          options: {
              legend: {
                  position: "bottom",  // For smaller screens, move legend to bottom
              }
          }
      }]
  }
}

if (document.getElementById("donut-chart") && typeof ApexCharts !== 'undefined') {
  const chart = new ApexCharts(document.getElementById("donut-chart"), getChartOptions());
  chart.render();
}


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
  