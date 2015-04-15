/**
 * Manage setting and reading client data.
 *
 * Example code, can be used throughout the theme/plugins.
 */

// Proposed structure:
/*
localStorage
	.readPosts
		.id[postid] = boolean
	.postStatus
		.id[postid] = float latest scroll position as a percentage

Need to figure out how to set the postid dynmically in the variable names, may need to generate that JS from PHP.

*/

// Set
localStorage.readPosts.id = 1;

// Retreive
var scrollPosition = localStorage.readPosts.id[postid];
alert scrollPosition;