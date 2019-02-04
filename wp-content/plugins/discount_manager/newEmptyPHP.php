
/*admin menu*/
add_action('admin_menu','disc_mang_menu');
    
    function disc_mang_menu(){
        $discount_page = add_menu_page('User Role Discount','User Role Discount Manager','manage_options','delivery_manager','dicount_menu_render','',7);
        $category_role_page = add_menu_page('Category Role Discount','Category Role Discount Manager','manage_options','category_role_manager','category_role_render','',8);
        
        add_action('load-'.$discount_page,'disc_mang_add_js_css');
        add_action('load-'.$category_role_page,'disc_mang_add_js_css');
    }
    
    function dicount_menu_render(){
        global $title,  $wp_roles, $wpdb;
        $roles = $wp_roles->get_names();
        $table = $wpdb->prefix. "options";
        $query = $wpdb->get_row("select * from ".$table." where option_name = 'roles_discount'");
        $roles_encoded = $query->option_value;
        $roles_db = json_decode($roles_encoded,true);
        ?>
            <h1 style="text-align:center;"><?= $title ?></h1>
            <input type="hidden" id="page" value="role_page"/>
            <div id="continer_div">
                <div style="float:right">
                    <button class="discbtn" id="role_add">+</button>
                </div>
                <div class="clearfix"></div>
                <div class="role_list">
                    <?php foreach($roles_db as $roleindb => $discount){
                            if($discount > 0){
                        ?>
                        <div class="row">
                        <div class="role_div">
                            <select class="select" name="user_role">
                                <option value="-1">Select role</option>
                                <?php
                                    foreach($roles as $role){
                                        $lrole = strtolower($role);
                                        if(($lrole == $roleindb) || ($roles_db[$lrole] == 0)){
                                     ?>
                                        <option value="<?= $role ?>" <?php echo (strtolower($role) == $roleindb)? "selected":''; ?>><?= $role ?></option>
                                     <?php
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="role_div">
                            <input type="number" name="discount" class="discount_input" value="<?= $discount ?>"/>
                        </div>
                        <div class="role_div">
                            <button title="create" style="inline-block" class="btn_save">Save</button>
                            <button title="delete" style="inline-block" class="btn_delete">Delete</button>
                        </div>
                        <script>
                            var $ = jQuery.noConflict();
                            $('document').ready(function(){
                                $('.btn_save').on('click',function(){
                                    var divData = $(this).parent().parent();
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
                                    
                                    save_to_database(role_val, discount_val);
                                });
                                
                                $('.btn_delete').on('click',function(){
                                   var ans = confirm('Are you sure you want to delete?');
                                   if(ans){
                                    var divData = $(this).parent().parent();
                                    var role = divData.find('.select').val();
                                    deleteFromDatabase(role, divData);
                                   }
                                });
                            });
                        </script>
                    </div>
                    <?php
                        }                        
                    }  
                     foreach($roles_db as $roleindb => $discount){
                    ?>    
                    <div class="hidden_row" style="display:none;">
                     <div class="row">
                        <div class="role_div">
                            <select class="select" name="user_role">
                                <option value="-1">Select role</option>
                                <?php
                                    foreach($roles as $role){
                                        $lrole = strtolower($role);
                                        if($roles_db[$lrole] == 0){
                                     ?>
                                        <option value="<?= $role ?>"><?= $role ?></option>
                                     <?php
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="role_div">
                            <input type="number" name="discount" class="discount_input"/>
                        </div>
                        <div class="role_div">
                            <button title="create" style="inline-block" class="btn_save">Save</button>
                            <button title="delete" style="inline-block" class="btn_delete">Delete</button>
                        </div>
                        <script>
                            var $ = jQuery.noConflict();
                            $('document').ready(function(){
                                $('.btn_save').on('click',function(){
                                    var divData = $(this).parent().parent();
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
                                    
                                    save_to_database(role_val, discount_val);
                                });
                                
                                $('.btn_delete').on('click',function(){
                                   var ans = confirm('Are you sure you want to delete?');
                                   if(ans){
                                    var divData = $(this).parent().parent();
                                    var role = divData.find('.select').val();
                                    deleteFromDatabase(role, divData);
                                   }
                                });
                            });
                        </script>
                    </div>
                </div>
                </div>
            </div>
        <?php
        }        
    }
    
/*delivery manager page js*/
function disc_mang_add_js_css(){
    add_action('admin_enqueue_scripts','load_admin_disc_scripts');
}    

function load_admin_disc_scripts(){
    wp_enqueue_script('disc_mang_after_load', plugin_dir_url(__FILE__).'discount_manager.js',false,'',TRUE);
    wp_localize_script('disc_mang_after_load', 'disc_obj' ,array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_style('disc_mang_css', plugin_dir_url(__FILE__).'discount_manager.css');
}

/*store in database*/
add_action('wp_ajax_role_dis', 'save_role_dis_db');
add_action('wp_ajax_nopriv_role_dis', 'save_role_dis_db');    

function save_role_dis_db(){
   global $wpdb;
   $table = $wpdb->prefix . "options";
   $role = $_POST['role'];
   $discount = $_POST['discount'];
   $id = 0;
   
   if(!empty($role) && !empty($discount)){
       /*fetch array  from database*/
       $results = $wpdb->get_row("select * from ".$table." where option_name = 'roles_discount'");
       $row_id = $results->option_id;
       $roles_encoded = $results->option_value;
       $roles=json_decode($roles_encoded,true);
       
       $role_key = strtolower($role);

       if(array_key_exists($role_key, $roles)){
           $roles[$role_key]=$discount;
       }
       
       $result = $wpdb->update(
               $table,
               array(
                    'option_name'=>'roles_discount',
                    'option_value'=>json_encode($roles)
                   ),
               array(
                   'option_id' =>$row_id
               ),
               array('%s','%s'),
               array('%d'));           
   }
   echo $result;
   exit;
}

/*remove role discount from database*/
add_action('wp_ajax_remove_discount_role', 'rem_role_dis_db');
add_action('wp_ajax_nopriv_remove_discount_role', 'rem_role_dis_db');    

function rem_role_dis_db(){
   global $wpdb;
   $table = $wpdb->prefix . "options";
   $role = $_POST['role'];
   $id = 0;
   
   if(!empty($role)){
       /*fetch array  from database*/
       $results = $wpdb->get_row("select * from ".$table." where option_name = 'roles_discount'");
       $row_id = $results->option_id;
       $roles_encoded = $results->option_value;
       $roles=json_decode($roles_encoded,true);
       
       $role_key = strtolower($role);

       if(array_key_exists($role_key, $roles)){
           $roles[$role_key]=0;
       }
       
       $result = $wpdb->update(
               $table,
               array(
                    'option_name'=>'roles_discount',
                    'option_value'=>json_encode($roles)
                   ),
               array(
                   'option_id' =>$row_id
               ),
               array('%s','%s'),
               array('%d'));           
   }
   echo $result;
   exit;
}


/*apply user role discount on products(for all pages other than cart and checkout - simple product)*/
function calculate_user_role_price($price){ 
        global $wpdb;
        $table = $wpdb->prefix."options";

        $user = wp_get_current_user();
        $user_roles = $user->roles;
        $role = '';
        foreach($user_roles as $urole){
            $role = $urole;
        }


        $dis_arr = $wpdb->get_var("select option_value from ".$table." where option_name = 'roles_discount'");
        $roles = json_decode($dis_arr,true);

        $disc_percent = 0;

        if(array_key_exists($role, $roles)){
            $disc_percent = $roles[$role];
        }    

        $disc_price = 0;
        if($disc_percent > 0){
            $disc_price = (float)$price * (float)($disc_percent / 100);
        }  

        $rprice = (float)$price - $disc_price;
        return $disc_price;
}

add_filter('woocommerce_product_get_price','apply_role_discount');

function apply_role_discount($price){
    if(is_shop() || is_product() || is_product_category() || is_product_tag()){
        global $product;
       $id = $product->get_id();
       $categories = get_the_terms($id, 'product_cat');
       foreach($categories as $category){
           $term_id =  $category->term_id;
           add_option('cat_discount_'.$term_id,50);
       }
        $price = $product->get_regular_price();
       if($product->is_on_sale()){
            $price = $product->get_sale_price();
        }
        return calculate_user_role_price($price);        
    } else{
        return $price;
    }  
} 

/*apply user role discount on cart and checkout(simple product)*/
add_filter('woocommerce_add_cart_item', 'setuserroledisc',10,2);

function setuserroledisc($cart_data, $cart_item_key){
    $price = $cart_data['data']->get_price(); 
    $new_price = calculate_user_role_price($price); 
    $cart_data['data']->set_price($new_price);
    $cart_data['new_price']= $new_price;
    return $cart_data;
}

add_filter( 'woocommerce_get_cart_item_from_session', 'set_custom_cart_item_prices_from_session', 20, 3 );
function set_custom_cart_item_prices_from_session( $session_data, $values, $key ) {
    if ( ! isset( $session_data['new_price'] ) || empty ( $session_data['new_price'] ) )
        return $session_data;

    // Get the new calculated price and update cart session item price
    $session_data['data']->set_price( $session_data['new_price'] );

    return $session_data;
}

/*apply user role discount on products(for all pages other than cart and checkout - variable product)*/
add_filter('woocommerce_product_variation_get_price','apply_variation_role_discount',20,2);

function apply_variation_role_discount($price, $variation){       
        $price = $variation->get_regular_price();        
       if($variation->is_on_sale()){
            $price = $variation->get_sale_price();
            
        } 
        $price = calculate_user_role_price($price); 
        return $price;
    
}

/*store category discount in database*/
add_action('wp_ajax_cat_dis', 'save_cat_dis_db');
add_action('wp_ajax_nopriv_cat_dis', 'save_cat_dis_db');    

function save_cat_dis_db(){
   global $wpdb,$product;
   $table = $wpdb->prefix . "options";
   $catid = $_POST['catid'];
   $discount = $_POST['discount'];
   $type = $_POST['type'];
   $id = 0;
   /*$all_categories = get_terms('product_cat'); print_r($all_categories);
   $categories_disc = [];
   foreach($all_categories as $category){
       $categories_disc[$category->term_id]= 0; 
   }
   
  $wpdb->insert(
           $table,
           array(
               'option_name'=>'categories_discount',
               'option_value'=>json_encode($categories_disc)
           )
   );*/
   
  if(!empty($catid)){
       $row_id = '';
       $roles_encoded = '';
       $results = $wpdb->get_row("select * from ".$table." where option_name = 'categories_discount'");
       if(!empty($results)){
           $row_id = $results->option_id;
           $categories_encoded = $results->option_value;
           $categories=json_decode($categories_encoded,true);           

            if($type == 'insert'){ 
                if(array_key_exists($catid,$categories)){
                    $categories[$catid]=$discount;    
                }

               $result = $wpdb->update(
               $table,
               array(
                    'option_name'=>'categories_discount',
                    'option_value'=>json_encode($categories)
                   ),
               array(
                   'option_id' =>$row_id
               ));
            }elseif($type == 'delete'){
                if(array_key_exists($catid,$categories)){
                    $categories[$catid]=0;    
                }
               $result = $wpdb->update(
               $table,
               array(
                    'option_name'=>'categories_discount',
                    'option_value'=>json_encode($categories)
                   ),
               array(
                   'option_id' =>$row_id
               ));
            }   
            echo $result;
       }
   }   
   exit;
}

    function category_role_render(){
        global $title, $wpdb;
        $categories = get_terms('product_cat');
        $table = $wpdb->prefix. "options";
        $query = $wpdb->get_row("select * from ".$table." where option_name = 'categories_discount'");
        $categories_encoded = $query->option_value;
        $categories_db = json_decode($categories_encoded,true);
        ?>
            <h1 style="text-align:center;"><?= $title ?></h1>
            <input type="hidden" id="page" value="category_page"/>
            <div id="continer_div">
                <div style="float:right">
                    <button class="catbtn" id="cat_add">+</button>
                </div>
                <div class="clearfix"></div>
                <div class="cat_list">
                    <?php 
                    
                    foreach($categories_db as $catdbid => $discount){
                        if($discount > 0){
                        ?>
                        <div class="row">
                            <div class="role_div">
                                <select class="select" name="category">
                                    <option value="-1">Select category</option>
                                    <?php
                                        foreach($categories as $category){
                                            $cat_id = $category->term_id;
                                            $cat_name = $category->name; 
                                                if($catdbid == $cat_id || $categories_db[$discount] == 0){
                                         ?>
                                                    <option value="<?= $cat_id ?>" <?php echo ($catdbid == $cat_id)? "selected":''; ?>><?= $category->name ?></option>
                                         <?php
                                                }
                                            }

                                    ?>
                                </select>
                            </div>
                            <div class="role_div">
                                <input type="number" name="discount" class="discount_input" value="<?= $discount ?>"/>
                            </div>
                        <div class="role_div">
                            <button title="create" style="inline-block" onclick="saveCategoryDiscount(this);">Save</button>
                            <button title="delete" style="inline-block" onclick="removeCategoryDiscount(this);">Delete</button>
                        </div>
                      </div>
                    <?php
                        }
                     } 
                     ?>
                </div>
                <div class="row">
                        <div class="role_div">
                            <select class="select" name="category">
                                <option value="-1">Select category</option>
                                    <?php
                                        foreach($categories as $category){
                                            $cat_id = $category->term_id;
                                            if($categories_db[$cat_id] == 0){
                                      ?>
                                            <option value="<?= $cat_id ?>"><?= $category->name ?></option>
                                      <?php
                                            }
                                        }
                                       ?>
                            </select>
                        </div>
                        <div class="role_div">
                            <input type="number" name="discount" class="discount_input"/>
                        </div>
                        <div class="role_div">
                            <button title="create" style="inline-block" onclick="saveCategoryDiscount(this);">Save</button>
                            <button title="delete" style="inline-block" onclick="removeCategoryDiscount(this);">Delete</button>
                        </div>
                </div>
                <div class="hidden_row"  style="display:none;"> 
                    <div class="row">
                        <div class="role_div">
                            <select class="select" name="category">
                                <option value="-1">Select category</option>
                                    <?php
                                        foreach($categories as $category){
                                            $cat_id = $category->term_id;
                                            if($categories_db[$cat_id] == 0){
                                      ?>
                                            <option value="<?= $cat_id ?>"><?= $category->name ?></option>
                                      <?php
                                            }
                                        }
                                       ?>
                            </select>
                        </div>
                        <div class="role_div">
                            <input type="number" name="discount" class="discount_input"/>
                        </div>
                        <div class="role_div">
                            <button title="create" style="inline-block" onclick="saveCategoryDiscount(this);">Save</button>
                            <button title="delete" style="inline-block" onclick="removeCategoryDiscount(this);">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
    }