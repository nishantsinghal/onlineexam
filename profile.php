<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
            <script type="text/javascript">
            var min = 1;
            var sec = 0;
            function countDown()
            {
                 var element = document.getElementById(elem);
                // element.innerHTML = "<h2>You have <b>"+secs+"</b> seconds to answer the questions</h2>";
                // if(secs < 1) {
                //     document.quiz.submit();
                // }
                // else
                // {
                //     secs--;
                //     setTimeout('countDown('+secs+',"'+elem+'")',200);
                // }

                if (parseInt(sec) >0) {

			    document.getElementById("status").innerHTML = "Time Remaining :"+min+" Minutes ," + sec+" Seconds";
                sec = parseInt(sec) - 1;                
                tim = setTimeout("countDown()", 1000);
            }
            else {

			    if (parseInt(min)==0 && parseInt(sec)==0){
			    	document.getElementById("status").innerHTML = "Time Remaining :"+min+" Minutes ," + sec+" Seconds";
				     alert("Time Up");
				     document.quiz.submit();
			     }

                if (parseInt(sec) == 0) {				
				    document.getElementById("status").innerHTML = "Time Remaining :"+min+" Minutes ," + sec+" Seconds";					
                    min = parseInt(min) - 1;
					sec=59;
                    tim = setTimeout("countDown()", 1000);
                }

            }
            }

            function validate() {
                return true;
            }
            </script> 
            <div id="status"></div>
            <script type="text/javascript">countDown();</script>
            <title>Questionnaire</title>
            <style type="text/css"> 
            span { 
                color: #FF00CC;
            }
            </style>
        </head>
        <body>
            <h1>Please complete the following Survey</h1>
            <form name="quiz" id="myquiz"  method="post" action="process.php">
                First Name: <input type="text" name="firstname" id="fname"/>
                <p></p>
                Last Name: <input type="text" name="lastname" id="lname"/>
                <p></p>
                <input type="submit" name="submitbutton" value="Go"></input>
                <input type="reset" value="clear all"></input>
            </form>
        </body>
    </html>