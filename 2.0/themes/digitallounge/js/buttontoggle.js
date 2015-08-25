// Makes the search window go on and off
function toggleSearch() {
	// Get the DOM reference
	var contentId = document.getElementById("search-window");
	// Toggle 
	contentId.style.display == "block" ? contentId.style.display = "none" : contentId.style.display = "block";
	$( '#search-window .search-field' ).focus();
}

function toggleCalendar() {
  // Get the DOM reference
  var contentId = document.getElementById("calendar-window");
  // Toggle 
  contentId.style.display == "block" ? contentId.style.display = "none" : 
contentId.style.display = "block"; 
$('#tribe-events-content .tribe-events-calendar td').css('height', '90px');
}


// Closes the window
function toggleClose() {
	$( ".window" ).css( "display", "none" );
}


function toggleChat() {
	  var contentId = document.getElementById("livechat-compact-container");
  // Toggle 
  contentId.style.display == "block" ? contentId.style.display = "none" : 
contentId.style.display = "block";
} 

//Changes the lightbox window to anchor 
$(window).load(function(){
	if ($(window).width() <= 736){
		$("#calendarhref").removeAttr("onclick").prop("href", "/events");
		}
});