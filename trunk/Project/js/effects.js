// JavaScript Document
$(document).ready(function() {
	
	/* Side Bar */
	$("#sidebar h3:first").addClass("active");
	$("#sidebar div:not(:first)").hide();

	$("#sidebar h3").click(function() {
		$(this).next("div").slideToggle("fast")
		.siblings("div:visible").slideUp("fast");
		$(this).toggleClass("active");
		$(this).siblings("h3").removeClass("active");
	});
	
	/* Login Form Slide Toggle */
	$(".btn-slide").click(function() {
		$("#panel").slideToggle("slow");
		$(this).toggleClass("active");
		return false;
	});
	
	/* Slide Bar Navigation */
	$(function() {
		$('#navigation a').stop().animate({'marginLeft':'-85px'}, 1000);

		$('#navigation > li').hover(
			function() {
				$('a',$(this)).stop().animate({'marginLeft':'-2px'}, 200);
			},
			function(){
				$('a',$(this)).stop().animate({'marginLeft':'-85px'}, 200);
			}
		);
	});
	
	/* Show Hide Slide Bar */
	$("#navigation .showmenu").click(function() {
		$("#left").toggle("slide", { direction: "left" }, 2);
		if ($("#map").css("width") == "672px") {
			$("#map").width(1022);
		} else {
			$("#map").width(672);
		}
		return false;
	});

});