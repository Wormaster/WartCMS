/**
*
* jquery.sparkline.js
*
* v2.1.2
* (c) Splunk, Inc
* Contact: Gareth Watts (gareth@splunk.com)
* http://omnipotent.net/jquery.sparkline/
*
* Generates inline sparkline charts from data supplied either to the method
* or inline in HTML
*
* Compatible with Internet Explorer 6.0+ and modern browsers equipped with the canvas tag
* (Firefox 2.0+, Safari, Opera, etc)
*
* License: New BSD License
*
* Copyright (c) 2012, Splunk Inc.
* All rights reserved. 
* 
*/


// Sparkline Mini Charts 

// bar chart 1
var values = [15, 12, 28, 5, 32, 5, 3, 14, 22, 5, 12];

// Draw a sparkline for the #sparkline element
$('.sparkline').sparkline(values, {
    type: "bar",
	barColor: '#ec9a5d',
    tooltipSuffix: " widgets"
});
// bar chart 2
var values = [20, 5, 35, 10, 5, 15, 7, 3, 20, 10];

// Draw a sparkline for the #sparkline element
$('.sparkline2').sparkline(values, {
    type: "bar",
	barColor: '#ec9a5d',
    tooltipSuffix: " widgets"
});

// bar chart 3
var values = [6, 5, 12, 10, 5, 6, 7, 3, 4, 10, 5, 4, 10, 5, 4, 7, 3, 7, 10];

// Draw a sparkline for the #sparkline element
$('.sparkline3').sparkline(values, {
    type: "bar",
	barColor: '#ffffff',
    tooltipSuffix: "users"
});

// Samples from charts.html
$("#line-ex").sparkline([5,6,7,9,9,5,3,2,2,4,6,7,2,4,6,2,4], {
    type: 'line',
    lineColor: '#ff7f00',
    fillColor: '#007f7f',
    spotColor: '#ff7f00',
    minSpotColor: '#ff7f00',
    maxSpotColor: '#ff7f00',
    spotRadius: 2});

$("#bar-ex").sparkline([15,6,7,2,0,22,12,25,6,7,9,9,5,3,2,2,], {
    type: 'bar',
    barColor: '#ff7f00',
    negBarColor: '#ff7f00'});

$("#bar-ex2").sparkline([15,6,7,2,0,22,12,25,6,7,9,9,5,3,2,2,15,6,7,2,0,22 ], {
    type: 'bar',
	barWidth: 15,
	width: '',
    height: '200',
    barColor: '#ec9a5d',
    negBarColor: '#ff7f00'});
	
$("#bullet-ex").sparkline([10,12,12,9,7], {
    type: 'bullet',
    targetColor: '#ff7f00',
    performanceColor: '#007f7f',
    rangeColors: ['#7eeae4','#4ccece','#2cb7b7']});

$('#compositebar-ex').sparkline('html', { type: 'bar', barColor: '#2cb7b7' });
    $('#compositebar-ex').sparkline([4,1,5,7,9,9,8,7,6,6,4,7,8,4,3], 
        { composite: true, fillColor: false, lineColor: '#ff7f00' });	
	
		
$("#pie-ex1").sparkline([2,3,1], {
    type: 'pie',
    sliceColors: ['#00b49d','#687081','#ff9900','#109618','#66aa00','#dd4477','#0099c6','#990099 ']});
$("#pie-ex2").sparkline([1,1,2], {
    type: 'pie',
    sliceColors: ['#00b49d','#687081','#ff9900','#109618','#66aa00','#dd4477','#0099c6','#990099 ']});
$("#pie-ex3").sparkline([1,1,1], {
    type: 'pie',
    sliceColors: ['#00b49d','#687081','#ff9900','#109618','#66aa00','#dd4477','#0099c6','#990099 ']});
$("#pie-ex4").sparkline([1,2,3], {
    type: 'pie',
    sliceColors: ['#00b49d','#687081','#ff9900','#109618','#66aa00','#dd4477','#0099c6','#990099 ']});
$("#pie-ex5").sparkline([2,3,1], {
    type: 'pie',
    sliceColors: ['#00b49d','#687081','#ff9900','#109618','#66aa00','#dd4477','#0099c6','#990099 ']});
$("#pie-ex6").sparkline([2,3,1], {
    type: 'pie',
	width: '200',
    height: '200',
    sliceColors: ['#00b49d','#687081','#ff9900','#109618','#66aa00','#dd4477','#0099c6','#990099 ']});	
	
$('#discrete-ex').sparkline([4,6,7,7,4,3,2,1,4,4,4,6,7,7,4,3,2,1,4,4,4,6,7,7,4,3,2,1,4,4], {
    type: 'discrete',
    lineColor: '#ff7f00'});	
		
// ************ Morris Line Chart index.html **********//
 new Morris.Line({
            // ID of the element in which to draw the chart.
            element: 'weeklysales',
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.
            data: [
            { year: '2013-08', value: 50 },
            { year: '2013-09', value: 162 },
            { year: '2013-10', value:112 },
            { year: '2013-11', value:220 },
            ],
            // The name of the data record attribute that contains x-values.
            xkey: 'year',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['value'],
            // Labels for the ykeys -- will be disaddinged when you hover over the
            // chart.
            labels: ['Value'],
                  lineColors: ['#ffffff'],
                  pointFillColors: ['#ffffff']
				  
 });
 

			
// ************ Easy Pie Charts index.html **********//
 			var initPieChart = function() {
                $('.percentage').easyPieChart({
                    animate: 1000
                });
                $('.percentage-light').easyPieChart({
                    trackColor: '#caced7',
					barColor:'#a3346f',
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