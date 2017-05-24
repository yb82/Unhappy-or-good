
$(document).ready(function() {
    $('#from').datepick({
    
    dateFormat: 'dd/mm/yyyy'});
$('#to').datepick({
    
    dateFormat: 'dd/mm/yyyy'});


$('#refresh').click(function(e){
   var selectedOption = $('input[name=mode]:checked').val();
   var from = $("#from").val();
   var to = $("#to").val();
   if(selectedOption=="range")
   { 
    if(!from || !to){
      alert("please enter the dates");
      return 0;
    }
    var date = {
    from, to
    };
     var data = getRangeData(date);


     

   } else if (selectedOption =="current") {
    var data = getAllData();
      Highcharts.chart('container',{data}  );
   } else if (selectedOption =="today") {
      var data = getTodayData();
      Highcharts.chart('container',{data}  );
   }
   

   
});

 });
function getRangeData(fromto){
    $.post( "./update.php", { 'date': fromto}) .done(function( data ) {
        if(data=="[]"){
          alert( "Error" );
        } 
        else {
        
          //visitorData(data);
         var myObj = JSON.parse(data);
         var categories =myObj["categories"];
         
         var series = myObj["series"];

         var categoriesData =[];
         for (var i =0; categories.length > i ; i++) {
           categoriesData.push(categories[i]);
        }

      var chart ={
              chart: {type: 'column'},
          title: {     text: 'Stacked column chart'  },
          xAxis: {      categories: []            },
          yAxis: {
        min: 0,
        title: {
            text: 'Total fruit consumption'
        },
        stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
            }
        }
    },
            series:[]

           };
           chart.series=series;
           chart.xAxis.categories= categoriesData;
         Highcharts.chart('container1',chart);
          
        }
      });
}
function getAllData(){
    $.post( "./update.php", { all: 1 }) .done(function( data ) {
        if(data=="[]"){
          alert( "Error" );
        } 
        else {
         
           
          //visitorData(data);
         
          var myObj = JSON.parse(data);
          var seriesData =[];
          for (var i =0; myObj.length > i ; i++) {
           seriesData.push({name:myObj[i].name,data:[parseInt(myObj[i].data)]});
          }
           
           var chart ={
              chart: {type: 'column'},
          title: {     text: 'Stacked column chart'  },
          xAxis: {      categories: ["All Data"]            },
            yAxis: {
              min: 0,
              title: {
                text: 'Level of Student satisfaction'
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
            series:[]

           };
           chart.series=seriesData;
           Highcharts.chart('container1',chart);
          
        }
      });
}
function getTodayData(){
    $.post( "./update.php", { today: 1 }) .done(function( data ) {
        if(data=="[]"){
          alert( "Error" );
        } 
        else {
          
         // $("#ex").append(data);
          var myObj = JSON.parse(data);
          var seriesData =[];
          for (var i =0; myObj.length > i ; i++) {
           seriesData.push({name:myObj[i].name,data:[parseInt(myObj[i].data)]});
          }
           
           var chart ={
              chart: {type: 'column'},
          title: {     text: 'Stacked column chart'  },
          xAxis: {      categories: ["Today"]            },
            yAxis: {
              min: 0,
              title: {
                text: 'Level of Student satisfaction'
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
            series:[]

           };
           chart.series=seriesData;
           Highcharts.chart('container1',chart);
          
        }
      });
}
