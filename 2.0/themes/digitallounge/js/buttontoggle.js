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

function toggleClose() {
	$( ".window" ).css( "display", "none" );
}