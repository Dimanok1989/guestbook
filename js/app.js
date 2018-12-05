function sendGuestBook() {
	var data = $('#guestBook').serializeArray();
	var error = false;
	$.each(data, function(ind, valu) {
		if (valu.name != "site") {
			if (valu.value == "" || valu.value == null) {
				error = true;
			}
		}
	});
	
	if (error) alert('Заполнены не все поля\nПоля, отмеченные звездочкой * обязательны к заполнению');
	else {
		$('#guestBook input, #guestBook textarea').attr('disabled', true);
		$.ajax({
			url: "/newmsg", 
			type: "POST", 
			data: data,
			dataType: 'json',
			success: function(data, textstatus) {
				$('#infoCont').empty();
				if (data.success == "ok") {
					$('.first').remove();
					$('.lastMsg').prepend('<div class="temp" id="temp'+data.id+'"></div>')
					$('#temp'+data.id).load('/html/guestBook_rowMessage.html', function() {
						$('#temp'+data.id+' .msg').attr('id', 'msg'+data.id);
						$('#temp'+data.id+' .msg .time').text(data.time);
						$('#temp'+data.id+' .msg .name').text(data.name);
						$('#temp'+data.id+' .msg .email a').attr('href', 'mailto:'+data.email).text(data.email);
						$('#temp'+data.id+' .msg .homepage a').attr('href', data.site).text(data.site);
						$('#temp'+data.id+' .msg .text').text(data.msg);
						$('#guestBook')[0].reset();
					});
				}
				else if (data.success == "error") {
					$('#infoCont').append('<div id="error">'+data.error+'</div>');
				}
				$('form input, form textarea').attr('disabled', false);
			},
			error: function (jqXHR, exception) {
				$('form input, form textarea').attr('disabled', false);
			}
		});
	}
}

$(function() {
	var sort = $.cookie('sort');
	if (sort) {
		var type = sort.split('-');
		var style = "up";
		if (type[1] == "down") style = "down";
		$('span#'+type[0]).data('id', type[1]).addClass(style);
	}
	else $('span#date').data('id', 'up').addClass('down');

	$('.sortButt').on('click', function() {
		$('span.sortButt').removeClass('up').removeClass('down');
		var now = $(this).data('id');
		var id = $(this).attr('id');
		style = "up";
		sort = $.cookie('sort');
		if (sort) {
			var before = '';
			var type = sort.split('-');
			if (type[0] != id) {
				before = now;
			}
			else {
				if (now == type[1] && now == "up") before = "down";
				else if (now == type[1] && now == "down") before = "up";
				else before = now;
			}
			if (type[1] == "down") style = "down";
		}
		else {
			before = now;
			if (now == "down") style = "down";		
		}
		$('span#'+id).data('id', now).addClass(now).data('id', before);
		
		$.cookie('sort', id+"-"+before, {
			expires: 30
		});
		location.reload();
		console.log(now+' '+before);
	});
});
