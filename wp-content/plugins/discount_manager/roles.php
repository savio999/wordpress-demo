<?php

   function roles_render(){
        global $title,  $wp_roles, $wpdb;
        $roles = array();
        $user_roles = $wp_roles->roles;
    
        foreach($user_roles as $role => $info){
            $roles[]= $role;        
        }
        
        $roles = $wp_roles->get_names();
        $table = $wpdb->prefix. "options";
        $query = $wpdb->get_row("select * from ".$table." where option_name = 'roles_discount'");
        $roles_encoded = $query->option_value;
        $roles_db = json_decode($roles_encoded,true);
        ?>
        <div class="spinner"></div>
            <input type="hidden" id="page" value="role_page"/>
            <div id="continer_div">
                <div style="margin-bottom:2%;">
                    <button class="discbtn" id="role_add">Add</button>
                </div>
                <div class="clearfix"></div>
                <div class="role_list">
                    <?php 
                            /*discounts are entered*/    
                            foreach($roles_db as $roleindb => $discount){
                            if($discount > 0){
                        ?>
                        <div class="row">
                        <div class="role_div select_div">
                            <label><?= formatText($roleindb) ?></label>
                            <input type="hidden" class="select" value="<?= $roleindb ?>">
                        </div>
                        <div class="role_div">
                            <input type="number" name="discount" class="discount_input" value="<?= $discount ?>"/>
                        </div>
                        <div class="role_div">
                            <button title="create" style="display:inline-block" class="btn_save" onclick="saveRoleDiscount(this);">Save</button>
                            <button title="delete" style="display:inline-block" class="btn_delete" onclick="removeRoleDiscount(this);">Delete</button>
                        </div>
                    </div>
                    <?php
                        }                        
                    } 
                    
                    /*new entry*/
                    ?>
                    <div class="row">
                        <div class="role_div select_div">
                            <select class="select" name="user_role">
                                <option value="-1">Select role</option>
                                <?php
                                    foreach($roles as $role){
                                        $lrole = formatOptions($role);
                                        if($roles_db[$lrole] == 0){
                                     ?>
                                        <option value="<?= $lrole ?>"><?= $role ?></option>
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
                            <button title="create" style="display:inline-block" class="btn_save" onclick="saveRoleDiscount(this);">Save</button>
                            <button title="delete" style="display:none" class="btn_delete" onclick="removeRoleDiscount(this);">Delete</button>                           
                        </div>
                    </div>                    
                    <?php
                    /*to add new row*/
                     foreach($roles_db as $roleindb => $discount){
                    ?>    
                    <div class="hidden_row" style="display:none;">
                     <div class="row">
                        <div class="role_div select_div">
                            <select class="select" name="user_role">
                                <option value="-1">Select role</option>
                                <?php
                                    foreach($roles as $role){
                                        $lrole = formatOptions($role);
                                        if($roles_db[$lrole] == 0){
                                     ?>
                                        <option value="<?= $lrole ?>"><?= $role ?></option>
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
                            <button title="create" style="display:inline-block" class="btn_save" onclick="saveRoleDiscount(this);">Save</button>
                            <button title="delete" style="display:none" class="btn_delete" onclick="removeRoleDiscount(this);">Delete</button>                           
                        </div>
                    </div>
                </div>
                </div>
            </div>
        <?php
        }        
    }
    
/*store role discount in database*/
add_action('wp_ajax_role_dis', 'save_role_dis_db');
add_action('wp_ajax_nopriv_role_dis', 'save_role_dis_db');    

function save_role_dis_db(){
   global $wpdb;
   $table = $wpdb->prefix . "options";
   $role = $_POST['role'];
   $discount = $_POST['discount'];
   $type = $_POST['type'];
   $id = 0;
   
   if(!empty($role)){
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
                    'option_value'=>json_encode($roles)
                   ),
               array(
                   'option_name'=>'roles_discount'
               ));           
   }
   echo $result;
   exit;
}
?>

