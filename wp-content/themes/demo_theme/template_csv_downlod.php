<?php
/*
 * Template Name:Download CSV
 */

$output_filename="order_details.csv";
$order_id = filter_input(INPUT_GET, 'id');
$title = "Order Details";
$output_handle = @fopen( 'php://output', 'w' );
$delimiter = ';';

header('Content-type: application/csv'); 
header( 'Content-Disposition: attachment; filename=' . $output_filename );

/*title*/
$ctitle[] = $title;
fputcsv($output_handle, $ctitle, $delimiter);//inserts new row

$seller_heading[] = "Seller Name";
$seller_heading[] = "Seller Address";
$seller_heading[] = "Seller Postcode";
$seller_heading[] = "Seller Country";
fputcsv($output_handle, [], $delimiter);
fputcsv($output_handle, $seller_heading, $delimiter);

/*seller*/

global $wpdb;
$results = $wpdb->get_results("SELECT distinct(post_author) from wp_posts JOIN wp_postmeta v1 on (wp_posts.ID = v1.post_id) where ID= ".$order_id." order by ID");
$seller_id = $results[0] -> post_author;
$seller_info = get_user_by('ID',$seller_id);
$seller_name = $seller_info->data->display_name;
if(empty($seller_name)){
	$seller_name = '';
}
$seller_values[] = $seller_name;

	
$address = get_option('woocommerce_store_address');	
if(empty($address)){
    $address= '';
}
$seller_values[] = $address;
	
$pincode = get_option('woocommerce_store_postcode');
if(empty($pincode)){
    $pincode= '';
}
$seller_values[] = $pincode;

$cou = new WC_Countries();
$country_code = $cou->get_base_country();
$country = WC()->countries->countries[ $country_code ];
if(empty($country)){
	$country = '';
}
$seller_values[] = $country;
fputcsv($output_handle, $seller_values, $delimiter);

/*order items*/
fputcsv($output_handle, [], $delimiter);
$order_item[]="Item Name";
$order_item[]="Qty";
$order_item[]="Total";

fputcsv($output_handle, $order_item, $delimiter);
$order = wc_get_order($order_id);
$items = $order->get_items();
foreach($items as $item){
$product = $item->get_product();
    $productarr=[];
    $productarr['name'] = $product->get_name(); // Get the product name
    $productarr['qty'] = $item->get_quantity(); // Get the item quantity
    $productarr['total'] = $item->get_total(); 
    fputcsv($output_handle, $productarr, $delimiter);        
}

/*purchse details*/  
fputcsv($output_handle, [], $delimiter);
$pur_heading[] = "Purchaser Name";
$pur_heading[] = "Purchaser Address";
$pur_heading[] = "Purchaser Postcode";
$pur_heading[] = "Purchaser Country";
fputcsv($output_handle, $pur_heading, $delimiter);

$pur['name']=$order->get_billing_first_name()." ".$order->get_billing_last_name();
if(empty($pur['name'])){
    $$pur['name']='';
}

$pur['address']=$order->get_billing_address_1();
if(empty($pur['address'])){
    $pur['address']='';
}
        $pur['postcode']=$order->get_billing_postcode();
        if(empty($pur['postcode'])){
            $pur['postcode']='';
        }
        $pcountry=$order->get_billing_country();
        if(empty($pcountry)){
            $pur['country']='';
        }else{
            $pcoun = WC()->countries->countries[ $pcountry ];
            $pur['country']=$pcoun;
        }
fputcsv($output_handle, $pur, $delimiter);



fclose($output_handle);
die();
?>
