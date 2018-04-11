$(document).ready(function(){

	$('.switch input[type="checkbox"]').each(function(){
		var value_active = $(this).val();
		if(value_active == 1){
			$(this).attr('checked','checked');
			// console.log(value_active);
		}
	});

	 $('.switch input[name="checkbox_active"]').click(function(){
	 	var value_act = 0;
	 	var value_active_a = $(this).val();
	 	var value = $(this).parent().find('input[type="hidden"]').val();
	 	var controller = $(this).parent().find('input[type="hidden"]').attr('name');

	 	if(Number(value_active_a) > 0){
			$(this).val('0');
			value_act = 0;
		}else{
			$(this).val('1');
			value_act = 1;
		}

		if($(this).attr('name') !== 'active'){
			$.ajax({
			  type: 'POST',
			  url: '/admin/'+ controller + '/index',
			  data: {'checkbox_active': value_act, 'id': value},
			  success: function(data){
			    console.log(data);
			  }
			});
		}
	});
	 $('.switch-cu input[type="checkbox"]').click(function(){
	 	var value_active_a = $(this).val();
	 	if(Number(value_active_a) > 0){
			$(this).val('0');
			value_act = 0;
		}else{
			$(this).val('1');
			value_act = 1;
		}
	 });

	$('.child-category').click(function(e){
		var id = $(this).attr('data-id');
		$('div[data-wrap-id="' + id +'"]').slideToggle();
		return false;

	});

	var value_alias = $('.alias-input').val();

	if(value_alias != ''){
		$('.input-group-addon i').removeClass('fa fa-unlock');
		$('.input-group-addon i').addClass('fa fa-lock');
		$('.alias-input').attr('readonly', 'readonly');
	}

	$('.input-group-addon').click(function(){

		if($(this).find('i').attr("class") == 'fa fa-unlock'){
			$(this).find('i').removeClass('fa fa-unlock');
			$(this).find('i').addClass('fa fa-lock');
			$('.alias-input').attr('readonly', 'readonly');
		}
		else if($(this).find('i').attr("class") == 'fa fa-lock'){
			$(this).find('i').removeClass('fa fa-lock');
			$(this).find('i').addClass('fa fa-unlock');
			$('.alias-input').removeAttr('readonly');
		}
	});

$(".popup-with-zoom-anim:not(.ads-user)").click(function(){
		$.ajax({
			  type: 'POST',
			  url: '/admin/ads/send-message',
			  data: {'user_id': $(this).attr('data-ajax-user-id')},
			  success: function(data){
			  	data = jQuery.parseJSON(data);
			  	$('input[name="email"]').attr('value', data['email']);
			  	$('input[name="username"]').attr('value', data['username']);
			  }
			});
	});

	$('.popup-with-zoom-anim').magnificPopup({
		type: 'inline',
		fixedContentPos: false,
		fixedBgPos: true,
		overflowY: 'auto',
		closeBtnInside: true,
		preloader: false,
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-zoom-in'
	});

	$('.send-main-submit ').click(function(e){
		e.preventDefault();
		
		$.ajax({
			  type: 'POST',
			  url: '/admin/ads/send-message',
			  data: {
			  	'email': $('input[name="email"]').val(),
			  	'username': $('input[name="username"]').val(),
			  	'message': $('textarea[name="message"]').val(),
			  	'sub': $('input[name="sub"]').val(),
			  	'tema': $('input[name="tema"]').val(),
			  	'redirect': $('input[name="redirect"]').val()
			  },
			  success: function(data){
			  	console.log(data);
			  }
			});

	});

	// $('.send-main-submit button[type="submit"]').click(function(e){
	// 	e.preventDefault();

	// 	$.ajax({
	// 		  type: 'POST',
	// 		  url: '/admin/users/send-message',
	// 		  data: {
	// 		  	'email': $('input[name="email"]').val(),
	// 		  	'username': $('input[name="username"]').val(),
	// 		  	'message': $('textarea[name="message"]').val(),
	// 		  	'sub': $('input[name="sub"]').val(),
	// 		  	'tema': $('input[name="tema"]').val()
	// 		  },
	// 		  success: function(data){
	// 		  	console.log(data);
	// 		  }
	// 		});

	// });

	if($('select.select-add-new-fields').val() == 'text' || $('select.select-add-new-fields').val() == 'number')
		$('.add-new-fields').css({'display': 'none'});

	$('select.select-add-new-fields').click(function(){
		var select_value = $(this).val();
		if(select_value == 'text' || select_value == 'number'){
			$('.add-new-fields').css({'display': 'none'});
		}else{
			$('.add-new-fields').css({'display': 'block'});
		}
		
	});

	$('.dropdown-fields').click(function(e){
		e.preventDefault();

		$('.dropdown-fields-wrap').append('<div class="row"><div class="col-md-10"><div class="form-group text-left"><label for="exampleInputName1">Название</label><input type="text" name="Fields[sub_field][]" class="form-control" id="exampleInputName1"></div></div><div class="col-md-1"><span class="glyphicon glyphicon-move" aria-hidden="true"></span></div><div class="col-md-1"><a href="#" class="dropdown-field-delete btn-sm btn-danger">X</a></div></div>');
	});

	$('html').on('click','.dropdown-field-delete', function (e) {                               
        if(confirm('Вы действительно хотите удалить этот елемент?')){
			$(this).parent().parent().remove();
		}
		e.preventDefault();   
    });

    $current_click_fields = $('.dropdown-category-field select').val();

    $('html').on('click', '.dropdown-category-field select', function(){

    	var num = $(this).val();
    	var this_ads_id = $(this).parent().data('id');

    	if($current_click_fields != num){
    		$.ajax({
			  type: 'POST',
			  url: '/admin/ads/add-fields',
			  data: {
			  	'category_id': num,
			  	'ads_id' : this_ads_id
			  },
			  success: function(data){
			  
			  	$('.inputs_js').remove();
			  	$('.insert_fields').append(data);
			  }
			});
    	}

    	$current_click_fields = num;
    });

	$('select[name="user_status"]').change(function(){
     	$.ajax({
			  type: 'POST',
			  url: '/admin/users/set-status',
			  data: {
			  	'user_id': $(this).data('id'),
			  	'status' : $(this).val()
			  },
			  success: function(data){
			  	console.log(data);
			  }
			});
	});

	var el = document.getElementById('drag');

	if(el != undefined){
		var sortable = Sortable.create(el,{
			handle: '.glyphicon-move',
	  		animation: 150
		});
	}


	$('.add-balance-user').click(function(e){
		e.preventDefault();

		console.log('1');
	});

	$('.add-balance-user').magnificPopup({
		type: 'inline',
		fixedContentPos: false,
		fixedBgPos: true,
		overflowY: 'auto',
		closeBtnInside: true,
		preloader: false,
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-zoom-in'
	});

	$('.add-balance-user').each(function(i){
		$(this).attr('href', '#ssmal_popup' + i);
		$(this).next().attr('id','ssmal_popup' + i);
	});

	$(document).on('click', '.send-send-main-submit', function(e){
		e.preventDefault();


		$.ajax({
			  type: 'POST',
			  url: '/admin/users/set-balance',
			  data: { 'data': $(this).parent().parent().serialize() },
			  success: function(data){
			  		$('.add-balance-user').magnificPopup('close');
			  }
			});

	});



	$(document).on('click', '.send-send-send-main-submit', function(e){
		e.preventDefault();


		$.ajax({
			  type: 'POST',
			  url: '/admin/messages/send',
			  data: { 'data': $(this).parent().parent().serialize() },
			  success: function(data){
			  		$('.add-balance-user').magnificPopup('close');
			  }
			});

	});



	$(document).on('click', '.price-send', function(e){
		e.preventDefault();
		var data = $(this).parent().parent().serialize();
		$.ajax({
			  type: 'POST',
			  url: '/admin/users/price/',
			  data: { 'data': data },
			  success: function(data){
			  	console.log(data);
			  		$('.add-balance-user').magnificPopup('close');
			  }
			});

	});

	$(document).on('click', '.price-send-stock', function(e){
		e.preventDefault();
		var data = $(this).parent().parent().serialize();
		console.log(data);
		$.ajax({
			  type: 'POST',
			  url: '/admin/users/stock/',
			  data: { 'data': data },
			  success: function(data){
			  	console.log(data);
			  		$('.add-balance-user').magnificPopup('close');
			  }
			});

	});


	$(document).on('click', '.stock-del', function(e){
		e.preventDefault();
		console.log('data)');
		var id = $(this).attr('data-stock-id');
		$.ajax({
			  type: 'POST',
			  url: '/admin/users/stockdel/',
			  data: { 'id': id },
			  success: function(data){
			  		$('.add-balance-user').magnificPopup('close');
			  }
			});

	});

	$("[name='my-checkbox']").bootstrapSwitch({
		onText: 'Вкл',
		offText: 'Выкл'
	});
	$(".boot-checkbox").bootstrapSwitch({
		size: 'mini',
		onText: 'Вкл',
		offText: 'Выкл'
	});

	$(document).on('switchChange.bootstrapSwitch', "[name='my-checkbox']", function(e){
		var status = e.target.checked;
		$.ajax({
			  type: 'POST',
			  url: '/admin/settings/save/',
			  data: { 'status': status },
			  success: function(data){
			  	console.log(data);
			  }
			});

	});

	$(document).on('click', '.del-img-acc', function(){
		var img_id = $(this).data('id');
		var model_id = $(this).data('model');
		$.post( "/myaccount/deleteimgg/", { img_id:  img_id, model_id: model_id} , function() {
			$('.wrap-user-ac-img').remove();
		});
	});

	$(document).on('click', '.del-img-acc-ag', function(){
		var img_id = $(this).data('id');
		var model_id = $(this).data('model');
		$.post( "/admin/magazine/deleteimgg-ag/", { model_id: model_id} , function() {
			$('.wrap-user-ac-img-ag').remove();
		});
	});

	$(document).on('click', '.del-img-accc', function(){
		var img_id = $(this).data('id');
		var model_id = $(this).data('model');
		$.post( "/myaccount/deleteimggg/", { img_id:  img_id, model_id: model_id} , function() {
			$('.wrap-user-ac-img').remove();
		});
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
	

	$(document).on('click', 'a[data-drop-user-ads]', function(e){
		e.preventDefault();
		
		var _this = $(this);
		var _id = $(this).data('drop-id');
		var _csrf = $('meta[name="csrf-token"]').attr("content");
		$.post('/admin/ads/delete-ajax/', {id: _id, _csrf : _csrf}, function(){
			$(_this).parent().parent().remove();
		});
	});
	
});