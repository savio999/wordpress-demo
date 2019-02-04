(function($){
    $('document').ready(function(){
        $('.csv_download').on('click',function(e){
            var link = url_link.site_url;
            var btn_id = $(this).attr('id');
            var arr = btn_id.indexOf('_');
            var id = btn_id.substr(0,arr);
            
            link = link+"/427-2?id="+id;
            window.location.href = link;
            e.stopPropagation();
	    e.preventDefault();
	});
    });
    
    $("#upload_btn").on('click',function(){
        $('#csv_upload').trigger('click');
    });
    
    /*on change and click*/
    $('#csv_upload').on('change',function(){
        var url = url_link.admin_ajax_url;
        var formData = new FormData();
        var file = $('#csv_upload').prop('files')[0];
        formData.append('file',file);
        formData.append('action','upload_csv');
        $.ajax({
            url:url,
            type:'POST',
            data:formData,
            contentType:false,
            processData:false,
            cache:false,
            success:function(data){
                $('#output').html(data);
            }
        });
    }).on('click',function(){
        $(this).val('');
    });
}(jQuery));


