<?php
    function categories_render(){
        global $title, $wpdb;
        $categories = get_terms('product_cat');
        $table = $wpdb->prefix. "options";
        $query = $wpdb->get_row("select * from ".$table." where option_name = 'categories_discount'");
        $categories_encoded = $query->option_value;
        $categories_db = json_decode($categories_encoded,true);
        ?>
            <h1><?= $title ?></h1>
            <input type="hidden" id="page" value="category_page"/>
            <div id="continer_div">
                <div style="margin-bottom: 2%;">
                    <button class="catbtn" id="cat_add">Add</button>
                </div>
                <div class="clearfix"></div>
                <div class="cat_list">
                    <?php 
                    /*existing category discounts*/
                    foreach($categories_db as $category => $discount){
                        if($discount > 0){
                        ?>
                        <div class="row">
                            <div class="role_div select_div">
                                <label><?= $category ?></label>
                                <input type="hidden" class="select" value="<?= $category ?>">
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
                     /*to add new row*/
                     ?>
                        <div class="row">
                            <div class="role_div select_div">
                                <select class="select" name="category">
                                    <option value="-1">Select category</option>
                                        <?php
                                            foreach($categories as $category){
                                                $category = $category->name;
                                                if($categories_db[$category] == 0){
                                          ?>
                                                <option value="<?= $category ?>"><?= $category ?></option>
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
                                <button title="create" style="display:inline-block" onclick="saveCategoryDiscount(this);">Save</button>
                                <button title="delete" style="display:none;" onclick="removeCategoryDiscount(this);" class="btn_delete">Delete</button>
                            </div>
                        </div>
                    </div>

                <?php /*to add new row*/ ?>
                <div class="hidden_row"  style="display:none;"> 
                        <div class="row">
                            <div class="role_div select_div">
                                <select class="select" name="category">
                                    <option value="-1">Select category</option>
                                        <?php
                                            foreach($categories as $category){
                                                $category = $category->name;
                                                if($categories_db[$category] == 0){
                                          ?>
                                                <option value="<?= $category ?>"><?= $category ?></option>
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
                                <button title="create" style="display:inline-block" onclick="saveCategoryDiscount(this);">Save</button>
                                <button title="delete" style="display:none;" onclick="removeCategoryDiscount(this);" class="btn_delete">Delete</button>
                            </div>
                        </div>
                </div>
            </div>
            <?php
    }
    
/*store category discount in database*/
add_action('wp_ajax_cat_dis', 'save_cat_dis_db');
add_action('wp_ajax_nopriv_cat_dis', 'save_cat_dis_db');    

function save_cat_dis_db(){
   global $wpdb,$product;
   $table = $wpdb->prefix . "options";
   $category = $_POST['category'];
   $discount = $_POST['discount'];
   $type = $_POST['type'];
   $id = 0;

   
  if(!empty($category)){
       $row_id = '';
       $roles_encoded = '';
       $results = $wpdb->get_row("select * from ".$table." where option_name = 'categories_discount'");
       if(!empty($results)){
           $row_id = $results->option_id;
           $categories_encoded = $results->option_value;
           $categories=json_decode($categories_encoded,true);           

            if($type == 'insert'){
                $categories[$category]=$discount;    
            
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
                if(array_key_exists($category,$categories)){
                    $categories[$category]=0;    
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
?>

