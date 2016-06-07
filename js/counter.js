var min = 30;
var sec = 0;
function countDown()
{
    if (parseInt(sec) >0) {

      document.getElementById("status").innerHTML = "Time left :"+min+" Minutes " + sec+" Seconds";
      sec = parseInt(sec) - 1;                
      tim = setTimeout("countDown()", 1000);
    }
    else {
      if (parseInt(min)==0 && parseInt(sec)==0){
    	document.getElementById("status").innerHTML = "Time left :"+min+" Minutes " + sec+" Seconds";
	    alert("Time Up");
	    document.quiz.submit();
      }

      if (parseInt(sec) == 0) {				
	    document.getElementById("status").innerHTML = "Time left :"+min+" Minutes " + sec+" Seconds";					
        min = parseInt(min) - 1;
		sec=59;
        tim = setTimeout("countDown()", 1000);
      }

    }
}