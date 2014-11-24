/**
* Neo v1.0.0 by yokCreative
* Copyright 2013  
* 
*/


// Add Tabs
$('#btnAdd').click(function (e) {
  	var nextTab = $('#tabs li').size()+1;
	
  	// create the tab
  	$('<li><a href="#tab'+nextTab+'" data-toggle="tab">Tab '+nextTab+'</a></li>').appendTo('#tabs');
  	
  	// create the tab content
  	$('<div class="tab-pane" id="tab'+nextTab+'">tab' +nextTab+' content</div>').appendTo('.tab-content');
  	
  	// make the new tab active
  	$('#tabs a:last').tab('show');
});


// Stretch Columns to Full Height
	$(" .sidebar-nav , .stretch-full-height").height($(document).height());


// Close panels
	$('.close-panel').click(function() {
				$(this).parent().parent().parent().slideToggle("fast");
		});
	 

// Rotate Collapse Icon
	$('.accord-collapse').on('shown.bs.collapse', function(){
		$(this).parent().find(".icon-chevron-right").removeClass("icon-chevron-right", 1000, "easeInBack").addClass("icon-chevron-down", 1000, "easeInBack" );
		  }).on('hidden.bs.collapse', 
		function(){
		$(this).parent().find(".icon-chevron-down").removeClass("icon-chevron-down").addClass("icon-chevron-right");
	});


// List Strikethrough 		
	$('[type="checkbox"]').click(function(){
		var element = $(this).parent();
		if ($(this).is(':checked')) {
		   element.wrap('<del>');
		} else {
		   element.unwrap('<del>');
		}
	});

			
// Show first tab
  $(function () {
    $('.nav-tabs a:last-child').tab('show')
  })
  
// Show first pill
  $(function () {
    $('.nav-pills .active a').tab('show')
  })
  
// Sidebar Menu Toggle
  $(function () {
		$('.inactive').hide();
		$('.submenu-title').click(function (event) {
			event.preventDefault();
		  //  $(this).next()
		$(this).next().slideToggle('400');
		$('#sidebar').find('a').not(this).next().slideUp('400');   
		});
	})


/* Initializing some of the plugins  */

// EZ Mark	
	$(function() {
			$('.custom-check input').ezMark()
		}); 	
	

// Counters
	$({countNum: $('#new-users').text()}).delay(300).animate({countNum: 1184}, {
		  duration: 3000,
		  easing:'linear',
		  step: function() {
			$('#new-users').text(Math.floor(this.countNum));
		  },
	
	});
	$({countNum: $('#new-comments').text()}).delay(300).animate({countNum:581}, {
		  duration: 3500,
		  easing:'linear',
		  step: function() {
			$('#new-comments').text(Math.floor(this.countNum));
		  },
	
	});
	$({countNum: $('#new-subscribers').text()}).delay(300).animate({countNum:392}, {
		  duration:4000,
		  easing:'linear',
		  step: function() {
			$('#new-subscribers').text(Math.floor(this.countNum));
		  },
	
	});
	$({countNum: $('#new-orders').text()}).delay(300).animate({countNum:107}, {
		  duration:4500,
		  easing:'linear',
		  step: function() {
			$('#new-orders').text(Math.floor(this.countNum));
		  },
	
	});

// Google Maps
	$(window).load(function () {
		initGoogleMap(); //init Gmap3
	});

	function initGoogleMap() {
		
		$('#my-map').gmap3({
			map: {
				options: {
					center:[43.2964, 5.3700],
					zoom: 2, //adjust this depending upon how much you want to see
					styles: [{
						stylers: [{
								hue: '#8a8995'
							}, //this is the accent color
							{
								saturation:-90
							},
							{
								lightness: 20
							}
						]
					}]
				}
			},
			marker:{
				values: [
				  {
					latLng:[40.4169, 3.7036],
					options:{
					  icon: {
								path: fontawesome.markers.MAP_MARKER,
								scale: 0.7,
								strokeWeight: 0.2,
								strokeColor: '00b49d',
								strokeOpacity: 1,
								fillColor: '#00b49d',
								fillOpacity: 1
							},
					}
				  },
				  {
					latLng:[60.0000, 90.0000],
					options:{
					  icon: {
								path: fontawesome.markers.MAP_MARKER,
								scale: 0.7,
								strokeWeight: 0.2,
								strokeColor: '00b49d',
								strokeOpacity: 1,
								fillColor: '#00b49d',
								fillOpacity: 1
							},
					}
				  },
				  {
					latLng:[53.5500, 2.4333],
					options:{
					  icon: {
								path: fontawesome.markers.MAP_MARKER,
								scale: 0.7,
								strokeWeight: 0.2,
								strokeColor: '00b49d',
								strokeOpacity: 1,
								fillColor: '#00b49d',
								fillOpacity: 1
							},
					}
				  },
				  {
					latLng:[5.8783, -66.2036],
					options:{
					  icon: {
								path: fontawesome.markers.MAP_MARKER,
								scale: 0.7,
								strokeWeight: 0.2,
								strokeColor: '00b49d',
								strokeOpacity: 1,
								fillColor: '#00b49d',
								fillOpacity: 1
							},
					},
				  },
				  {
					latLng:[40.0000, -100.0000],
					options:{
					  icon: {
								path: fontawesome.markers.MAP_MARKER,
								scale: 0.7,
								strokeWeight: 0.2,
								strokeColor: '00b49d',
								strokeOpacity: 1,
								fillColor: '#00b49d',
								fillOpacity: 1
							},
					},
				  },
				  {
					latLng:[35.0000, 93.0000],
					options:{
					  icon: {
								path: fontawesome.markers.MAP_MARKER,
								scale: 0.7,
								strokeWeight: 0.2,
								strokeColor: '00b49d',
								strokeOpacity: 1,
								fillColor: '#00b49d',
								fillOpacity: 1
							},
					},
				  }
			]},
		options: {
					draggable: true,
				}
			
		});
		

		
	
	}


 