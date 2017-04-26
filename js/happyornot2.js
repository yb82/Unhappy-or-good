$(document).ready(function() {

  $("#happy").click(function(e){
       sendFeedback(4);      
  });

  $("#good").click(function(e){
       sendFeedback(3);      
  });


  $("#soso").click(function(e){
       sendFeedback(2);      
  });


  $("#angry").click(function(e){
       sendFeedback(1);      
  });



function timeAlert(msg,duration)
{
  var el = document.createElement("div");
  el.setAttribute("style","position:absolute;top:40%;left:20%;background-color:white;");
  el.innerHTML = msg;
  setTimeout(function(){el.parentNode.removeChild(el);},duration);
  document.body.appendChild(el);
}
function sendFeedback(rate){
    $.post( "./update.php", { emo: rate }) .done(function( data ) {
        if(data=="[]"){
          alert( "Error" );
        } 
        else {
          timeAlert("Thanks for your Feedback",5000);
        }
      });
}
