<?php  
  /*
   Plugin Name: PDF plugin
   Text Domain: ppdf
  */
  
/*add pdf column title*/  
function ppdf_add_pdf_column( $columns ) {
    $columns['pdf_column'] = 'Order Details';
    return $columns;
}

add_filter( 'manage_edit-shop_order_columns', 'ppdf_add_pdf_column' );

/*for each row*/
add_action( 'manage_shop_order_posts_custom_column' , 'ppdf_custom_orders_list_column_content');
function ppdf_custom_orders_list_column_content( $column )
{
    switch ( $column )
    {
        case 'pdf_column' :
			global $the_order;
			$order_id = $the_order->get_id();
        	echo "<button class='print_pdf' id='".$order_id."_orderID'>View Details</button>";
        	break;
    }
} 

/*include js and css*/ 
function ppdf_load_scripts_styles(){
	wp_enqueue_script('ppdf_load_pdfmke', plugin_dir_url(__FILE__)."includes/pdfmake.min.js", array('jquery'), false, true);  
	wp_enqueue_script('ppdf_load_vfs_fonts', plugin_dir_url(__FILE__)."includes/vfs_fonts.js", array('jquery'), false, true);  
	wp_enqueue_script('ppdf_load_script', plugin_dir_url(__FILE__)."script.js", array('jquery'), false, true);
	
	$loadArray = array('admin_ajax_url' => admin_url('admin-ajax.php'));
	wp_localize_script('ppdf_load_script','ppdf_load_ajax_url',$loadArray);
}

add_action('admin_enqueue_scripts', 'ppdf_load_scripts_styles');

add_action('wp_ajax_order_data','getOrderData');
add_action('wp_ajax_nopriv_order_data','getOrderData');

function getOrderData(){
	$output = array();
	$orderID = $_POST['orderID'];
	
	/*seller info*/
	global $wpdb;
	$results = $wpdb->get_results("SELECT distinct(post_author) from wp_posts JOIN wp_postmeta v1 on (wp_posts.ID = v1.post_id) where ID= ".$orderID." order by ID");
	$seller_id = $results[0] -> post_author;
	$seller_info = get_user_by('ID',$seller_id);
	$seller_name = $seller_info->data->display_name;
	if(empty($seller_name)){
		$seller_name = '';
	}
	$output['seller']['name']=$seller_name;
	
	$address = get_option('woocommerce_store_address');	
	if(empty($address)){
		$address= '';
	}
	$output['seller']['address']=$address;
	
	$pincode = get_option('woocommerce_store_postcode');
	if(empty($pincode)){
		$pincode= '';
	}	
	$output['seller']['pincode']=$pincode;
	
	
	$cou =WC_Countries::get_base_country();
	$country = WC()->countries->countries[ $cou ];
	if(empty($country)){
		$country = '';
	}
	$output['seller']['country'] = $country;
	$order = wc_get_order($orderID);
        $items = $order->get_items();
        $count = 0;
        foreach($items as $item){
            //$product_id = $item->get_product_id();
            $product = $item->get_product();
            $output['product'][$count]['name'] = $product->get_name(); // Get the product name
            $output['product'][$count]['qty'] = $item->get_quantity(); // Get the item quantity
            $output['product'][$count]['total'] = $item->get_total(); 
            $count++;
        }
        //echo "<pre>";
	//print_r($order);
        
        $output['purchaser']['name']=$order->data['billing']['first_name']." ".$order->data['billing']['last_name'];
        if(empty($output['purchaser']['name'])){
            $output['purchaser']['name']='';
        }
        $output['purchaser']['address']=$order->data['billing']['address_1'];
        if(empty($output['purchaser']['address'])){
            $output['purchaser']['address']='';
        }
        $output['purchaser']['postcode']=$order->data['billing']['postcode'];
        if(empty($output['purchaser']['postcode'])){
            $output['purchaser']['postcode']='';
        }
        $pcountry=$order->data['billing']['country'];
        if(empty($pcountry)){
            $output['purchaser']['country']='';
        }else{
            $pcoun = WC()->countries->countries[ $pcountry ];
            $output['purchaser']['country']=$pcoun;
        }
	echo json_encode($output);
	exit;
}
  
?>  