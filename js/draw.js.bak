function visitorData (data) {
   $('#chart1').highcharts({
    chart: {
        type: 'column'
    },
    title: {
        text: 'Average Visitors'
    },
    xAxis: {
        categories: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
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
    fromdate : from,
    todate : to
    };
      var data = getRangeData(date);
      highcharts.charg('container',{data}

      );
   } else if (selectedOption =="current") {
    var data = getAllData();
      highcharts.charg('container',{data}  );
   } else if (selectedOption =="today") {
      var data = getTodayData();
      highcharts.charg('container',{data}  );
   }
   

   
});

 });
function getRangeData(fromto){
    $.post( "./update.php", { date: fromto}) .done(function( data ) {
        if(data=="[]"){
          alert( "Error" );
        } 
        else {
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
          return data;
        }
      });
}
function getToday(){
    $.post( "./update.php", { today: 1 }) .done(function( data ) {
        if(data=="[]"){
          alert( "Error" );
        } 
        else {
          return data;
        }
      });
}
