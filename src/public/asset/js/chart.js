
var mini_chart = function(element, data, color){

    var labels = [];

    var min_value;
    var max_value;

    $(data).each(function(){
        if(min_value === undefined || min_value > this.y)
            min_value = this.y ;

        if(max_value === undefined || max_value < this.y)
            max_value = this.y;
    });

    var delta = max_value - min_value;
    var start_value = min_value - delta;
    for (var i = 0; i < data.length -1 ; i++){
        labels.push(i);
    }

    new Chart(element, {

        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: labels,
            datasets: [{
                fill: true,
                label: 'value',
                backgroundColor: color,
                borderColor: color,
                data: data,
            }]
        },

        // Configuration options go here
        options: {

            hover: {
                mode: 'nearest',
                intersect: true
            }

            ,title: {
                display: false
            }

            ,legend: {
                display: false
            }

            ,tooltips: {
                mode: 'index',
                intersect: false,
                displayColors: false
            }

            ,elements: {
                point:{
                    radius: 0
                }
            }

            ,responsive: true
            ,maintainAspectRatio: false
            ,scaleBeginAtZero : true
            ,bezierCurve: true

            ,scales: {
                yAxes: [{
                    display: false

                    ,ticks: {
                        min: start_value
                    }
                }]
                ,xAxes: [{
                    display: false
                }]
            }

        }
    });


};
