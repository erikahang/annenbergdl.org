
//Makes Calendar Icon Display Current Date- M.Stack 
// Date Variable, all functions below rely on this 
var d = new Date();

//Declares all of the months in the year
  function getMonthOfYear() {
      var month = new Array();
      month[0] = "January";
      month[1] = "February";
      month[2] = "March";
      month[3] = "April";
      month[4] = "May";
      month[5] = "June";
      month[6] = "July";
      month[7] = "August";
      month[8] = "September";
      month[9] = "October";
      month[10] = "November";
      month[11] = "December";

      var m = month[d.getMonth()];
      document.getElementById("month").innerHTML = m;
      document.getElementById("month_event_calendar").innerHTML = m;
  }

  // Pulls the day of the week Sun-Sat
  function getDayOfWeek() {
      var weekday = new Array(7);
      weekday[0] = "Sunday";
      weekday[1] = "Monday";
      weekday[2] = "Tuesday";
      weekday[3] = "Humpday"; //Why Not?
      weekday[4] = "Thursday";
      weekday[5] = "Friday";
      weekday[6] = "Saturday";

      var w = weekday[d.getDay()];
      document.getElementById("dayofweek").innerHTML = w;
  }
 // Pulls the date numeber in the month 0-31
    function getDayOfMonth() {
      var n = d.getDate()
      document.getElementById("daynumber").innerHTML = n;

  }
    //Tells DOM to fire off Calendar Functions after DOM is ready, uses Jquery

    $( document ).ready(function() {
  
      getDayOfMonth();
      getMonthOfYear();
      getDayOfWeek();
});
 
