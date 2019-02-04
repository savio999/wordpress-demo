$('document').ready(function() {
	$("#displayMoreFilter").waypoint(function(direction){
		if(direction == 'down') {
			loadPostsFilter();
		}		
	},{
		offset:'bottom-in-view' 
	});
});

function loadPostsFilter() {
	$("#spinner").show();
	var term_id = $("#term_id").val();
	var page = $("#current_page").val();
	var products = new Array();	
	$("input:checked").each(function() {
	   products.push($(this).val());
	});
	page++;
	var admin_url = loadMoreObj.admin_ajax_url;
	$.ajax({
		url: admin_url,
		type:'post',
		data:{
			action: 'pro_filter',
			page: page, 
			products: products,
			repeat:true,
			term_id:term_id,
		},
		dataType:'json',
		success: function(data) {
			if($.trim(data) == '') {
				
			} else {
				$('#appended_div_posts').append(data.result);
				$("#current_page").val(page);
				updateTaxonomyFilter();
			}
			Waypoint.refreshAll();
			$("#spinner").hide();
		}
	});
}

	function updateTaxonomyFilter() {
		var admin_url = loadMoreObj.admin_ajax_url;
		var page = $("#current_page").val();
		var term_taxonomy = $("#term").val();
		var term_arr = $("#term_arr").val();
		var products = new Array();
		$("input:checked").each(function() {
	   		products.push($(this).val());
		});
		var taxonomy_active = $("[name='taxonomy_active']").val();
		$.ajax({
		url: admin_url,
		type:'post',
		dataType:'html',
		data:{
			action: 'update_filter',
			selected: products,
			page: page,
			term:term_taxonomy,
			taxonomy_active:taxonomy_active,
			term_arr: term_arr
		},
		success: function(data) {
				$('#checkboxes_to_replace').html(data);
		}			
		});
	}

	function check_click(){
		var admin_url = loadMoreObj.admin_ajax_url ;
		var term_id = $("#term_id").val();
		var selected_ids = new Array();	
		var taxonomy_active = $("[name='taxonomy_active']").val();

		$("input:checked").each(function() {
		   selected_ids.push($(this).val());
		});

        var str = "products="+selected_ids+"&term_id="+term_id+"&action=pro_filter&tax_active="+taxonomy_active;

		$.ajax({
			url: admin_url, 
			data: str,
			type:'POST',
			dataType:'json',
			success:function(data){
				$('#posts').html(data.result);
			}
		});
	}

function test_api(){
$.ajax({
	url:'http://localhost/wp_demo/wp-json/wp/v2/users/',
	method:'POST',
	beforeSend: function ( xhr ) {
        xhr.setRequestHeader( 'X-WP-Nonce', prodfiltr.nonce );
    },
	success:function(data){
		console.log(data);
	}
})
}

function postCreate(){

}