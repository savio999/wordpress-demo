<?php
/*
Plugin Name:  Discount Manager
Description:  Plugin to give discount to users based on their roles and product category.
Version:      1.0
Text Domain:  disc_mang
*/

/*insert 3 entries into table on plugin activation*/
register_activation_hook(__FILE__, 'insert_in_wp_options');

function insert_in_wp_options(){
    global $wpdb, $wp_roles;
    $table = $wpdb->prefix.'options';
    $categories = array();
    $roles = array();
    $options = array();
    
    /*insert categories row if not present*/
    $res = $wpdb->get_row("SELECT * FROM ".$table." WHERE option_name = 'categories_discount'");
    
    $cat_terms = get_terms('product_cat');
    
    
    foreach($cat_terms as $category){
        $name = $category->name;
        $categories[$name]= "0";        
    }
    
    if(empty($res)){
        $wpdb->insert($table,
            array(
                'option_name'   => 'categories_discount',
                'option_value'  => json_encode($categories)
            )
        );
    }
    
    /*insert roles row if not present*/
    $res = $wpdb->get_row("SELECT * FROM ".$table." WHERE option_name = 'roles_discount'");
    
    $user_roles = $wp_roles->roles;
    
    foreach($user_roles as $role => $info){
        $roles[$role]= "0";        
    }
    
    if(empty($res)){
        $wpdb->insert($table,
            array(
                'option_name'   => 'roles_discount',
                'option_value'  => json_encode($roles)
            )
        );
    }
    
    /*insert discount options if not present*/
    $res = $wpdb->get_row("SELECT * FROM ".$table." WHERE option_name = 'discount_options'");
    
    $disc_options = array(
        'categories' => "1",
        'user_roles' => "1"
    );
    
    if(empty($res)){
        $wpdb->insert($table,
            array(
                'option_name'   => 'discount_options',
                'option_value'  => json_encode($disc_options)
            )
        );
    }
    
}

/*hide submenu options according to conditions*/
function hide_options(){
    global $wpdb, $submenu;
    $table = $wpdb->prefix.'options'; 
    
    /*remove submenus*/
    if (isset( $submenu['discount_manager'] ) && in_array( 'disc_mang_roles', wp_list_pluck( $submenu['discount_manager'], 2 ) )) {
        remove_submenu_page('discount_manager', 'disc_mang_roles');
    } 
    
    if (isset( $submenu['discount_manager'] ) && in_array( 'disc_mang_cat', wp_list_pluck( $submenu['discount_manager'], 2 ) )) {
        remove_submenu_page('discount_manager', 'disc_mang_cat');
    } 
    
    $res = $wpdb->get_var("SELECT option_value FROM ".$table." WHERE option_name = 'discount_options'");
    if(!empty(($res))){
        $decoded_options = json_decode($res, true);
        if($decoded_options['user_roles'] == '1'){
            add_submenu_page( 'discount_manager', 'Roles Discount', 'Roles Discount', 'manage_options', 'disc_mang_roles', 'discount_setting');           
         }
         
         if($decoded_options['categories'] == '1'){
            add_submenu_page( 'discount_manager', 'Categories Discount', 'Categories Discount', 'manage_options', 'disc_mang_cat', 'discount_setting');        
        }
    }   
    
}

/*admin menu*/
    add_action('admin_menu','disc_mang_menu');
    
    function disc_mang_menu(){
        add_menu_page('Discount Manager','Discount Manager','manage_options','discount_manager','discount_setting');
        add_submenu_page( 'discount_manager', 'Settings', 'Settings', 'manage_options', 'discount_manager', 'discount_setting');           
        $roles_page = add_submenu_page( 'discount_manager', 'Roles Discount', 'Roles Discount', 'manage_options', 'disc_mang_roles', 'roles_render');   
        $categories_page = add_submenu_page( 'discount_manager', 'Categories Discount', 'Categories Discount', 'manage_options', 'disc_mang_cat', 'categories_render');
        
        add_action('load-'.$roles_page,'disc_mang_add_js_css');
        add_action('load-'.$categories_page,'disc_mang_add_js_css');
    }
    

    function disc_mang_add_js_css(){
        add_action('admin_enqueue_scripts','load_admin_disc_scripts');
    }    

    function load_admin_disc_scripts(){
        wp_enqueue_script('disc_mang_after_load', plugin_dir_url(__FILE__).'discount_manager.js',false,'',TRUE);
        wp_localize_script('disc_mang_after_load', 'disc_obj' ,array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_style('disc_mang_css', plugin_dir_url(__FILE__).'discount_manager.css');
    }
    
/*function to display setting*/    
    function discount_setting(){
        global $title,$wpdb;
        $table = $wpdb->options;        
        echo '<h1>' . $title . '</h1>';
        echo "<p>Show discount for:</h3>";
        $result = $wpdb->get_var("SELECT option_value FROM ".$table." WHERE option_name = 'discount_options'");
        if(!empty(($result))){
            $decoded_result = json_decode($result,true);
            foreach($decoded_result as $key => $value){ 
                ?>
                    <p><input type="checkbox" <?php echo($value == "1")? "checked":'' ?> id="<?= $key ?>"/> <?= formatText($key) ?></p>
                <?php
            }
        }
        ?>
                    <input type="button" value="Save" style="width:100px;" onclick="saveOptions();"/>
                    <script>
                        var $ = jQuery.noConflict();
                        function saveOptions(){
                            var category = $('#categories').is(':checked');
                            if(category == true){
                                category = "1";
                            }else{
                                category = "0";
                            }
                            
                            var user_roles = $('#user_roles').is(':checked');
                            if(user_roles == true){
                                user_roles = "1";
                            }else{
                                user_roles = "0";
                            }                            
                            
                            var url = "<?php echo admin_url('admin-ajax.php'); ?>";
                            $.ajax({                                
                                url:url,
                                type:'POST',
                                data:{
                                    'category':category,
                                    'user_roles':user_roles,
                                    'action':'save_options'
                                },
                                success:function(data){
                                    if(data == 1){
                                        alert('Added successfully');
                                    }                                    
                                }
                            });
                        }
                    </script>
        <?php            
    }
    
    /*handle ajax*/
    add_action('wp_ajax_save_options','save_options_db');
    
    function save_options_db(){
        global $wpdb;
        $category = $_POST['category'];
        $user_roles = $_POST['user_roles'];
       
        $table = $wpdb->prefix."options";
        $res = $wpdb->update($table,
               array(
                   'option_value'   => json_encode(
                           array(
                               "categories" => $category,
                               "user_roles" => $user_roles
                           )
                    )
                ),
                array(
                   'option_name'    => 'discount_options'
               )
            );
        echo $res;
        exit;
    }
    
    function formatText($text){
        $retStr = ucfirst($text);
        $retStr = str_replace("_", " ", $retStr);
        return $retStr;
    }
    
    function formatOptions($text){
        $retStr = strtolower($text);
        $retStr = str_replace(" ", "_", $retStr);
        return $retStr;
    }
  
include ABSPATH."wp-content/plugins/discount_manager/roles.php";    
include ABSPATH."wp-content/plugins/discount_manager/categories.php";  
include ABSPATH."wp-content/plugins/discount_manager/woocommerce.php"; 
?>

