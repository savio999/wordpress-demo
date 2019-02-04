var $=jQuery.noConflict();

$('document').ready(function(){
    if($('#page').val() === 'role_page'){//roles page
            $('#role_add').on('click',function(){
                var div = $('.hidden_row').html();
                $('.role_list').append(div);
            });
    }

    if($('#page').val() === 'category_page'){// category page
        $('#cat_add').on('click',function(){
            var div = $('.hidden_row').html();
            $('.cat_list').append(div);
        });
    }
});

function save_role_disc(role_val, discount_val, type, divData){
    var url = disc_obj.ajax_url;
    $.ajax({
        url: url,
        type:'POST',
        data:{
            role:role_val,
            discount:discount_val,
            type:type,
            action:'role_dis'
        },
        success:function(data){
            if(data == 1 && type == 'insert') {
                alert('Inserted successfully');
                $(divData).find('.btn_delete').css({"display":"inline-block"});
                $(".select option[value='"+role_val+"']").remove();
                var formatted_role_text = formatText(role_val);
                $(divData).find('.select_div').html("<label>"+formatted_role_text+"</label>"+
                "<input type='hidden' class='select' value='"+role_val+"'>");
            }else if(data == 1 && type == 'delete'){
                var elem = $(divData).parent().parent();
                alert('Deleted successfully');
                $(elem).remove();
                $('.select').append("<option value='"+role_val+"'>"+formatText(role_val)+"</option>");
            }
        }
    });
}

function save_cat_disc(category, discount_val, type, divData){
    var url = disc_obj.ajax_url;
    $.ajax({
        url: url,
        type:'POST',
        data:{
            category:category,
            discount:discount_val,
            type:type,
            action:'cat_dis'        
        },
        success:function(data){
            if(data == 1 && type == 'insert') {
                alert('Inserted successfully');
                $(divData).find('.btn_delete').css({"display":"inline-block"});
                $(".select option[value='"+category+"']").remove();
                $(divData).find('.select_div').html("<label>"+category+"</label>"+
                "<input type='hidden' class='select' value='"+category+"'>");
                
            }else if(data == 1 && type == 'delete'){
                alert('Deleted successfully');
                $(divData).remove();                
                $('.select').append("<option value='"+category+"'>"+category+"</option>");
            }
            
        }
    });
}

    function saveCategoryDiscount(element){        
        var divData = $(element).parent().parent();
        var category = divData.find('.select');
        var discount = divData.find('.discount_input');
        var cat_val = category.val();
        var discount_val = discount.val();
        if(cat_val == -1){
          alert("Please select category");
        }
                                    
        if(discount_val == ''){
            alert('Please select discount');
        }
                             
        save_cat_disc(cat_val, discount_val,"insert",divData);
        }
                                
    function removeCategoryDiscount(element){
        var ans = confirm('Are you sure you want to delete?');
        if(ans){
            var divData = $(element).parent().parent();
            var category = divData.find('.select').val();
             save_cat_disc(category, 0, "delete", divData);
        }
    }

    function saveRoleDiscount(elem){
        var divData = $(elem).parent().parent();
        var role = divData.find('.select');
        var discount = divData.find('.discount_input');
        var role_val = role.val();
        var discount_val = discount.val();
        if(role_val == -1){
            alert("Please select role");
        }
                                    
        if(discount_val == ''){
            alert('Please select discount');
        }
        
        save_role_disc(role_val, discount_val, "insert", divData);
    }
    
    function removeRoleDiscount(element){
        var ans = confirm('Are you sure you want to delete?');
        if(ans){
            var divData = $(element).parent().parent();
            var role = divData.find('.select').val();
            save_role_disc(role, 0, "delete", element);
        }
    }
    
    function formatText(text){        
        var str = text.replace("_"," ");
        str = str.charAt(0).toUpperCase()+str.substr(1);
        return str;
    }
    
    
                                
 


