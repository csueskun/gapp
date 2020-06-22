var productoPedidoId = 0;
var app = angular.module('myApp', ['ngAnimate']);
app.controller('informeController', function($scope, $http) {
    $scope.data = [];
    $scope.filter = {
        fecha_inicio: '',
        fecha_fin: '',
    }
    //$scope.loadData();

    $scope.loadData = function(){
        console.log($scope.filter);
        mostrarFullLoading();
        $http.post("/producto_vendido", $scope.filter)
        .then(
            function(response){
                console.log(response)
                if(response.status == 200){
                    $scope.data = response.data;
                    showData();
                }
                ocultarFullLoading();
            }
        );
    }

    function showData() {
        var canvasHeight = $scope.data.data.length * 25 + 200 + 'px';

        var id = new Date().getTime();
        $('#canvas-container').html(`<canvas id="canvas${id}" height="${canvasHeight}" style="width: 100%;"></canvas>`);
        
        var colors_list = [
            "rgb(54, 162, 235)",
            "rgb(255, 99, 132)",
            "rgb(255, 159, 64)",
            "rgb(153, 102, 255)",
            "rgb(75, 192, 192)",
            "rgb(255, 205, 86)",
        ]
        var colors = [];
        for (let i = 0; i < 20; i++) {
            colors_list.forEach(element => {
                colors.push(element);
            });
        }

        var labels = [];
        var data = [];
        $scope.data.data.forEach(element => {
            labels.push(element.tipo + ' ' + element.descripcion);
            data.push(element.cantidad);
        });
		var horizontalBarChartData = {
			labels: labels,
			datasets: [{
				label: 'Productos más vendidos',
				borderWidth: 1,
                data: data,
                backgroundColor: colors
			}]

        };
        var ctx = document.getElementById('canvas'+id).getContext('2d');
        window.myHorizontalBar = new Chart(ctx, {
            type: 'horizontalBar',
            data: horizontalBarChartData,
            options: {
                maintainAspectRatio: false,
                elements: {
                },
                responsive: false,
                title: {
                    display: false,
                    text: 'Productos más vendidos'
                },
                scales: {
                    xAxes: [{
                        position: 'top',
                        ticks: {
                            min: 0 // Edit the value according to what you need
                        }
                    }],
                    yAxes: [{
                    }]
                },
            }
        });
        

    };

    $scope.deleteChart = function(){
        var chart = window.myHorizontalBar;
        chart.data.labels.pop();
        chart.data.datasets.forEach((dataset) => {
            dataset.data.pop();
        });
        chart.update();
    }

});