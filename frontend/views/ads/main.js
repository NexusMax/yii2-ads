$(document).ready(function(){

	$(".owl-carousel.three").owlCarousel({
		loop: true,
		dots: false,
        // center: true,
        margin: 10,
        nav: false,
        items: 5,
        responsive:{
        	0:{
        		items:1
        	},
        	600:{
        		items:3
        	},
        	1000:{
        		items:5
        	}
        },
        navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
        navElement: 'span',
        // autoplay: true
    });

	$(".categories .item").on('click', function (event) {
		$_item_id = ($(this).attr("id")).substr(11, ($(this).attr("id")).lenght);

		if ( $(this).find("a").hasClass("selected") ) {
			$(this).find("a").removeClass("selected");
			$("#subcategories"+$_item_id).hide();
		} else {
			$(".categories .item a").removeClass("selected");
			$(this).find("a").toggleClass("selected");
			$(".subcategories").hide();
			$("#subcategories"+$_item_id).show();
			$("#subcategories"+$_item_id).find(".sub-p:before").attr("left", (120*$_item_id)+"px");                                 
		}
		return false;
	});

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$("#"+input.id).parent().parent().find("img").attr('src', e.target.result).parent().append('<i class="fa fa-times" aria-hidden="true"></i>');
				$("#"+input.id).parent().parent().find("img").addClass("selected");
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	$(".files input[type=file]").change(function(){
		readURL(this);
	});

	$(document).on('click', '.file-i i', function(e){
		e.preventDefault();
		if($(this).attr('data-ajax-delete') !== undefined){
			$.ajax({
				type: 'POST',
				url: '/ads/delete-image/',
				data: {
					'image_id': $(this).attr('data-ajax-delete'),
					'ads_id': $(this).attr('data-id')
				},
				success: function(data){
					console.log(data);
				}
			});
		}
		$(this).parent().parent().find('.form-group input[type=file]').val("");
		$(this).parent().find("img").attr('src', '/images/add-min.png').removeClass('selected');
		$(this).remove();
	});
	$('.nav-messages a').click(function (e) {
		e.preventDefault()
		$('.nav-messages a').removeClass('active');
		$(this).tab('show');
	});

	$("#login_tab").click(function() {
			$(this).addClass("active");
			$("#register_tab").removeClass("active");
			$('.login-tabs__content li[data-content=register]').hide();
			$('.login-tabs__content li[data-content=login]').show();

			return false;
		});
		$("#register_tab").click(function() {
			$(this).addClass("active");
			$("#login_tab").removeClass("active");
			$('.login-tabs__content li[data-content=login]').hide();
			$('.login-tabs__content li[data-content=register]').show();

			return false;
		});
		

	$('.subcategories-ajax').change(function(e){
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: '/ads/subcategory/',
			data: {
				'category_id': $(this).val()
			},
			success: function(data){
				$('.dropdownSubCat option').remove();
				$('.dropdownSubCat').append(data);
				console.log(data);
			},
			error: function(){
				$('.dropdownSubCat option').remove();
			}
		});

		$.ajax({
			type: 'POST',
			url: '/ads/subfields/',
			data: {
				'category_id': $(this).val()
			},
			success: function(data){
				$('.inputs_js').remove();
				$('.block-after').after(data);
			}
		});
	});

	$('.dropdownSubCat').change(function(e){
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: '/ads/subfields/',
			data: {
				'category_id': $(this).val(),
				'ads_id' : $(this).attr('data-ajax-id')
			},
			success: function(data){
				$('.inputs_js').remove();
				$('.block-after').after(data);
			}
		});
	});


	$('#owl-carousel-ad').owlCarousel({
	    loop: true,
		dots: false,
        margin: 10,
        nav: true,
        items: 4,
        responsive:{
        	0:{
        		items:1
        	},
        	600:{
        		items:3
        	},
        	1000:{
        		items:4
        	}
        },
        navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
        navElement: 'span',
	})


	$().fancybox({
	  selector : '[data-fancybox="images"]',
	  loop     : true
	});


	$('.mini_img').click(function(e){
		e.preventDefault();

		$mini_img = $(this).attr('href');
		$big_img = $('.big_img').attr('href');

		$mini_src = $('.big_img').attr('href').split('/');

		$(this).attr('href', $big_img);
		$(this).find('img').attr('src', $mini_src[0] + '/' + $mini_src[1] + '/' + $mini_src[2] + '/' + $mini_src[3] + '/' + $mini_src[4] + '/' + $mini_src[5] + '/mini_' + $mini_src[6]);
		$('.big_img').attr('href', $mini_img);
		$('.big_img img').attr('src', $mini_img)

	});



	$('a[role="tab"]').click(function (e) {
	  e.preventDefault();
	  $('a[role="tab"]').each(function(){
	  	$(this).removeClass('active');
	  });
	
	});

	$('a.favorite').click(function(e){
		e.preventDefault();

		var ads_id = $(this).attr('data-id');
		$.ajax({
			type: 'POST',
			url: '/ads/favorite/',
			data: {
				'ads_id' : ads_id
			},
			success: function(data){
				console.log(data);
			}
		});

		$(this).removeClass('favorite');
		$(this).addClass('favorite-out');
		$(this).attr('data-icon', "star-filled");
		$(this).find('i').remove();

	});

	$('.like').on('click', 'a.favorite-out', function(e){
		e.preventDefault();

		var ads_id = $(this).attr('data-id');
		$.ajax({
			type: 'POST',
			url: '/ads/favoritedelete/',
			data: {
				'ads_id' : ads_id
			},
			success: function(data){
			}
		});

		$(this).addClass('favorite');
		$(this).removeAttr('data-icon');
		$(this).removeClass('favorite-out');
		$(this).append('<i class="fa fa-star-o" aria-hidden="true">')

	});

	$('.sms-call').click(function(e){
		e.preventDefault();

		var modal = $('#myModal'),
			form = modal.find('form');

		console.log($('#myModal form textarea').length);
		if(form.find('textarea').length === 0){
			form.find('p').remove();
			form.append('<textarea name="ad_message" clas cols="30" rows="10" placeholder="Введите Ваше сообщение"></textarea>');
			modal.find('.modal-footer a').remove();
			modal.find('.modal-footer').prepend('<button type="submit" class="btn btn-success otp">Отправить</button>');
		}
	});

	$('#myModal button[type="submit"]').click(function(e){
		e.preventDefault();

		var message = $('#myModal form textarea').val(),
			ads_id = $('#myModal form input[name="ads_id"]').val();

		

			console.log(ads_id);

		$.ajax({
			type: 'POST',
			url: '/ads/message/',
			data: {
				'ads_id' : ads_id,
				'message' : message
			},
			beforeSend: function(){
	            $('#myModal form').html('<img id="imgcode" src="/images/ajax-loader.gif"><input type="hidden" name="ads_id" value="'+ ads_id +'">');
	        },
			success: function(data){
				var modal = $('#myModal');
				modal.find('form #imgcode').remove();
				modal.find('form').append('<p>Сообщение успешно отправлено</p>');
				modal.find('.modal-footer .otp').remove();
				modal.find('.modal-footer').prepend('<a class="btn btn-success" href="/myaccount/messages">Перейти к переписке</a>');
				console.log(data);
			},
			error: function(){
				var form = $('#myModal form');
				form.find('#imgcode').remove();
				form.append('При отправке сообщения произошла ошибка. Попробуйте позже!');
			}
		});


	});


});