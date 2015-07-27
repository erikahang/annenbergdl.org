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

function toggleChatWithUs() {
	var chatwithus = $( ".chatwithus" )

	chatwithus.style.display == "block" ? chatwithus.style.display = "none" : 
	chatwithus.style.display = "block";
}

function toggleCloseChatwithUs () {
	$( ".chatwithus" ).css( "display", "none");
}