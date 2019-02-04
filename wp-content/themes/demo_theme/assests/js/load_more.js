$('document').ready(function() {
	$("#displayMore").waypoint(function(direction){
		if(direction == 'down') {
			loadMorePosts();
		}		
	},{
		offset:'bottom-in-view' 
	});
});

function loadMorePosts() {
	$("#spinner").show();
	var page = $("#current_page").val();
	page++;
	//$("#btnLoadMore").attr('disabled','disabled');
	//$("#btnLoadMore").html("Please wait <span class='fa fa-spinner fa-spin'></span>");
	var admin_url = loadMoreObj.admin_ajax_url;
	$.ajax({
		url: admin_url,
		type:'post',
		data:{
			action: 'fetch',
			page: page, 
		},
		success: function(data) {
			if(data == '') {
				Waypoint.destroy();

			} else {
				$("#spinner").hide();
				$('#appended_div_posts').append(data);
				//$("#btnLoadMore").removeAttr('disabled');
				//$("#btnLoadMore").html("Load More");
				$("#current_page").val(page);
				Waypoint.refreshAll();
			}
			
		}
	});
}