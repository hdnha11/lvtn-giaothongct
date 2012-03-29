// JavaScript Document
$(document).ready(function() {
	
	/* Side Bar */
	$("#sidebar h3").click(function() {
		$(this).next("div").slideToggle("fast")
		.siblings("div:visible").slideUp("fast");
		$(this).toggleClass("active");
		$(this).siblings("h3").removeClass("active");
	});
	
});