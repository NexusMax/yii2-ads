$(document).ready(function(){

	$(".owl-carousel.three:not(.owl-car):not(.owl-car-car)").owlCarousel({
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
        	480:{
        		items:2
        	},
			769:{
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
    

    $(".owl-carousel.three.owl-car:not(.owl-car-car)").owlCarousel({
		loop: false,
		dots: false,
        // center: true,
        margin: 10,
        nav: false,
        items: 5,
        responsive:{
        	0:{
        		items:1
        	},
        	480:{
        		items:2
        	},
			769:{
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


    $(".owl-carousel.three.owl-car-car").owlCarousel({
		loop: true,
		dots: false,
        center: true,
        margin: 10,
        nav: false,
        items: 1,

        navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
        navElement: 'span',
        // autoplay: true
    });

	$(".categories .item").on('click', function (event) {
        if (window.matchMedia("(max-width: 768px)").matches){

		}
		else {
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
		}
	});

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {

				if(!$("#"+input.id).parent().hasClass('has-success')){
					$("#"+input.id).parent().addClass('has-error');
					var _text = $("#"+input.id).data('error');
					 $("#"+input.id).parent().find('.help-block').text(_text);
				}
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
		var _this = $(this);
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
		$(this).parent().parent().find('.form-group input[type="hidden"]').val("");
		$(this).parent().parent().find('.form-group input[type="file"]').val("");

		$(this).parent().find("img").attr('src', '/images/add-min.png').removeClass('selected');

		$(this).parent().parent().find('.form-group').removeClass('has-error');
		if(!$(this).parent().parent().find('.form-group').hasClass('has-error'))
			$(this).parent().parent().find('.help-inf').remove();
		$(this).parent().parent().find('.help-block').attr('style', '').text('').removeClass('popup-before');

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

		var max_cat_ads = $('#cat_'+ $(this).val()).val();
		$('.count_ads').remove();
		if(max_cat_ads != undefined){
			if($(this).val() != 15 && $(this).val() != 17 && $(this).val() != 28){
				if(max_cat_ads >= 8)
					$(this).parent().append('<p class="count_ads">Лимит бесплатных обьявлений в этой категории исчерпан.<br> Это обьявление платное</p>');
				else
					$(this).parent().append('<p class="count_ads">У Вас ' + max_cat_ads + '/8 бесплатных обьявлений в этой категории</p>');
			}else{
				if(max_cat_ads >= 5)
					$(this).parent().append('<p class="count_ads">Лимит бесплатных обьявлений в этой категории исчерпан.<br> Это обьявление платное</p>');
				else
					$(this).parent().append('<p class="count_ads">У Вас ' + max_cat_ads + '/5 бесплатных обьявлений в этой категории</p>');
			}

		}

		$.ajax({
			type: 'POST',
			url: '/ads/subcategory/',
			data: {
				'category_id': $(this).val()
			},
			success: function(data){
				$('.dropdownSubCat option').remove();
				$('.dropdownSubCat').append(data);

				var subcat_ = $('.dropdownSubCat').val();
				$.ajax({
					type: 'POST',
					url: '/ads/subfields/',
					data: {
						'category_id': subcat_
					},
					success: function(data){
						$('.inputs_js').remove();
						$('.block-after').after(data);
						if(data == ''){
							if($('.subcategories-ajax').parent().hasClass('has-error')){
								if($('.dropdownSubCat').parent().find('.help-inf-war').length == 0){
									$('.dropdownSubCat').parent().find('.help-inf').remove();
									$('.dropdownSubCat').parent().append('<p class="help-inf help-inf-war">'+getSvgWAR()+'</p>');
									$('.dropdownSubCat').parent().addClass('has-error');
									$('.dropdownSubCat').parent().removeClass('has-success');
									$('.dropdownSubCat').parent().find('.help-block').text('Необходимо заполнить «Подкатегория».');
									// console.log($('.dropdownSubCat').find('.help-block'));
								}
							}
							$('.categories-three').css({'display': 'none'});
						}else{
							if($('.subcategories-ajax').parent().hasClass('has-success')){
								if($('.dropdownSubCat').parent().find('.help-inf-ok').length == 0){
									$('.dropdownSubCat').parent().find('.help-inf').remove();
									$('.dropdownSubCat').parent().append('<p class="help-inf help-inf-ok">'+getSvgOK()+'</p>');
									$('.dropdownSubCat').parent().addClass('has-success');
									$('.dropdownSubCat').parent().removeClass('has-error');
									$('.dropdownSubCat').parent().find('.help-block').html('');
								}
							}
						}
					}
				});

				$.ajax({
					type: 'POST',
					url: '/ads/subcategory/',
					data: {
						'category_id': subcat_,
					},
					success: function(data2){

						data2 = data2.replace('<option value="">Выберите категорию</option>', '');
						$('.dropdownSubSubCat option').remove();
						$('.dropdownSubSubCat').append(data2);

						var subcat_ = $('.dropdownSubSubCat').val();

						if(data2 == ''){
							$('.categories-three').css({'display':'none'});
							if($('.subcategories-ajax').parent().hasClass('has-error')){
									if($('.dropdownSubSubCat').parent().find('.help-inf-war').length == 0){
										$('.dropdownSubSubCat').parent().find('.help-inf').remove();
										$('.dropdownSubSubCat').parent().append('<p class="help-inf help-inf-war">'+getSvgWAR()+'</p>');
										$('.dropdownSubSubCat').parent().addClass('has-error');
										$('.dropdownSubSubCat').parent().removeClass('has-success');
										$('.dropdownSubSubCat').parent().find('.help-block').text('Необходимо заполнить «Подкатегория».');
										// console.log($('.dropdownSubCat').find('.help-block'));
									}
								}
						}else{

							$.ajax({
								type: 'POST',
								url: '/ads/subfields/',
								data: {
									'category_id': subcat_,
								
								},
								success: function(data){
									$('.inputs_js').remove();
									$('.block-after').after(data);
								}
							});
							$('.categories-three').css({'display':'block'});

								if($('.subcategories-ajax').parent().hasClass('has-success')){
									if($('.dropdownSubSubCat').parent().find('.help-inf-ok').length == 0){
										$('.dropdownSubSubCat').parent().find('.help-inf').remove();
										$('.dropdownSubSubCat').parent().append('<p class="help-inf help-inf-ok">'+getSvgOK()+'</p>');
										$('.dropdownSubSubCat').parent().addClass('has-success');
										$('.dropdownSubSubCat').parent().removeClass('has-error');
										$('.dropdownSubSubCat').parent().find('.help-block').html('');
									}
								}
						}

					}
				});

			},
			error: function(){
				$('.dropdownSubCat option').remove();
				$('.dropdownSubSubCat option').remove();
				if($('.subcategories-ajax').parent().hasClass('has-error')){
					if($('.dropdownSubCat').parent().find('.help-inf-war').length == 0){
						$('.dropdownSubCat').parent().find('.help-inf').remove();
						$('.dropdownSubCat').parent().append('<p class="help-inf help-inf-war">'+getSvgWAR()+'</p>');
						$('.dropdownSubCat').parent().addClass('has-error');
					}
				}
			}
		});

	});


	$('.dropdownSubCat').change(function(e){
		e.preventDefault();
		var cat_id = $(this).val();
		var ads_id = $(this).attr('data-ajax-id');

		$.ajax({
			type: 'POST',
			url: '/ads/subfields/',
			data: {
				'category_id': cat_id,
				'ads_id' : ads_id
			},
			success: function(data){
				$('.inputs_js').remove();
				$('.block-after').after(data);
			}
		});


		$.ajax({
			type: 'POST',
			url: '/ads/subcategory/',
			data: {
				'category_id': cat_id,
			},
			success: function(data2){

				data2 = data2.replace('<option value="">Выберите категорию</option>', '');
				$('.dropdownSubSubCat option').remove();
				$('.dropdownSubSubCat').append(data2);

				var subcat_ = $('.dropdownSubSubCat').val();

				if(data2 == ''){
					$('.categories-three').css({'display':'none'});
				}else{

					$.ajax({
						type: 'POST',
						url: '/ads/subfields/',
						data: {
							'category_id': subcat_,
							'ads_id' : ads_id
						},
						success: function(data){
							$('.inputs_js').remove();
							$('.block-after').after(data);
						}
					});
					$('.categories-three').css({'display':'block'});
				}

			}
		});
	});

	$('.dropdownSubSubCat').change(function(e){
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
		loop: false,
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



	$(document).on('click','a[role="tab"]', function (e) {
		e.preventDefault();
		$(this).each(function(){
			$(this).removeClass('active');
		});

	});

	$('.like').on('click', 'a.favorite', function(e){
		e.preventDefault();

		var guest = $(this).data('guest');
		var ads_id = $(this).attr('data-id');
		if(!guest){
			$.ajax({
				type: 'POST',
				url: '/ads/favorite/',
				data: {'ads_id' : ads_id}
			});

			$(this).parent().find('.favorite-text').html('Удалить из<br> избранного');
			$(this).removeClass('favorite');
			$(this).addClass('favorite-out');
			$(this).attr('data-icon', "star-filled");
			$(this).find('i').remove();
		}else{
			$(this).parent().find('.favorite-text').html('<a href="/login">Авторизируйтесь</a>, чтобы добавить в избранные');
		}
		

	});

	$('.like').on('click', 'a.favorite-out', function(e){
		e.preventDefault();

		var ads_id = $(this).attr('data-id');
		$.ajax({
			type: 'POST',
			url: '/ads/favoritedelete/',
			data: {'ads_id' : ads_id}
		});

		$(this).parent().find('.favorite-text').text('В избранное');
		$(this).addClass('favorite');
		$(this).removeAttr('data-icon');
		$(this).removeClass('favorite-out');
		$(this).append('<i class="fa fa-star-o" aria-hidden="true">')

	});

	$('.sms-call').click(function(e){
		e.preventDefault();

		$('p.p-message').remove();

		var modal = $('#myModal'),
		form = modal.find('form');

		if(form.find('textarea').length === 0){
			form.find('p').remove();
			form.append('<textarea name="ad_message" cols="30" rows="10" placeholder="Введите Ваше сообщение"></textarea>');
			modal.find('.modal-footer .otp').remove();
			modal.find('.modal-footer').prepend('<button type="submit" class="btn btn-success otp">Отправить</button>');
		}
	});


	$('#myModal').on('keyup', 'form textarea', function(e){
		if($(this).val().length < 6 ){
			$('p.p-message').remove();
			$('#myModal form').append('<p class="p-message">Введите более 6 символов</p>');
		}else{
			if($(this).val().length >= 6){
				$('p.p-message').remove();
			}
		}
	});

	$('#myModal').on('click', 'button[type="submit"]', function(e){
		e.preventDefault();

		var message = $('#myModal form textarea').val(),
		ads_id = $('#myModal form input[name="ads_id"]').val(),
		alias = $('#myModal form input[name="ads_alias"]').val(),
		magazine_id = $('#myModal form input[name="magazine_id"]').val();

		if(message.length < 6){
			if($('p.p-message').length === 0)
				$('#myModal form').append('<p class="p-message">Введите сообщение</p>');
			return false;
		}

		$.ajax({
			type: 'POST',
			url: '/ads/message/',
			data: {
				'ads_id' : ads_id,
				'message' : message,
				'magazine_id' : magazine_id
			},
			beforeSend: function(){
				$('#myModal form').html('<img id="imgcode" src="/images/ajax-loader.gif"><input type="hidden" name="ads_id" value="'+ ads_id +'"><input type="hidden" name="ads_alias" value="'+ alias +'">');
			},
			success: function(data){
				var modal = $('#myModal');
				modal.find('form #imgcode').remove();
				modal.find('form').append('<p>Сообщение успешно отправлено</p>');
				modal.find('.modal-footer .otp').remove();
				modal.find('.modal-footer').prepend('<a class="btn btn-success otp" href="/myaccount/messages/'+data+'">Перейти к переписке</a>');
			},
			error: function(){
				var form = $('#myModal form');
				form.find('#imgcode').remove();
				form.append('<p>При отправке сообщения произошла ошибка. Попробуйте позже!</p>');
			}
		});


	});
	var kalina = 1;

	$('.counter-phone').click(function(){

		var ads_id = $(this).attr('data-ads-id');
		var first_click = 1; // $(this).attr('data-view')


		if(kalina == 1){
			kalina = 2;
			$(this).attr('data-view', "2");

			// if(!$.cookie(ads_id)){
				$.cookie(ads_id, 'phone_view');
				$.ajax({
					type: 'POST',
					url: '/ads/countphone/',
					data: {
						'ads_id' : ads_id,
					},
					success: function(data){
						console.log(data);
					}
				});
			// }
		}else{
			kalina = 1;
			$(this).attr('data-view', "1");
		}

	});



	$('.location-city').keyup(function(){

		if($(this).val() == ''){
			$('#select-city').css({'display': 'none'}).find('option').remove();
			$('input[id="ads-city_id"]').val('');
			$('input[id="ads-reg_id"]').val('');
		}
		if($(this).val().length > 2){
			var city = $(this).val();

			$.ajax({
				type: 'POST',
				url: '/ads/selectcity/',
				data: {
					'city' : city,
				},
				success: function(data){
					data = $.parseJSON(data);

					$('#select-city option').remove();
					var option = '';
					for(var i = 0; i < data.length; i++){
						option += '<option value="'+data[i]['city']+', '+data[i]['region']+'" data-city-id="'+data[i]['city_id']+'" data-reg-id="'+data[i]['reg_id']+'">'+data[i]['city']+', '+data[i]['region']+'</option>';
					}

					$('#select-city').css({'display': 'block'}).append(option);

					if(data.length == 0){
						$('#select-city').css({'display': 'none'});
						$('input[id="ads-city_id"]').val('');
						$('input[id="ads-reg_id"]').val('');
					}
				}
			});
		}

	});
	$('.location-city').focusout(function(){
		var select = $('#select-city');

		if($('#select-city option' + ':hover').length) {
			return;
		}

		if(select.css('display') == 'block'){
			$('input[id="ads-city_id"]').val('');
			$('input[id="ads-reg_id"]').val('');
			select.css({'display': 'none'});

		}
	});

	$('#select-city').on('click', 'option', function(){
		$('.location-city').val($(this).val());
		$('input[id="ads-city_id"]').val($(this).attr('data-city-id'));
		$('input[id="ads-reg_id"]').val($(this).attr('data-reg-id'));
		$('#select-city').css({'display': 'none'}).find('option').remove();

	});


	$('#add-title').keyup(function(){
		var k = $(this).val().length;
		$('#add-title-counter').text(70 - k);
	});
	


	if($('#search-form').length > 0){
		if($(document).find('.field-search-city input').val().length > 0){
			if($(document).find('.field-search-city .search-close').length == 0)
				$(document).find('.field-search-city').append('<a class="search-close" href=""><i class="fa fa-times" aria-hidden="true"></i></a>');
		}

		if($(document).find('.field-search-q input').val().length > 0){
			if($(document).find('.field-search-q .search-close-q').length == 0)
				$(document).find('.field-search-q').append('<a class="search-close-q" href=""><i class="fa fa-times" aria-hidden="true"></i></a>');
		}

		$(document).on('click', '.field-search-city input', function(){
			$('.regions-layer').css({'display': 'block'});
		});

		$(document).on('change', '.field-search-city input', function(){
			if($(this).val() != ''){
				if($('.search-close').length == 0)
					$('.field-search-city').append('<a class="search-close" href=""><i class="fa fa-times" aria-hidden="true"></i></a>');
			}
		});

		$(document).on('change', '.field-search-q input', function(){
			if($(this).val() != ''){
				if($('.field-search-q .search-close-q').length == 0)
					$('.field-search-q').append('<a class="search-close-q" href=""><i class="fa fa-times" aria-hidden="true"></i></a>');
			}
		});
	}
	$(document).on('click', '.search-link', function(e){
		e.preventDefault();

		var reg_id = $(this).attr('data-id');
		var reg_text = $(this).text();

		$('.field-search-city input').val(reg_text);

		if($('.search-close').length == 0)
			$('.field-search-city').append('<a class="search-close" href=""><i class="fa fa-times" aria-hidden="true"></i></a>');

		$('.regions-layer').css({'display': 'none'});
	});

	$(document).on('click', '.regions-layer', function(){
		$('.field-search-city input').focus();
	});

	$(document).on('focusout', '.field-search-city input', function(){

		if($('.regions-layer' + ':hover').length) {
			return;
		}
		$('.regions-layer').css({'display': 'none'});

	});

	$(document).on('click', '.search-close', function(e){
		e.preventDefault();
		$('.field-search-city input').val("");
		$(this).remove();
	});
	$(document).on('click', '.search-close-q', function(e){
		e.preventDefault();
		$('.field-search-q input').val("");
		$(this).remove();
	});

	$(document).on('click', '.all-ukr', function(e){
		e.preventDefault();
		$('.regions-layer').css({'display': 'none'});
	});


	var q = '';
	$(document).on('pjax:start', function() {
		NProgress.start();
	});

	$(document).on('pjax:end', function() {
		NProgress.done();
	   	// var cat = $('input[name="cat"]').val();
	   	// var subcat = $('input[name="subcat"]').val();

	   	// var new_q = $("#search-q").val();
	   	// var q_ = $('input[name="q_"]').val();
	   	// var new_reg = $("#search-city").val();

	   	// var new_url = '/category';
	   	// var get_p = window.location.search;


	   	// if(cat.length > 0)
	   	// 	new_url += '/' + cat;
	   	
	   	// if(subcat.length > 0)
	   	// 	new_url += '/' + subcat;
	   	
	   	// if(new_q.length > 0){
	   	// 	//if(!subcat.localeCompare(new_q))
	   	// 		new_url += '/q-' + new_q;
	   	// }
	   	
	   	// if(new_reg.length > 0){
	   	// 	console.log(new_reg);
	   	// 	new_url += '/c-' + new_reg;
	   	// }

	   	// if(get_p.length > 0)
	   	// 	new_url += get_p;

	   	// var stateObg = {foo: new_url};

	   	// window.history.pushState(stateObg, new_url, new_url);
	   	if($('#search-form').length > 0){
		   	if($(document).find('.field-search-city input').val().length > 0){
		   		if($(document).find('.field-search-city .search-close').length == 0)
		   			$(document).find('.field-search-city').append('<a class="search-close" href=""><i class="fa fa-times" aria-hidden="true"></i></a>');
		   	}

		   	if($(document).find('.field-search-q input').val().length > 0){
		   		if($(document).find('.field-search-q .search-close-q').length == 0)
		   			$(document).find('.field-search-q').append('<a class="search-close-q" href=""><i class="fa fa-times" aria-hidden="true"></i></a>');
		   	}
		   }
	   });

	NProgress.configure({
		parent: '#box',
		showSpinner: false
	});


	// if($(document).find('.alert-success').length > 0){
	// 	$(document).find('.alert-success').fadeOut(5000).promise().done(function(){
	//      	$(document).find('.alert-success').remove();
	//  	});
	// }

$(document).on('click', '#search-img', function(){
	if(Number($(this).attr('value')) === 1){
		$(this).attr('value', 0);
	}else{
		$(this).attr('value', 1);
	}
});

$(document).on('click', '.inputs_js input[type="checkbox"], .inputs_js input[type="radio"]', function(){
	var this_name = $(this).attr('name');
	$('input[name="'+this_name+'"]').each(function(){
		$(this).removeAttr('checked');
	});

	$('input[value="'+$(this).attr('value')+'"]').attr('checked', 'checked');
});

	$(document).on('click', '.simple_search button', function(e){

		if($('#p0').length > 0){
			e.preventDefault();
		}
		SearchLink();
		// return false;
	});

	// $(document).on('change', '.cat-search', function(){
	// 	console.log('.cat-search');
	// 	SearchLink();

	// })

	
	function SearchLink(_price = null)
	{
		if($('#p0').length > 0){

			var cat = $('input[name="cat"]').val();
			var subcat = $('input[name="subcat"]').val();

			var new_q = $("#search-q").val();
			var q_ = $('input[name="q_"]').val();
			var new_reg = $("#search-city").val();
			var with_img = $("#search-img").val();
			var sprice = $('input[name="Search[sprice]"]').val();
			var eprice = $('input[name="Search[eprice]"]').val();

			var new_url = '/category/';
			var get_p = window.location.search;


			// get_p = get_p.replace('/^(?!temp=.*&$).*?/', '');

			var params = get_p.replace('?','').split('?').reduce(
					        function(p,e){
					            var a = e.split('=');
					            p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
					            return p;
					        },{});
			var paramss = get_p.replace('?','').split('&').reduce(
					        function(p,e){
					            var a = e.split('=');
					            p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
					            return p;
					        },{});


			var old_param = '';
			var old_param2 = '';
			var count = 0;
			for( var arr in paramss){
				if(arr.indexOf('course') == -1 && arr.indexOf('img') == -1 && arr.indexOf('ads') == -1 && arr.indexOf('sort') == -1){
					old_param += '&' + arr + '=' + paramss[arr];
					if(count == 0){
						old_param2 += '?' + arr + '=' + paramss[arr];count = 1;
					}else
						old_param2 += '&' + arr + '=' + paramss[arr];
				}
			}


			get_p = get_p.replace(old_param,'');
			get_p = get_p.replace(old_param2,'');


			ad = new Array();

			$('.inputs_js').each(function(i){

				if($(this).find('input').val() != undefined)
					if($(this).find('input').val().length > 0)
						if($(this).find('input').attr('type') == 'checkbox' || $(this).find('input').attr('type') == 'radio'){
							$(this).find('input').each(function(){
								if($(this).attr("checked") == 'checked')
									ad[$(this).attr('name').replace('Ads[sub_fields]', "").replace('[', "").replace(']', "")] = $(this).val();
							});
							
							
						}
						else if($(this).find('input').attr('type') != 'checkbox' && $(this).find('input').attr('type') != 'radio')
							ad[$(this).find('input').attr('name').replace('Ads[sub_fields]', "").replace('[', "").replace(']', "")] = $(this).find('input').val();
				if($(this).find('select').val() != undefined)
					if($(this).find('select').val().length > 0){
						if($(this).find('select').val().indexOf('ne-ukazano') == -1)
							ad[$(this).find('select').attr('name').replace('Ads[sub_fields]', "").replace('[', "").replace(']', "")] = $(this).find('select').val();
					}
			});


			var paramsss = '';

			for(var Arr in ad) {
				paramsss += '&' + Arr + '=' + ad[Arr];
			}


			if(cat.length > 0)
				new_url += '/' + cat;

			if(subcat.length > 0)
				new_url += '/' + subcat;

			if(new_q.length > 0)
		   		new_url += '/q-' + new_q.replace(/ /ig, '-');
		   	
		   	if(new_reg.length > 0)
		   		new_url += '/c-' + new_reg.replace(/ /ig, '-');


		    if(with_img == 1){
		    	get_p = get_p.replace('?img=img', '');
		    	get_p = get_p.replace('&img=img', '');
		   		new_url += '?img=img';
		   		if(paramss['ads'] != undefined)
			   		if(paramss['ads'].length > 0 && paramss['ads'].length != undefined){
			   			new_url += '&ads=' + paramss['ads'];
			   			get_p = get_p.replace('?ads='+paramss['ads'], '');
			   			get_p = get_p.replace('&ads='+paramss['ads'], '');
			   		}
		    }else{
		    	get_p = get_p.replace('?img=img', '');
		    	get_p = get_p.replace('&img=img', '');

		    	if(paramss['ads'] != undefined)
			    	if(paramss['ads'].length > 0 && paramss['ads'].length != undefined){
			   			new_url = new_url.replace('?ads='+paramss['ads'], '');
			   			new_url = new_url.replace('&ads='+paramss['ads'], '');
			   			get_p = get_p.replace('?ads='+paramss['ads'], '');
			   			get_p = get_p.replace('&ads='+paramss['ads'], '');
			   			new_url += '?ads=' + paramss['ads'];
			   		}
		    
		    }

		    if(_price === null){
		    	if(sprice != undefined)
				    if(sprice != 0 && sprice != ''){
				    	new_url += '?sprice=' + sprice;
				    	if(with_img == 1){
				    		new_url = new_url.replace('?img=img', '');
				    		new_url = new_url.replace('&img=img', '');
				    		get_p = get_p.replace('?img=img', '');
				    		get_p = get_p.replace('&img=img', '');
				   			new_url += '&img=img';
				    	}
				    	if(paramss['ads'] != undefined)
					    	if(paramss['ads'].length > 0 && paramss['ads'].length != undefined){
					   			new_url = new_url.replace('?ads='+paramss['ads'], '');
					   			new_url = new_url.replace('&ads='+paramss['ads'], '');
					   			get_p = get_p.replace('?ads='+paramss['ads'], '');
					   			get_p = get_p.replace('&ads='+paramss['ads'], '');
					   			new_url += '&ads=' + paramss['ads'];
					   		}
				    }

				if(eprice != undefined)
				    if(eprice != 0 && eprice != ''){
				    	new_url += '?eprice=' + eprice;
				    	if(with_img == 1){
				    		new_url = new_url.replace('?img=img', '');
				    		new_url = new_url.replace('&img=img', '');
				    		get_p = get_p.replace('?img=img', '');
				    		get_p = get_p.replace('&img=img', '');
				   			new_url += '&img=img';
				    	}
				    	if(paramss['ads'] != undefined)
					    	if(paramss['ads'].length > 0 && paramss['ads'].length != undefined){
					   			new_url = new_url.replace('?ads='+paramss['ads'], '');
					   			new_url = new_url.replace('&ads='+paramss['ads'], '');
					   			get_p = get_p.replace('?ads='+paramss['ads'], '');
					   			get_p = get_p.replace('&ads='+paramss['ads'], '');
					   			new_url += '&ads=' + paramss['ads'];
					   		}
				    }

				if(sprice != undefined && eprice != undefined)
				    if(sprice != 0 && sprice != '' && eprice != 0 && eprice != ''){
				    	get_p = get_p.replace('?sprice=' + paramss['sprice'] + '&eprice=' + paramss['eprice'], '');

				    	new_url = new_url.replace('?sprice=' + sprice, '');
				    	new_url = new_url.replace('?eprice=' + eprice, '');
				    	new_url += '?sprice=' + sprice + '&eprice=' + eprice;

				    	if(with_img == 1){
				    		new_url = new_url.replace('?img=img', '');
				    		new_url = new_url.replace('&img=img', '');
				    		get_p = get_p.replace('?img=img', '');
				    		get_p = get_p.replace('&img=img', '');
				   			new_url += '&img=img';
				    	}
				    	if(paramss['ads'] != undefined)
					    	if(paramss['ads'].length > 0){
					   			new_url = new_url.replace('?ads='+paramss['ads'], '');
					   			new_url = new_url.replace('&ads='+paramss['ads'], '');
					   			get_p = get_p.replace('?ads='+paramss['ads'], '');
					   			get_p = get_p.replace('&ads='+paramss['ads'], '');
					   			new_url += '&ads=' + paramss['ads'];
					   		}
				    }

		    }
		    get_p = get_p.replace('?sprice=' + paramss['sprice'], '').replace('&eprice=' + paramss['eprice'], '');
		    get_p = get_p.replace('?sprice=' + params['sprice'], '').replace('?eprice=' + params['eprice'], '');

		   	if(get_p.length > 0)
		   		new_url += get_p;

		   	new_url += paramsss;

		   	if(new_url.indexOf('?') == -1 && paramsss.length > 0){

		   		var pos = new_url.indexOf('&');
		   		new_url = new_url.substr(0,pos) + '?' + new_url.substr( ++pos );

		   	}
		   	new_url = new_url.replace('//', '/');
		   	new_url = new_url.replace('??', '/');
		   	new_url = new_url.replace('&&', '/');


		   	if(new_url.indexOf('?')+1){
		   		var poss = new_url.indexOf('?');
		   		var s_str = new_url.substr(0,poss+1);
		   		var e_str = new_url.substr( poss+1 );
		   		new_url = s_str;
		   		if(e_str.indexOf('?')+1){
		   			var posss = e_str.indexOf('?');
		   			var s_strr = e_str.substr(0,posss);
		   			var e_strr = e_str.substr( posss+1 );
		   			new_url += s_strr + '&' + e_strr;
		   		}else{
		   			new_url += e_str;
		   		}
		   	}


		   $.pjax.reload({container : '#p0', "timeout" : 5000, replace: true, url: new_url});
		}
	}

	$('.form-group input, .form-group select').change(function(){
		
	});

	$(document).on('click', '.link-list', function(e){

		var data_list = $(this).attr('data-list');
		$.ajax({
			type: 'POST',
			url: '/category/list/',
			data: {
				'data_list' : data_list
			},
			success: function(){
				$.pjax.reload({container : '#p0', "timeout" : 1000, replace: true, url: window.location});
			}
		});
	});

	$(document).on('click', '.delete-phone-count a', function(e){
		e.preventDefault();
		var ads_id = $(this).parent().parent().attr('data-ad-id');

		var cur_link = $(this);
		var p_div_link = $(this).parent();
		var span = $(this).parent().parent().find('.del-view-phone span');

		if($(this).data('model') == 'magazine'){
			return false;
		}

		$.ajax({
			type: 'POST',
			url: '/myaccount/updatephonecount/',
			data: {
				'ads_id' : ads_id
			},
			beforeSend: function(){
				p_div_link.css({'visibility': 'visible', 'opacity': '1'});
				p_div_link.html('<img id="imgcode" src="/images/ajax-loader.gif">');
			},
			success: function(data){
				span.html('0');
				p_div_link.html('<span style="color:green">Количество просмотров обнулено</span>');
				setTimeout(function(){
					p_div_link.attr('style', '');
					p_div_link.html('<a href="#">Обнулить</a><span> количество просмотров</span>');
				}, 5000);
			},
			error: function(){
				p_div_link.html('<span style="color:red">Произошла ошибка</span>');
				setTimeout(function(){
					p_div_link.attr('style', '');
					p_div_link.html('<a href="#">Обнулить</a><span> количество просмотров</span>');
				}, 5000);
			}
		});

	});

	$(document).on('click', '.delete-ad-count a', function(e){
		e.preventDefault();
		var ads_id = $(this).parent().parent().attr('data-ad-id');

		var cur_link = $(this);
		var p_div_link = $(this).parent();
		var span = $(this).parent().parent().find('.del-view-ad span');

		if($(this).data('model') == 'magazine'){
			return false;
		}

		$.ajax({
			type: 'POST',
			url: '/myaccount/updateadcount/',
			data: {
				'ads_id' : ads_id
			},
			beforeSend: function(){
				p_div_link.css({'visibility': 'visible', 'opacity': '1'});
				p_div_link.html('<img id="imgcode" src="/images/ajax-loader.gif">');
			},
			success: function(data){
				span.html('0');
				p_div_link.html('<span style="color:green">Количество просмотров обнулено</span>');

				setTimeout(function(){
					p_div_link.attr('style', '');
					p_div_link.html('<a href="#">Обнулить</a><span> количество просмотров</span>');
				}, 5000);
			},
			error: function(){
				p_div_link.html('<span style="color:red">Произошла ошибка</span>');
				setTimeout(function(){
					p_div_link.attr('style', '');
					p_div_link.html('<a href="#">Обнулить</a><span> количество просмотров</span>');
				}, 5000);
			}
		});

	});

	$(document).on('click', '.delete-phone-countt a', function(e){
		e.preventDefault();

		if($(this).data('model') == 'magazine'){
			var ads_id = $(this).parent().parent().attr('data-ad-id');

			var cur_link = $(this);
			var p_div_link = $(this).parent();
			var span = $(this).parent().parent().find('.del-view-phone span');

			$.ajax({
				type: 'POST',
				url: '/myaccount/updatephonecountt/',
				data: {
					'ads_id' : ads_id
				},
				beforeSend: function(){
					p_div_link.css({'visibility': 'visible', 'opacity': '1'});
					p_div_link.html('<img id="imgcode" src="/images/ajax-loader.gif">');
				},
				success: function(data){
					span.html('0');
					p_div_link.html('<span style="color:green">Количество просмотров обнулено</span>');
					
					setTimeout(function(){
						p_div_link.attr('style', '');
						p_div_link.html('<a href="#">Обнулить</a><span> количество просмотров</span>');
					}, 5000);
				},
				error: function(){
					p_div_link.html('<span style="color:red">Произошла ошибка</span>');
					setTimeout(function(){
						p_div_link.attr('style', '');
						p_div_link.html('<a href="#">Обнулить</a><span> количество просмотров</span>');
					}, 5000);
				}
			});
		}

	});

	$(document).on('click', '.delete-ad-countt a', function(e){
		e.preventDefault();
		e.stopPropagation();
		if($(this).data('model') == 'magazine'){
			var ads_id = $(this).parent().parent().attr('data-ad-id');

			var cur_link = $(this);
			var p_div_link = $(this).parent();
			var span = $(this).parent().parent().find('.del-view-ad span');

			$.ajax({
				type: 'POST',
				url: '/myaccount/updateadcountt/',
				data: {
					'ads_id' : ads_id
				},
				beforeSend: function(){
					p_div_link.css({'visibility': 'visible', 'opacity': '1'});
					p_div_link.html('<img id="imgcode" src="/images/ajax-loader.gif">');
				},
				success: function(data){
					span.html('0');
					p_div_link.html('<span style="color:green">Количество просмотров обнулено</span>');

					setTimeout(function(){
						p_div_link.attr('style', '');
						p_div_link.html('<a href="#">Обнулить</a><span> количество просмотров</span>');
					}, 5000);
				},
				error: function(){
					p_div_link.html('<span style="color:red">Произошла ошибка</span>');
					setTimeout(function(){
						p_div_link.attr('style', '');
						p_div_link.html('<a href="#">Обнулить</a><span> количество просмотров</span>');
					}, 5000);
				}
			});
		}

	});

	///////////////

	var color_green_btn = '#79be00';

	function getSvgOK(){
		return '<svg fill="#86d200" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 305.002 305.002" xml:space="preserve"><g><g><path d="M152.502,0.001C68.412,0.001,0,68.412,0,152.501s68.412,152.5,152.502,152.5c84.089,0,152.5-68.411,152.5-152.5S236.591,0.001,152.502,0.001z M152.502,280.001C82.197,280.001,25,222.806,25,152.501c0-70.304,57.197-127.5,127.502-127.5c70.304,0,127.5,57.196,127.5,127.5C280.002,222.806,222.806,280.001,152.502,280.001z"/><path d="M218.473,93.97l-90.546,90.547l-41.398-41.398c-4.882-4.881-12.796-4.881-17.678,0c-4.881,4.882-4.881,12.796,0,17.678l50.237,50.237c2.441,2.44,5.64,3.661,8.839,3.661c3.199,0,6.398-1.221,8.839-3.661l99.385-99.385c4.881-4.882,4.881-12.796,0-17.678C231.269,89.089,223.354,89.089,218.473,93.97z"/></g></g></svg>';
	}
	function getSvgWAR(){
		return '<svg fill="red" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve"><g><g id="error"><path d="M255,0C114.75,0,0,114.75,0,255s114.75,255,255,255s255-114.75,255-255S395.25,0,255,0z M280.5,382.5h-51v-51h51V382.5zM280.5,280.5h-51v-153h51V280.5z"/></g></g></svg>';
	}

	function validataionYii(event){
		$('#w0').yiiActiveForm('validate');

		// $(event).parent().parent().find('.text-green').css({'display':'none'});
		// if($(event).parent().parent().find('.text-green').length > 0)
			//$(event).parent().find('.help-block').css({'display': 'block'});
		// $(event).parent().find('.help-block').attr('style', '').addClass('help-block-error');

		if(event.type == 'checkbox')
			event = $(event).parent().parent();
		if(event.id == '#file1' || event.id == '#file2' || event.id == '#file4' || event.id == '#file4' || event.id == '#file5' || event.id == '#file6'){
			event = $('#file3');
		}

		if($(event).parent().hasClass('has-success')){
			if($(event).val() != '')
				$(event).parent().find('.help-block').removeClass('popup-before').attr('style', '').css({'background-color':'#fff'});
			if($(event).parent().find('.help-inf-ok').length == 0){
				$(event).parent().find('.help-inf').remove();
				$(event).parent().append('<p class="help-inf help-inf-ok">'+getSvgOK()+'</p>');
			}
		}else{
			$(event).parent().find('.help-block').css({'background-color':'red'});
			if($(event).parent().find('.help-inf-war').length == 0){
				$(event).parent().find('.help-inf').remove();
				$(event).parent().append('<p class="help-inf help-inf-war">'+getSvgWAR()+'</p>');
			}
		}
	}


	// $('.form-ads .form-group').on('keyup', 'input, select, checkbox, radio, textarea', function(){
	// 	validataionYii(this);
	// });
	// $('.form-ads .form-group').on('keydown', 'input, select, checkbox, radio, textarea', function(){
	// 	validataionYii(this);
	// });
	$('.form-ads .form-group').on('change', 'input, select, checkbox, radio, textarea', function(){
		validataionYii(this);
	});

	if($('#ads-contact').val() != ''){
		if(!$(this).parent().hasClass('has-error')){
			if($(this).parent().find('.help-inf-war').length == 0){
				$(this).parent().find('.help-inf').remove();
				$(this).parent().append('<p class="help-inf help-inf-war">'+getSvgOK()+'</p>');
			}
		}
	}



	///////////////


	function popupSetting(this_, text){
		//if($(this_).val() == ''){
			$(this_).parent().removeClass('has-error');
			$(this_).parent().addClass('has-success');
			$(this_).parent().find('.help-block').css({'padding':'5px 10px', 'background-color': color_green_btn});
			$(this_).parent().find('.help-block').addClass('popup-before');
			$(this_).parent().find('.help-block').html(text);
			$(this_).parent().find('.help-inf').remove();
			$(this_).parent().append('<p class="help-inf help-inf-ok">'+getSvgOK()+'</p>');
		//}
	}

	$('#add-title').click(function(){
		popupSetting(this, '<strong>Введите название продаваемого товара, услуги или объекта.</strong> Корректно сформированный заголовок с использованием тематических фраз поможет целевой аудитории быстрее увидеть Ваше объявление. Не стоит использовать заглавные буквы кроме первой, вносить в заголовок номера телефонов, электронные адреса и ссылки - такие объявления удаляются модертором');
	});

	$('#add-title').keyup(function(){
		popupSetting(this, '<strong>Введите название продаваемого товара, услуги или объекта.</strong> Корректно сформированный заголовок с использованием тематических фраз поможет целевой аудитории быстрее увидеть Ваше объявление. Не стоит использовать заглавные буквы кроме первой, вносить в заголовок номера телефонов, электронные адреса и ссылки - такие объявления удаляются модертором');
	});

	$('#add-title').keydown(function(){
		popupSetting(this, '<strong>Введите название продаваемого товара, услуги или объекта.</strong> Корректно сформированный заголовок с использованием тематических фраз поможет целевой аудитории быстрее увидеть Ваше объявление. Не стоит использовать заглавные буквы кроме первой, вносить в заголовок номера телефонов, электронные адреса и ссылки - такие объявления удаляются модертором');
	});
	$('#add-title').keypress(function(){
		popupSetting(this, '<strong>Введите название продаваемого товара, услуги или объекта.</strong> Корректно сформированный заголовок с использованием тематических фраз поможет целевой аудитории быстрее увидеть Ваше объявление. Не стоит использовать заглавные буквы кроме первой, вносить в заголовок номера телефонов, электронные адреса и ссылки - такие объявления удаляются модертором');
	});

	$('#desc').click(function(){
		popupSetting(this, '<strong>Опишите предлагаемый товар, услугу или объект.</strong> Введите важную информацию и постарайтесь раскрыть все преимущества продаваемого товара, услуги. Описание должно соответствовать предлагаемому товару и заголовку. Количество символов не должно превышать 500 знаков. Не стоит в описание вносить свои контактные данные и ссылки - такие объявления удаляются модертором');
	});

	$('#ads-location').click(function(){
		popupSetting(this, 'Укажите населенный пункт и выберите Ваше местоположение из списка');
	});
	$('#ads-phone').click(function(){
		popupSetting(this, '<strong>Укажите свой номер телефона.</strong> Допускается введение нескольких номеров, разделив их запятой.');
	});

	$('#add-title, #desc, #contacts input').blur(function(){
		if($(this).val() == ''){
			if(!$(this).parent().hasClass('has-error')){
				$(this).parent().find('.help-inf').remove();
				$(this).parent().removeClass('has-success');
				$(this).parent().find('.help-block').attr('style', '').removeClass('popup-before').text('');
			}
		}
		if($(this).val() != ''){
			if(!$(this).parent().hasClass('has-error')){
				if($(this).parent().find('.help-inf-ok').length == 0){
					$(this).parent().find('.help-inf').remove();
					$(this).parent().addClass('has-success');
					$(this).parent().append('<p class="help-inf help-inf-ok">'+getSvgOK()+'</p>');
				}
				$(this).parent().find('.help-block').attr('style', '').removeClass('popup-before').text('');
			}
		}
	});


$('#w0').on('afterValidateAttribute', function(event, attribute, messages) {
    
});


	
	$('#ads-contact').hover(function(){
		$(this).parent().find('.help-block').css({'padding': '5px 10px', 'background-color': color_green_btn}).addClass('popup-before').text('Изменить контактную информацию Вы можете в настройках своего профиля.');
		$(this).parent().append('<p class="help-inf help-inf-ok">'+getSvgOK()+'</p>');
		
	}, function(){
		$(this).parent().find('.help-inf').remove();
		$(this).parent().find('.help-block').attr('style', '').removeClass('popup-before').text('');
	});


	$('.upl-img').hover(function(){
		var input = $(this).find('.form-group input[type="file"]');
		var input_text = $(input).data('text');
		if(!$('.file1, .file2, .file3, .file4, .file5, .file6').find('.form-group').hasClass('has-error')){
			$('.field-file3 .help-block').css({'padding': '5px 10px', 'background-color': color_green_btn}).addClass('popup-before').html(input_text);
			$('.field-file3').append('<p class="help-inf help-inf-ok">'+getSvgOK()+'</p>');
		}
		
	}, function(){
		var chec_ = true;
		$('.img-upl .upl-img').each(function(i){
			if($(this).find('.form-group').hasClass('has-error')){
				chec_ = false;
				_clear();
			}
		});

		if(chec_){
			_clear();
		}
	});


	function _clear()
	{
		if(!$('.file3').find('.form-group').hasClass('has-error')){
			$('.field-file3 .help-inf').remove();
			$('.file3').find('.help-block').attr('style', '').removeClass('popup-before').text('');
		}
		
	}
	$(document).on('click', '.cat-search', function(e){
		e.preventDefault();

		if($(this).parent().find('.cat-search + ul').css('display') == 'none')
			$(this).parent().find('.cat-search + ul').css({'display':'block'});
		else
			$(this).parent().find('.cat-search + ul').css({'display':'none'});

	});

	$(document).on('click', '.cat-search + ul a, .secondary-ul a, .third-ul a', function(e){
		e.preventDefault();
		
		$('.cat-search').text($(this).text()).append('<i class="fa fa-angle-down" aria-hidden="true"></i>');

		var li = $(this).parent();
		var alias = li.attr('data-category-alias');
		var id = li.attr('data-category-id');
		var parent_alias = $('li[data-category-id="'+ li.parent().attr('data-parent-id') +'"]').data('category-alias');

		var _price = null;
		if((id >= 30 && id <= 39) || (id >= 226 && id <= 266) || (id >= 451 && id <= 778))
			_price = true;
  //       	$('.form-price').hide();
  //       else
  //       	$('.form-price').show();


		// $.ajax({
		// 	type: 'POST',
		// 	url: '/ads/subfields/',
		// 	data: {'category_id': id, 'search': id}, //, 'search': id
		// 	success: function(data){
		// 		SearchLink();
		// 		$('.sub-fields-search > *').remove();
		// 		$('.sub-fields-search').append(data);
		// 	}
		// });


		$('input[name="cat"]').val(parent_alias);
		$('input[name="subcat"]').val(alias);
		$('.cat-search').focus();
		$('.cat-search + ul').css({'display': 'none'});

		SearchLink(_price);
	});



	$(document).on('focusout', '.cat-search', function(){

		if($('.cat-search + ul' + ':hover').length) {
			return;
		}

		if($('.secondary-ul' + ':hover').length) {
			return;
		}

		if($('.third-ul' + ':hover').length) {
			return;
		}

		if($('.cat-search + ul').css('display') == 'block'){
			$('.cat-search + ul').css({'display': 'none'});

			$('.parent-ul').css({'display':'none'});
			$('.secondary-ul').css({'display':'none'});
			$('.third-ul').css({'display':'none'});

		}
	});


	$(document).on('click', '.mess-add-file', function(e){
		e.preventDefault();

		$('.wrap-inf-mes').css({'display': 'block'});
	});





	///////////////////////////////////////////////////////////////////////
	/////////////// MODAL CREATE DIALOG ///////////////////////////////////


	var valid = false;
	$(document).on('click', '#w0 button[type="submit"], #preview-modal button[type="submit"]', function(e){
		// e.preventDefault();

		valid = true;
		// $('#w0').data('yiiActiveForm').submitting = true;
		// $('#w0').yiiActiveForm('validate');

		// $('#preview-modal').modal('hide');
		// $('#w0').unbind('submit');
		// $('#w0').on('submit', function(){$('body').append('<div id="loader-wrapper" class="loader-wrapper"><div id="loader"></div><div class="loader-section section-left"></div><div class="loader-section section-right"></div></div>');return true;});
		// $('#w0').data('yiiActiveForm').submitting = true;
		// $('#w0').yiiActiveForm('validate');

		// $('#w0').trigger('submit');
	});
	$(document).on('click' , '#preview-modal button[type="submit"]' , function(e){
		// e.preventDefault();
		valid = true;
		$('#preview-modal').modal('hide');
		$('#w0').trigger('submit');
		// $('#w0').unbind('submit');
		// $('#w0').on('submit', function(){$('body').append('<div id="loader-wrapper" class="loader-wrapper"><div id="loader"></div><div class="loader-section section-left"></div><div class="loader-section section-right"></div></div>');return true;});
		// $('#w0').data('yiiActiveForm').submitting = true;
		// $('#w0').yiiActiveForm('validate');
	});

	$('#w0').on('submit', function(){
		if(valid)
			return true;
		else return false;
	});

	$(document).on('click', '#predview', function(e){
		e.preventDefault();

		var $form = $('#w0');
		valid = false;
		$form.trigger('submit');
    	// $form.on('submit', function () {return false;});


        if($('#w0 .has-error').length == 0 && $('#ads-location').val().length > 0){
    		var ad = new Object();
    		ad['images'] = new Object();
	  		ad['sub_field'] = new Object();

	  		ad['name'] 				= $('#add-title').val();
	  		ad['text'] 				= $('#desc').val();
	  		ad['price']				= $('#ads-price').val();
	  		ad['type_payment']		= $('#ads-type_payment').val();
	  		ad['type_delivery']		= $('#ads-type_delivery').val();
	  		ad['torg']				= $('#torg').val();
	  		ad['without_payment'] 	= $('#without_payment').val();
	  		ad['location'] 			= $('#ads-location').val();
	  		ad['phone'] 			= $('#ads-phone').val();
	  		ad['phone_2'] 			= $('#ads-phone_2').val();
	  		ad['phone_3'] 			= $('#ads-phone_3').val();
	  		ad['email'] 			= $('#ads-email').val();
	  		ad['contact'] 			= $('#ads-contact').val();
	  		ad['bargain'] 			= $('#torg').val();
	  		ad['without_payment'] 	= $('#without_payment').val();


			$('.file-i img').each(function(i){
				if($(this).attr('src').indexOf("/images/add-min.png") == -1)
					ad['images'][i] = $(this).attr('src');
			});

			$('.inputs_js').each(function(i){
				var label = $(this).find('.fleft.label label').text();

				if($(this).find('input').val() != undefined)
					if($(this).find('input').val().length > 0)
						ad['sub_field'][label] = $(this).find('input').val();
				if($(this).find('select').val() != undefined)
					if($(this).find('select').val().length > 0)
						ad['sub_field'][label] = $(this).find('select option[selected]').text();
			});


	  		$.ajax({
		        url: '/ads/preview/',
		        data: {'ad':JSON.stringify(ad)},
		        type: 'POST',
				beforeSend: function(){
					$('body').append('<div id="loader-wrapper" class="loader-wrapper"><div id="loader"></div><div class="loader-section section-left"></div><div class="loader-section section-right"></div></div>');
				},
		        success: function (ad) {
		        	ad = $.parseJSON(ad);

					$('#preview-modal .modal-body').html(ad);
					$('#preview-modal').modal()

		            setTimeout(function(){$('.loader-wrapper').remove()}, 200);
		        },
		        error: function(){

		        	$('#preview-modal .modal-body').html('<p>Произошла ошибка</p>');
		        	$('#preview-modal').modal();
		        	setTimeout(function(){$('.loader-wrapper').remove()}, 200);
		        }
		    });
    	}
	});

	///////////////////////////////////////////////////////////////////////
	/////////////// MODAL CREATE DIALOG ///////////////////////////////////






	////// SET REKLAMA ///////////////////

	function removeActiveItem(this_){
		$(this_).find('.s-item').removeClass('s-item__active').find('input[name="Promotion[promotion]"]').val('0').end().find('.s-item__btn').removeClass('s-item__btn-active').html('Выбрать');
	}

	function setTotalPrice(){
		$('.s-list__bot .s-item__price').html(getTotalPrice() + getCurrentCourse());
	}

	function getTotalPrice(){
		var total = 0;
		for(var item in promo_price)
			total += promo_price[item];
		return total;
	}

	function getCurrentCourse(){
		return '<span class="s-item__price-currency">грн.</span>';
	}

	function setPackagePrice($){
		package_list = {'start' : 30, 'medium' : 49, 'full' : 89};
	}

	function setSelectPrice(select_name){
		select_list[select_name] = new Object();
		select_list[select_name] = $.parseJSON($('input[type="hidden"][name="'+select_name+'"]').val());
	}


	var promo_price = {'package' : 0, 'select_top' : 0, 'select_vip' : 0, 'select_up' : 0, 'select_fire' : 0, 'select_once' : 0},
		select_list = new Object(),
		package_list = {
			'start' : Number($('input[type="hidden"][name="start"]').val()), 
			'medium' : Number($('input[type="hidden"][name="medium"]').val()), 
			'full' : Number($('input[type="hidden"][name="full"]').val())
		};


	if($('#PaymentForm').length > 0){
		setSelectPrice('select_up');
		setSelectPrice('select_vip');
		setSelectPrice('select_top');
		setSelectPrice('select_fire');
		setSelectPrice('select_once');
	}
	

	$('.s-items').click(function(e){
		e.preventDefault();
		var package = e.target.getAttribute('data-package'),
			item = e.target.parentNode,button = e.target,
			check = $(e.target).parent().find('input[type="hidden"]');

		if(package){
			if(button.className.indexOf('s-item__btn-active') !== -1){
				removeActiveItem(this);
				promo_price['package'] = 0;
			}else{
				removeActiveItem(this);
				item.className += ' s-item__active';button.className += ' s-item__btn-active';button.innerHTML = 'Выбрано<i class="fa fa-check" aria-hidden="true"></i>';
				promo_price['package'] = package_list[package];
				check.val(package);
			}
			setTotalPrice();
		}
	});

	$('.service select').change(function(e){
		var name = e.target.getAttribute('name'),
			value = e.target.value,
			input_checked = $(this).parent().find('input[type="checkbox"]');

		promo_price[name] = select_list[name][value];
		$(this).parent().find('.s-item__price').html(select_list[name][value] + getCurrentCourse());

		if(input_checked[0].checked){
			input_checked[0].value = value;
			setTotalPrice();
		}else{
			promo_price[name] = 0;
		}
	});

	$('.service input[type="checkbox"]').click(function(e){
		var name = $(this).parent().find('select').attr('name'),
			value = $(this).parent().find('select').val(),
			checkbox = e.target;

		if(checkbox.checked){
			checkbox.value = value;
			promo_price[name] = select_list[name][value];
			setTotalPrice();
		}else{
			promo_price[name] = 0;
			setTotalPrice();
		}
	});


	$('#check_promo').click(function(e){
		if(getTotalPrice() === 0)
			e.preventDefault();
	});


	$('#PaymentButton').click(function(){
		if(getTotalPrice() == 0)
			return false;
		else return true;
	});


	$('[data-toggle="tooltip"]').tooltip();

		////// SET REKLAMA ///////////////////




	/// MODAL AFTER DELETE ////

	$('.delete-click').click(function(e){
		e.preventDefault();
		
		if(confirm('Вы действительно хотите Удалить?')){
			var ad_id = $(this).attr('href').split('?')[1].split('=')[1];
			var tr = $(this).parent().parent().parent().parent();

			$('#after-del').modal();

			$.ajax({
		        url: '/myaccount/deleteajax/',
		        data: {'ad' : ad_id},
		        type: 'POST',
				beforeSend: function(){
					$('body').append('<div id="loader-wrapper" class="loader-wrapper"><div id="loader"></div><div class="loader-section section-left"></div><div class="loader-section section-right"></div></div>');
				},
		        success: function (data) {
		        	$('#loader-wrapper', 'body').remove();

		        	$('main > .container').prepend('<div id="w0-success-0" class="alert-success alert fade in">' +
		        		'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + data + '</div>');
		        	tr.next().remove();
		        	tr.remove();

					// $('#preview-modal .modal-body').html(ad);
					// $('#preview-modal').modal()

		        },
		        error: function(){
		        	$('#loader-wrapper', 'body').remove();
		        	$('main > .container').prepend('<div id="w0-success-0" class="alert-warning alert fade in">' +
		        		'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Обьявление не удалено</div>');
		        }
		    });


		}
	});

	$('.after-del-link').on('click', function(e){
		var result = $(this).attr('data-value');

		$.ajax({
	        url: '/myaccount/afterdel/',
	        data: {'result' : result},
	        type: 'POST',
	        success: function (data) {
	        }
	    });
	});

	/// MODAL AFTER DELETE ////


	$('.about-author').click(function(){
		$('#user-status').modal();
	});


	$('.send-user-status').click(function(e){
		e.preventDefault();

		var data = $('#user-status-form').serialize();

		$.ajax({
	        url: '/ads/userstatus/',
	        data: {'data' : data},
	        type: 'POST',
	        success: function (data) {
	        	data = $.parseJSON(data);
	        	$("#user-status .modal-body textarea").before('<p><small>'+data[0]+'</small> <a href="/category/user-ads/'+data[1]+'">Вы</a></p>');
	        	$("#user-status .modal-body textarea").before('<p>'+$('#user-status-form textarea').val()+'</p>');
	        	$('#user-status-form textarea').val('');
	        },
	        error: function(){
	        	console.log('Произошла ошибка');
	        }
	    });
	});


	$('#user-status i.fa-times').click(function(){
		var id = $(this).attr('data-id');
		var this_  = $(this).parent();
		$.ajax({
	        url: '/ads/userstatusdel/',
	        data: {'id' : id},
	        type: 'POST',
	        success: function (data) {
	        	$(this_).prev().remove();
	        	$(this_).remove();
	        },
	        error: function(){
	        	console.log('Произошла ошибка');
	        }
	    });
	});

	$(document).on('click', '#button_admin_message', function(e){
		e.preventDefault();

		$('#admin-message').modal('show');

		console.log(1);
	});

	$(document).on('click', '#send_admin_btn', function(e){
		e.preventDefault();

		var data = $('#form_admin_message_form').serialize();

		$.ajax({
	        url: '/site/adminmessage/',
	        data: {'data' : data},
	        type: 'POST',
	        success: function (data) {
	        	$('#admin-message textarea').val('');
	        	$('#admin-message input').val('');
	        	$('#form_admin_message_form').append('<p>Сообщение отправлено успешно!</p>');
	        },
	        error: function(){
	        	$('#admin-message').append('<p>Произошла ошибка</p>');
	        }
	    });

	});


	if (window.matchMedia("(max-width: 610px)").matches){
        $('a.myaccount-link + ul.myaccount-menu').prepend('<i class="fa fa-times close-myaccount-menu" aria-hidden="true"></i>')
		$('header .btn.j-primary.myaccount-link').click(function () {
            $('a.myaccount-link + ul.myaccount-menu').animate({right: '-16px'}, 1000);
            return false;
        });
        $('header .close-myaccount-menu').click(function () {
            $('a.myaccount-link + ul.myaccount-menu').animate({right: '-400px'}, 1000);
            return false;
        })
	}


    if (window.matchMedia("(max-width: 480px)").matches) {
        if(window.location.href == 'https://jandooo.com/'){
            $('main > div:nth-child(5)').insertBefore($('main > div:nth-child(5)').prev());
            $('main > div:nth-child(1)').append('<div class="mob__main--menu"><a href="/magazine/shops" class="mob__main--menu-block">Магазины</a><a href="/category/index" class="mob__main--menu-block">Объявления</a><a href="/pomosch-2" class="mob__main--menu-block">Помощь</a></div>');
        }

        $('#myaccount').parent().prepend('<a class="button-for-myaccount" href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>');
    	$('#myaccount .nav-tabs li').css('display', 'none');
        $('#myaccount .nav-tabs li.active').css('display', 'block');



        $('.cat-search + ul a').click(function () {
            if($(this).parent().is('ul')){
                /*Скрываем ссылку*/
                debugger;
                $('#cat-search').css('display','none');
                var new_button = $(this).find('ul').clone();
                var name_button = $(this).text();
                var id = $(this).parent().data('data-category-id');
            }
        });
    }

    $(".mob-tab-button a").click(function() {
        if($(this).parent().hasClass('active')){
            $('main > div:nth-child(4)').fadeOut();$(this).parent().removeClass('active');return false
        }
        else{
            $('main > div:nth-child(4)').fadeIn();$(this).parent().addClass('active');return false;
        }
    });

    $('.button-for-myaccount').click(function () {

    	if($(this).hasClass('active')){
            $('#myaccount .nav-tabs li:not(.active)').css('display', 'none');
            $(this).html('<i class="fa fa-bars" aria-hidden="true"></i>');
            $(this).removeClass('active');return false;
        }
        else {
            $('#myaccount .nav-tabs li').css('display', 'block');
            $(this).html('<i class="fa fa-times" aria-hidden="true"></i>');
            $(this).addClass('active');return false;
        }
    });


    $(document).on('click', '.del-img-ac', function(){
		var img_id = $(this).data('id');
		$.post( "/myaccount/deleteimg/", { img_id:  img_id} , function() {
			$('.wrap-user-ac-img').remove();
		});
	});

	$(document).on('click', '.del-img-acc', function(){
		var img_id = $(this).data('id');
		var model_id = $(this).data('model');
		$.post( "/myaccount/deleteimgg/", { img_id:  img_id, model_id: model_id} , function() {
			$('.wrap-user-ac-img').remove();
		});
	});

	$(document).on('click', '.del-img-accc', function(){
		var img_id = $(this).data('id');
		var model_id = $(this).data('model');
		$.post( "/myaccount/deleteimggg/", { img_id:  img_id, model_id: model_id} , function() {
			$('.wrap-user-ac-img').remove();
		});
	});

	$(document).on('click', '.del-img-acccc', function(){
		var img_id = $(this).data('id');
		var _this = $(this);
		var model_id = $(this).data('model');
		var old_ = $(this).data('old');
		var inp = '<div class="form-group field-magazineads-imagesfiles has-success"><label class="control-label" for="magazineads-imagesfiles">Изображения </label> <input type="hidden" name="MagazineAds[imagesFiles][]" value=""><input type="file" id="magazineads-imagesfiles" name="MagazineAds[imagesFiles][]" accept="image/*" aria-invalid="false"><div class="help-block"></div></div>';
			console.log(inp);	
		if(old_ != ''){
			$.post( "/myaccount/deleteimggggold/", { img_id:  img_id, model_id: model_id, old: old_} , function() {
				_this.parent().remove();
			});
		}else{
			$.post( "/myaccount/deleteimgggg/", { img_id:  img_id, model_id: model_id} , function() {
				_this.parent().remove();
				$('.insert-kartinka').append(inp);
			});
		}
	});

	
    /*** create mag ****/

	$(document).on('click', '.check-mag-tarif', function(e){
		e.preventDefault();
		var tarif_id = $(this).data('id');
		var inputTarifId = $('input[id="magazine-tarif_plan"]');
		inputTarifId.val(tarif_id);
		$('.row.tariff .item').removeClass('active');
		$(this).parent().addClass('active');
	});


	$(document).on('change', '#magazine-period', function(e){

		var id = $(this).val(),
			action = '/magazine/ajax-plan/';

		$.ajax({
	        url: action,
	        data: {'id' : id},
	        type: 'POST',
	        success: function (data) {
	        	var msg = $.parseJSON(data);
	        	for(var i = 0; i < msg.length; i++){
	        		var item = $('.item[data-id="' + msg[i]['plan_id'] + '"]');
	        		item.find('.span-count').text(msg[i]['count_ads']);
	        		item.find('.days').text(msg[i]['top_30_day']);
	        		item.find('.span-price').text(Number(msg[i]['price']).toFixed(0));
	        		item.find('.days-fire').text(Number(msg[i]['fire']).toFixed(0));
	        		item.find('.span-dop').text(Number(msg[i]['dop_tov']).toFixed(0));

	        		item.find('.span-ind-des').text(Number(msg[i]['ind_design']).toFixed(0));

	        		if(Number(msg[i]['old_price']).toFixed(0) != 0)
	        			item.find('.price-relative').html('<span class="line-through"></span><span class="span-old-price">' + Number(msg[i]['old_price']).toFixed(0) + '</span>');
	        		else 
	        			item.find('.price-relative').html('');


	        		if(Number(msg[i]['per_consult']).toFixed(0) === 1)
	        			item.find('.span-per_consult i').removeClass('fa-check').removeClass('fa-close').addClass('fa-check');
	        		else
	        			item.find('.span-per_consult i').removeClass('fa-check').removeClass('fa-close').addClass('fa-close');	

	        		if(Number(msg[i]['design']) === 1)
	        			item.find('.check i').removeClass('fa-check').removeClass('fa-close').addClass('fa-check');
	        		else
	        			item.find('.check i').removeClass('fa-check').removeClass('fa-close').addClass('fa-close');	
	        	}
	        }
	    });

	});

	$('.hover-pop').hover(function(){
		if(!$(this).parent().hasClass('has-error')){
			$(this).parent().addClass('has-success');
			$(this).parent().find('.help-block').css({'padding': '5px 10px', 'background-color': color_green_btn}).addClass('popup-before').text($(this).data('text'));
		}// $(this).parent().append('<p class="help-inf help-inf-ok">'+getSvgOK()+'</p>');
		
	}, function(){
		if(!$(this).parent().hasClass('has-error')){
			$(this).parent().removeClass('has-success').find('.help-inf').remove();
			$(this).parent().find('.help-block').attr('style', '').removeClass('popup-before').text('');
		}
	});

	$(document).on('click', '.without-val', function(){
		$('#w0').yiiActiveForm('destroy');
		// $('#w0').yiiActiveForm('remove', 'magazine-desc');
		// $('#w0').yiiActiveForm('remove', 'magazine-tarif_plan');
		// $('#w0').yiiActiveForm('remove', 'magazine-period');
	});
	/*** create mag end ****/

	$(document).on('click', '.click-childs', function(){
		var _class = $(this).data('class');
		console.log($(_class));
		$('.' + _class).toggle();
	});

	$(document).on('change', '#magazine-shop-form input, #magazine-shop-form select', function(){
		$('#magazine-shop-form').submit();
	});

	var csrfToken = $('meta[name="csrf-token"]').attr("content");

	$(document).on('change', '#ads-reg_id', function(e) {
		var _id = $(this).val();

		$.post('/ads/get-city/', {id : _id, _csrf : csrfToken}, function(data){$('#ads-city_id').html(data);});
	});

	$(document).on('change', '#ads-city_id', function(e){
		console.log($(this).val());
	});
    $(document).on('change', '#magazine-reg_id', function(e) {
        var _id = $(this).val();

        $.post('/ads/get-city/', {id : _id, _csrf : csrfToken}, function(data){$('#magazine-city_id').html(data);});
    });


    if (window.matchMedia("(max-width: 600px)").matches) {
        $('.parent-ul li').each(function () {
            var id_1 = $(this).data('category-id'), i = 0;
            $('.secondary-ul').each(function () {
                var id_2 = $(this).data('parent-id');
                if(id_1 == id_2){i = 1;return true;}
            });
            if(i == 1) $(this).find('a').append('<i class="ul-search-icon fa fa-angle-right" data-icon-id="'+ id_1 +'"></i>');
        });
        $('.secondary-ul li').each(function () {
            var id_1 = $(this).data('category-id'), i = 0;
            $('.secondary-ul').each(function () {
                var id_2 = $(this).data('parent-id');
                if(id_1 == id_2){i = 1;return true;}
            });
            if(i == 1) $(this).find('a').append('<i class="ul-search-icon fa fa-angle-right" data-icon-id="'+ id_1 +'"></i>');
        });
        $('.ul-search-icon').click(function () {
            var id_icon = $(this).data('icon-id');
            $('.cat-search ~ ul').css({'display':'none'});
            $('ul[data-parent-id="' + id_icon + '"]').css({'display':'block'});
            return false;
        });

        $(document).ajaxComplete(function() {
            $('.parent-ul li').each(function () {
                var id_1 = $(this).data('category-id'), i = 0;
                $('.secondary-ul').each(function () {
                    var id_2 = $(this).data('parent-id');
                    if(id_1 == id_2){i = 1;return true;}
                });
                if(i == 1) $(this).find('a').append('<i class="ul-search-icon fa fa-angle-right" data-icon-id="'+ id_1 +'"></i>');
            });
            $('.secondary-ul li').each(function () {
                var id_1 = $(this).data('category-id'), i = 0;
                $('.secondary-ul').each(function () {
                    var id_2 = $(this).data('parent-id');
                    if(id_1 == id_2){i = 1;return true;}
                });
                if(i == 1) $(this).find('a').append('<i class="ul-search-icon fa fa-angle-right" data-icon-id="'+ id_1 +'"></i>');
            });
            $('.ul-search-icon').click(function () {
                var id_icon = $(this).data('icon-id');
                $('.cat-search ~ ul').css({'display':'none'});
                $('ul[data-parent-id="' + id_icon + '"]').css({'display':'block'});
                return false;
            });
        });
    }
    else {
        $(document).on('mouseover', '.parent-ul li', function(event){
            var cat_id = event.target.parentNode.getAttribute('data-category-id');
            $('.secondary-ul').css({'display':'none'});
            $('ul[data-parent-id="' + cat_id + '"]').css({'display':'block'});
        });

        $(document).on('mouseover', '.secondary-ul li', function(event){
            var cat_id = event.target.parentNode.getAttribute('data-category-id');
            if(!$(this).parent().hasClass('third-ul')){
                $('.third-ul').css({'display':'none'});
                $('ul[data-parent-id="' + cat_id + '"]').addClass('third-ul').css({'display':'block'});
            }
        });
    }



 //    $('#add-title').keypress(function(e) { 
	//     var s = String.fromCharCode( e.which );

	//     var str = $(this).val() === '' ? s : $(this).val() + s;
	//     var length_ = str.length;
	//     var lastTreeChar = str.substring(length_ - 4, length_);

	//     var caps = e.getModifierState && e.getModifierState( 'CapsLock' )
	//     console.log(caps);
	//     console.log(str);
	//     console.log(lastTreeChar);



	//     // if( (e.shiftKey && lastTreeChar === lastTreeChar.toUpperCase() && length_ >= 4) || 
	//     // 	lastTreeChar === lastTreeChar.toUpperCase() && length_ >= 4){
	//     // 	var newStr = str.substring(0, length_ - 4) + lastTreeChar.substring(0, lastTreeChar.length - 1) + s.toLowerCase();
	//     // 	$(this).val(newStr);
	//     // }

	// });

	var el_ = document.getElementById('add-title');
	if(el_){
	el_.addEventListener( 'keydown', function( event ) {
	  	var caps = event.getModifierState && event.getModifierState( 'CapsLock' );
	  	var s = String.fromCharCode( event.which );

	  	if(caps ){
	  		alert('Включен CapsLock');
	  	}
	});}


	Share = {
		facebook: function(purl, ptitle, pimg, text) {
			url  = 'http://www.facebook.com/sharer.php?s=100';
			url += '&p[title]='     + encodeURIComponent(ptitle);
			url += '&p[summary]='   + encodeURIComponent(text);
			url += '&p[url]='       + encodeURIComponent(purl);
			url += '&p[images][0]=' + encodeURIComponent(pimg);
			Share.popup(url);
		},
		twitter: function(purl, ptitle) {
			url  = 'http://twitter.com/share?';
			url += 'text='      + encodeURIComponent(ptitle);
			url += '&url='      + encodeURIComponent(purl);
			url += '&counturl=' + encodeURIComponent(purl);
			Share.popup(url);
		},
		inst: function(purl, ptitle) {
			url  = 'https://www.linkedin.com/shareArticle?';
			url += 'url='      + encodeURIComponent(purl);
			url += '&title='      + encodeURIComponent(ptitle);
			Share.popup(url);
		},
		google: function(purl) {
			url  = 'https://plus.google.com/share?';
			url += 'url='      + encodeURIComponent(purl);
			Share.popup(url);
		},
		popup: function(url) {
			window.open(url,'','toolbar=0,status=0,width=626,height=436');
		}
	};

	$(document).on('change', '#magazine-template', function(e){
		var template = $(this).val();
		if(template == 3){
			$(this).after('<div class="ind-temp">Вы выбрали индивидуальный дизайн - сумма Вашего заказа увеличивается на <strong>250 грн</strong></div>');
		}else{
			$('.ind-temp').remove();
		}

	});

	function removeSuc(){
		$('.alert-success').fadeOut(300);
	}
	setTimeout(removeSuc, 3000);
	setInterval(removeSuc, 5000);

});