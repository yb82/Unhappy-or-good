<!DOCTYPE html>
<html>
<head>
	<script src="https://code.highcharts.com/highcharts.src.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>

</head>
<body>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
	<script type="text/javascript">
		Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Stacked column chart'
    },
    xAxis: {
        categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total fruit consumption'
        }
    },
    tooltip: {
        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
        shared: true
    },
    plotOptions: {
        column: {
            stacking: 'percent'
        }
    },
    series: [{
        name: 'John',
        data: [4, 3, 4, 7, 2]
    }, {
        name: 'Jane',
        data: [2, 2, 3, 2, 1]
    }, {
        name: 'Joe',
        data: [3, 4, 4, 2, 5]
    }]
});
	</script>
</body>
</html>

