   $(document).ready(function() {	
    var markers = [
            {
                "title": 'Alibaug',
                "lat": '18.641400',
                "lng": '72.872200',
                "description": ''
            },
            {
                "title": 'Dombivli',
                "lat": '19.218400',
                "lng": '73.086700',
                "description": ''
            },
            {
                "title": 'Malegaon',
                "lat": '20.550500',
                "lng": '74.530900',
                "description": ''
            },

            {
                "title": 'Vashi',
                "lat": '18.750000',
                "lng": '73.033300',
                "description": ''
            },
			{
                "title": 'Ang Thong',
                "lat": '14.35',
                "lng": '100.31',
                "description": ''
            },
						{
                "title": 'Aranyaprathet',
                "lat": '13.41',
                "lng": '102.30',
                "description": ''
            },
			 {
                "title": 'Amnat Charoen',
                "lat": '15.51',
                "lng": '104.38',
                "description": ''
            },
			{
                "title": 'Chengdu',
                "lat": '33.10',
                "lng": '107.21',
                "description": ''
            },
			{
                "title": 'Ankara',
                "lat": '39.57',
                "lng": '32.54',
                "description": ''
            },
			{
                "title": 'Antakya',
                "lat": '36.14',
                "lng": '36.10',
                "description": ''
            },
			{
                "title": 'Antalya',
                "lat": '36.52',
                "lng": '30.45',
                "description": ''
            }

    ];
	// Map Style
	window.onload = function () {
 	 var styles = [
     {
		  featureType: "water",
		  elementType: "geometry",
		  stylers: [
			{ hue: "#afc4e1"},
			{ saturation: -10}
		  ]
		}
	  ];

	  // Create a new StyledMapType object, passing it the array of styles,
	  // as well as the name to be displayed on the map type control.
	  var styledMap = new google.maps.StyledMapType(styles,
		{name: "Styled Map"});

		var mapOptions = {
            center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
            zoom: 4,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
			panControl : false,
       	    zoomControl: false,
   		    zoomControlOpt: {
      			style: google.maps.ZoomControlStyle.SMALL
    		},
	   		streetViewControl : false,
        	mapTypeControl: false,
        	overviewMapControl: false,
			scaleControl: true

        };
        var infoWindow = new google.maps.InfoWindow();
        var map = new google.maps.Map(document.getElementById("map-fullscreen"), mapOptions);
        var i = 0;
        var interval = setInterval(function () {
            var data = markers[i]
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: data.title,
                animation: google.maps.Animation.DROP
            });
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    infoWindow.setContent(data.description);
                    infoWindow.open(map, marker);
                });
            })(marker, data);
            i++;
            if (i == markers.length) {
                clearInterval(interval);
            }
        }, 200);
		
		//Associate the styled map with the MapTypeId and set it to display.
		  map.mapTypes.set('map_style', styledMap);
		  map.setMapTypeId('map_style');

    
	  
	   }
	    $("#zoom-out").click(function() {
		 map.zoomOut(1);		  
	  	});
	  
	 	$("#zoom-in").click(function() {
		  map.zoomIn(1);	  
	  	});
	 });