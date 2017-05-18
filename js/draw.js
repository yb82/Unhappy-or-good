function visitorData (data) {
   $('#container1').highcharts({
    chart: {
        type: 'column'
    },
    title: {
        text: 'Average Visitors'
    },
    xAxis: {
        categories: ["Today"]
    },
    yAxis: {
        title: {
            text: 'Number of visitors'
        }
    },
    series: data,
  });
}
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
           Highcharts.chart('container1',{data}  );
          return data;
        }
      });
}
function getAllData(){
    $.post( "./update.php", { all: 1 }) .done(function( data ) {
        if(data=="[]"){
          alert( "Error" );
        } 
        else {
         // $("#ex").append(data);
          Highcharts.chart('container1',{data}  );
          //return data;
        }
      });
}
function getTodayData(){
    $.post( "./update.php", { today: 1 }) .done(function( data ) {
        if(data=="[]"){
          alert( "Error" );
        } 
        else {
          
          $("#ex").append(data);
          //visitorData(data);
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
