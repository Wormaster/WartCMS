var isIPad = function() { return (/ipad/i).test(navigator.userAgent); };
var isIPhone = function() { return (/iphone/i).test(navigator.userAgent); };
var isIPod = function() { return (/ipod/i).test(navigator.userAgent); };
var eventName = (isIPod() || isIPad() || isIPhone()) ? "touchstart" : "click";


function feedback(){
    var parent = $('.modal');
    $('.submit_btn' , parent).on('click', function(e){
        var form = $(this).parents('form'),
        valid = form.get(0).checkValidity();
        console.log(valid);
        if (valid){
            e.preventDefault();
            var data = $(this).parents('form').serialize() + '&ajax=true', elem = $('a:first', parent);
            var xhr = $.ajax({
                url: '/feedback/message',
                type: 'POST',
                data: data,
                dataType: 'json'
            })
                .done(function(data){
                    //parent.fadeOut('700');
                    if (data == true){
                        console.log(data);
                        $('.fancybox-overlay').click();
                    }
                    else {

                    }


                })
                .fail(function(){
                    console.log('Ошибочка вышла...');
                });
            //console.log(data);
        }


    })
}





$(window).load(function(){ 
	$('.slider').mobilyslider({
		transition: 'fade',
		animationSpeed: 500,
		autoplay: true,
		autoplaySpeed: 4000,
		bullets: true,
		arrowsHide: true
	});
	
	$('.product_info .images .small a').on(eventName, function(e){
		e.preventDefault();
		var img = $(this).attr('href');
		$('.product_info .images .big a').attr('href', img);
		$('.product_info .images .big img').attr('src', img);
	});

	
	$('.fancy').fancybox({ fitToView: false, autoResize: false, autoSize: true, padding: 0, closeBtn: false });

    feedback();
});


