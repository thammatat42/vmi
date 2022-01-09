$(function () {  
    function renderChart(endpoint, type = 'bar') {
        $.ajax({  
            type: "GET",  
            url: "../../service/dashboard/" + endpoint  
        }).done(function(data) {
            label = data.response.label
            endpoint = endpoint
            type = type
            myChart = new Chart($('#visitors-chart'), {
                type: type,
                data: {
                labels: data.response.labels,
                datasets: [{
                    label: label,
                    data: data.response.results,
                    backgroundColor: '#007bff88',
                    borderColor: '#007bff',
                    borderWidth: 3,
                    datalabels: {
                    align: 'end',
                    anchor: 'end'
                    },
                }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    layout: {
                        padding: {
                            top: 32,
                            right: 20,
                            bottom: 0,
                            left: 8
                        }
                    },
                    legend: {
                        display: false //ทำให้ป้ายหัวข้อหาย
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                display: true
                            }
                        }]
                    }
                }
            })
        })
    }
    renderChart('report.php', 'bar')
})
