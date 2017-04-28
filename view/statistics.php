<!DOCTYPE html>

<head>
	<script src="https://code.highcharts.com/highcharts.src.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="js/draw.js"></script>
	<script type="text/javascript" src="../datepicker/jquery.datepick.js"></script>
	
	<link type="text/css" href="../datepicker/jquery.datepick.css"
	rel="stylesheet" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
</head>
<body>
<form name="boform">
<input type="radio" name="mode" value="range" id="range" checked="true">Show Data with Date
<input type="radio" name="mode" value="current" id="current">Show Data till Now
<input type="radio" name="mode" value="today" id="today">Show Today Data
<input type="button" name="refresh" id = "refresh" value="show me the money!! mother father gentle man">
<div id= "range">

Please select arrange<br/>

From :<input type="text" name="from" id="from" /> To :<input type="text" name="to" id="to"/>
</div>

</form>
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


