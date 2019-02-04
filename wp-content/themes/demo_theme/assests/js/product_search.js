//products search
$('document').ready(function(){
	$('#search_products').on('keyup',function() {
		var term = $(this).val();
		var url = varObj.admin_ajax_url;
		var tips = "";
		if($.trim(term).length != 0) {
		$.ajax({
			'url':url,
			'type':'POST',
			data:{
				'action':'searchpro',
				'term': term
			},
			dataType:'html',
			success:function(data){
				$('#tips_terms').html(data);
				$('#tips_terms').show();
			}
		});
		}
	});

	$('body').on('click', function(e) {	
		if (!$('#tips_terms').is(e.target) && $('.box').has(e.target).length === 0) {	
			$('#tips_terms').hide();
		}
	});


});