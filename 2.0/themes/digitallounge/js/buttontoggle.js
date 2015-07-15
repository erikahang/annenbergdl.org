// Makes the search window go on and off
function toggleSearch() {
  // Get the DOM reference
  var contentId = document.getElementById("search-window");
  // Toggle 
  contentId.style.display == "block" ? contentId.style.display = "none" : 
contentId.style.display = "block"; 
}

function toggleCalendar() {
  // Get the DOM reference
  var contentId = document.getElementById("calendar-window");
  // Toggle 
  contentId.style.display == "block" ? contentId.style.display = "none" : 
contentId.style.display = "block"; 
}


// Closes the window
function toggleClose() {
	$( ".window" ).css( "display", "none" );
}

//__________________________My workspace below

//
// handles the click event, sends the query
// var function getOutput() {
//    $.ajax({
//       url:'myAjax.php',
//       complete: function (response) {
//           $('#calendar-window').html(response.responseText);
//       },
//       error: function () {
//           $('#calendar-window').html('Bummer: there was an error!');
//       }
//   });
//   return false;
// }