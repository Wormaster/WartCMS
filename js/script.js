// JavaScript Document
/*resize materials*/

/*resize materials end*/
function mainleftmove() {}
function mainrightmove() {}
function zamerhsik() {
	$(document).on('click', '#zambutton', function(event) {
		event.preventDefault() 
		var elem = $(this)
		$('.zbutton').animate({'top': '+=253'}, 1000);
		$('#zamershik').animate({'height': 273}, 1000, function() {
			elem.attr('id','zambutton-close').html('закрыть');
			});
	});
	$(document).on('click', '#zambutton-close', function(event) {
		event.preventDefault() 
		var elem = $(this)
		$('.zbutton').animate({'top': '-=253'}, 1000);
		$('#zamershik').animate({'height': 20}, 1000, function() {
			elem.attr('id','zambutton').html('вызвать замерщика');
			});
		});
	}
function oldanimation(){
	$('.mleft').mouseover(function(){
		$(this).stop().animate({backgroundPosition: '0 0'}, 500);
	})
	$('.mleft').mouseout(function(){
		$(this).stop().animate({backgroundPositionX: '120' }, 500);
	})
	$('.mright').mouseover(function(){
		$(this).stop().animate({backgroundPositionX: '120' }, 500);
	})
	$('.mright').mouseout(function(){
		$(this).stop().animate({backgroundPosition: '0 0'}, 500);
	})
}


/*resize materials*/

/* resize materials end*/

$(window).load(function() {
	conditionizr()
	if(!Modernizr.csstransitions) { 
	$.getScript('js/jquery.backgroundpos.min.js')
	.done( function(){
		console.log ('done')
		})
	.fail(function(jqxhr, settings, exception) {
	console.log ('hui')
});
     oldanimation()
    }

	});