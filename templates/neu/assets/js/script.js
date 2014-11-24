/**
* Neo v1.0.0 by yokCreative
* Copyright 2013  
* 
*/



$(function () {
// Sidebar menu toggle
		//  Get height of all dds before hide() occurs.  Store height in heightArray, indexed based on the dd's position.
	
		$('.sidebar-nav li a').click(function (event) {
			 	 //  $(this).next()
				$(this).next().slideToggle(800);
				$('.sidebar-nav').find('a').not(this).next().slideUp(500);   
			});
			
			
// Stretch Columns to Full Height
		  $(".stretch-full-height").height($(document).height());
	  	  $(".sidebar-nav").height($("#page-content-wrapper").height());
	  window.onresize=function(){
		  $(".stretch-full-height").height($(document).height());
	  	  $(".sidebar-nav").height($("#page-content-wrapper").height());
	  }
// Rotate accordion icon
	  $('.accord-collapse').on('shown.bs.collapse', function(){
		$(this).parent().find(".icon-chevron-right").removeClass("icon-chevron-right").addClass("icon-chevron-down");
		  }).on('hidden.bs.collapse', 
		function(){
		$(this).parent().find(".icon-chevron-down").removeClass("icon-chevron-down").addClass("icon-chevron-right");
	  });
// Close panels
	 $('.close-panel').click(function() {
		 $(this).parent().parent().parent().slideToggle("slow");
	 
// List Strikethrough 		
	$('[type="checkbox"]').click(function(){
		var element = $(this).parent();
		if ($(this).is(':checked')) {
		   element.wrap('<del>');
		} else {
		   element.unwrap('<del>');
		}
	});

});
// Show first tab
  $(function () {
    $('.nav-tabs a:first').tab('show')
  })
  
  
// Show first pill
  $(function () {
    $('.nav-pills .active a').tab('show')
  })
  
});		


/* Initializing some of the plugins  */

// Easy Pie Charts
	var initPieChart = function() {
                $('.percentage').easyPieChart({
                    animate: 1000
                });
                $('.percentage-light').easyPieChart({
                    trackColor: '#a64077',
                    scaleColor: false,
                    lineCap: 'butt',
                    lineWidth: 15,
                    animate: 1000
                });

                $('.updateEasyPieChart').on('click', function(e) {
                  e.preventDefault();
                  $('.percentage, .percentage-light').each(function() {
                    var newValue = Math.round(100*Math.random());
                    $(this).data('easyPieChart').update(newValue);
                    $('span', this).text(newValue);
                  });
                });
            };




 