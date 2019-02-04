<?php
add_filter('woocommerce_product_get_price','apply_simple_discount',10,2);

/*discount on simple products*/
function apply_simple_discount($price,$product){
    if($product->is_type('simple')){
            $id = $product->get_id();
            $role_percent = get_role_discount();
            $category_percent = get_category_discount($id);
            $discounted_price = apply_discount($role_percent, $category_percent, $price);
            return $discounted_price;
    }
}

/*discount on variable products*/
add_filter('woocommerce_product_variation_get_price','apply_variation_discount',10,2);
function apply_variation_discount($price, $product){
    $parent_id = $product->get_parent_id();
    $role_percent = get_role_discount();
    $category_percent = get_category_discount($parent_id);
    $discounted_price = apply_discount($role_percent, $category_percent, $price);
    return $discounted_price;
}

function get_role_discount(){
    global $wpdb;
    $table = $wpdb->prefix."options";
    $disc_role_percent = 0;
    

    $user = wp_get_current_user();
    if(!empty($user)){
        $user_roles = $user->roles;
 
        $dis_arr = $wpdb->get_var("select option_value from ".$table." where option_name = 'roles_discount'");
        $roles_db = json_decode($dis_arr,true);

        /*get maximum role discount*/
        foreach($roles_db as $role=>$discount ){
            if(empty($discount) || !(in_array($role, $user_roles))){
                
            }elseif($discount > $disc_role_percent){
                $disc_role_percent = $discount;
            }
        }

    }

    return $disc_role_percent;
}

function get_category_discount($product_id){
    global $wpdb;
    $disc_cat_percent = 0;
    $table = $wpdb->prefix . "options";
    $cat_names = array();
    $terms = get_the_terms($product_id, 'product_cat');  
    foreach($terms as $term){
        $cat_names[] = $term->name;
    }
    if(!empty($terms)){
        $cat_arr = $wpdb->get_var("select option_value from ".$table." where option_name = 'categories_discount'");
        $categories = json_decode($cat_arr,true);

        /*get maximum category discount*/
        foreach($categories as $category=>$discount ){
            if(empty($discount) || !(in_array($category, $cat_names))){
                
            }elseif($discount > $disc_cat_percent){
                $disc_cat_percent = $discount;
            }
        }
    }
    return $disc_cat_percent;
}

function apply_discount($role_percent, $category_percent, $price){
    global $wpdb;
    $table = $wpdb->prefix."options";
    
    /*fetch which discounts are applicable*/
    $enc_discount_options = $wpdb->get_var("select option_value from ".$table." where option_name = 'discount_options'");
    $dis_options = json_decode($enc_discount_options,true);

    /*apply discount according to conditions*/
    if($dis_options['user_roles'] == 1 && $dis_options['categories'] == 0 ){
        $final_discount = $role_percent;
    }elseif ($dis_options['user_roles'] == 0 && $dis_options['categories'] == 1 ) {
        $final_discount = $category_percent;
    }elseif ($dis_options['user_roles'] == 1 && $dis_options['categories'] == 1 ) {
        if($category_percent > $role_percent){
            $final_discount = $category_percent;
        }else{
            $final_discount = $role_percent;
        }        
    }       

   if($final_discount > 0){
        $disc_price = (float)$price * (float)($final_discount / 100);
    }  

    $rprice = (float)$price - (float)$disc_price;

    return $rprice;
}

/*display price and discount for simple product*/
/*add_filter( 'woocommerce_variable_price_html', 'display_variation_price', 10, 2 );

function display_variation_price( $price, $product ) {

    $min = 0;
    $max = 0;
    $count = 0;
    $variations = $product->get_available_variations();
    foreach($variations as $variation){
        
        $price_display = $variation['display_price'];
        if($count == 0){
            $min = $price_display;
            $max = $price_display;
        }
        
        if($price_display < $min){
            $min = $price_display;
        }
        
        if($price_display > $max){
            $max = $price_display;
        }
        $count++;
    }

    if($min == $max){
        echo  wc_price($min) . "<br/>";
    }else{
        echo  wc_price($min) . " - " . wc_price($max) . "<br/>";
    }
    
}


function display_price_discount($price, $product){
    if($product->is_type('simple')){
        echo wc_price($product->get_price())."<br/>";
        echo "<del>".wc_price($product->get_regular_price())."</del><br/>"; 
    }
}
add_filter('woocommerce_get_price_html','display_price_discount',10,2);*/

?>

