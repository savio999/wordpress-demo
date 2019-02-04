var last=0;

$(document).ready(function() {
    jQuery("body").addClass("loaded");
    //store default price on hidden deiv
    $('#store_def_price').html($("#left_div").html());

    /*Fired when the user selects all the required dropdowns / attributes and a final variation is selected */
    $( ".variations_form" ).on( "show_variation", function ( event, variation ) { 
        $(".price").html($('.single_variation').html());
        $('.stock_field').text($('.stock').html());
   } );

    // Fires whenever variation selects are changed 
    $( ".variations_form" ).on( "woocommerce_variation_select_change", function () { 
        $("#left_div").html($('#store_def_price').html());
        $('.stock_field').html('');
   } );   

    $("#woo_social_btn").on('click',function(){
        var no = $("#hidden_social_no").val();
        if(no == 0) {
            $("#woo_social_div").css({'display':'block'});
            $("#hidden_social_no").val(1);
        } else if(no == 1) {
            $("#woo_social_div").css({'display':'none'});
            $("#hidden_social_no").val(0);
        }
        
    });

    $("#woo_wishlist_btn").on('click',function(){
        var no = $("#hidden_wishlist_no").val();
        if(no == 0) {
            $("#woo_wishlist_div").css({'display':'block'});
            $("#hidden_wishlist_no").val(1);
        } else if(no == 1) {
            $("#woo_wishlist_div").css({'display':'none'});
            $("#hidden_wishlist_no").val(0);
        }
        
    });


});

function add_cart_quantity(qty){
 var $inputQty = $(qty).parent().find('input.qty'); 
        var val = parseInt($inputQty.val()); 
        var step = $inputQty.attr('step');
        var max = $inputQty.attr('max'); 
        step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
        if(max == ''|| val < max) {
            $inputQty.val(val + step).change();
            $("[name='update_cart']").trigger('click');
        }
}

function minus_cart_quantity(qty){
     var $inputQty = $(qty).parent().find('input.qty'); 
     var val = parseInt($inputQty.val()); 
     var step = $inputQty.attr('step'); 
     var min = $inputQty.attr('min'); 
     min = 'undefined' !== typeof(min) ? parseInt(min) : 1; 
     if(min == 0 ) {
        min = 1;
     }
     step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
     if (val > min) {
        $inputQty.val(val - step).change(); 
        $("[name='update_cart']").trigger('click'); 
     } 
}

$('document').ready(function() {
    $("#displayMoreShopProd").waypoint(function(direction){ 
        if(direction == 'down' && last==0) {
            loadWProFilter();
        }       
    },{
        offset:'bottom-in-view' 
    });
});

function loadWProFilter() {
    $("#spinner").show();
    var page = $("#current_page").val();
    page++;
    var admin_url = varObj.admin_ajax_url;   
    var sortby = $("[name='sort_by']").val();         
    var clean_div = $("#clean_div").val();
    var taxonomy = $('#taxonomy').val();
    var term = $('#term').val();
    var is_shop = $('#is_shop').val();
    var filterOptions = {};
    filterOptions.brandss = [];
    filterOptions.product_cat = [];

    $("[name='brands[]']:checked").each(function() {
         filterOptions.brandss.push($(this).val());
    });

    $("[name='categories[]']:checked").each(function() {
         filterOptions.product_cat.push($(this).val());
    });

    var options = {};
   options['taxonomy'] = taxonomy;
   options['term'] = term;
   options['page'] = page;  
   options['sort'] = sortby; 
   options['filterOptions']= filterOptions; 
   options['is_shop'] = is_shop; 
    $.ajax({
        url: admin_url,
        type:'post',
        data:{
            action: 'get_pro',
            options: JSON.stringify(options)
        },
        dataType:'json',
        success: function(data) {
            if(clean_div == 1) { 
                $('.columns-4').html(data.result);   
                $("#clean_div").val(0);  
            }else{
                $('.columns-4').append(data.result);
            }  

            $("#current_page").val(page);
            if(data.last == 1) {
                last = 1;
            }  

            $('#filters').html(data.filters);      
            Waypoint.refreshAll();
            $("#spinner").hide();
        }
    });
}

function filterProducts(){ 
    $("#current_page").val(0);
    $("#clean_div").val(1);
    last = 0;
    loadWProFilter();
}


