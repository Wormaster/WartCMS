/**
* Neo v1.0.0 by yokCreative
* Copyright 2013  
* mock up map for index.html
*/


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
								strokeColor: '1d1e24',
								strokeOpacity: 1,
								fillColor: '#1d1e24',
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
								strokeColor: '1d1e24',
								strokeOpacity: 1,
								fillColor: '#1d1e24',
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
								strokeColor: '1d1e24',
								strokeOpacity: 1,
								fillColor: '#1d1e24',
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
								strokeColor: '1d1e24',
								strokeOpacity: 1,
								fillColor: '#1d1e24',
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
								strokeColor: '1d1e24',
								strokeOpacity: 1,
								fillColor: '#1d1e24',
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
								strokeColor: '1d1e24',
								strokeOpacity: 1,
								fillColor: '#1d1e24',
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


 