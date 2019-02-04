<?php
/*
 * Plugin Name: Simple OOP plugin
 * Author: Savio
 * Description: Simple plugin that uses OOP
 * text-domain: oop
 * Version: 1.0.0
 */

class SubscriptionForm{
    public function __construct(){
        add_filter('the_content', array($this, 'oop_subscribe_form'));
        add_action('wp_ajax_oop_handle_submit', array($this, 'oop_handle_submit'));
        add_action('wp_ajax_nopriv_oop_handle_submit', array($this, 'oop_handle_submit'));
    }
    
    public function oop_subscribe_form($content){
        $display ='';
        if(!is_singular()){
            return $content;
        }

        if(isset($_GET['oop_success']) && !empty($_GET['oop_success'])){
            $display = "<p>Thanks for subscribing.</p>";
        }else{
             if(isset($_GET['oop_success'])){
                 if(empty($_GET['oop_success'])){
                     $display = "<p>Enter email</p>";
                 }            
            }

            $display .= "<h3>Subscribe</h3>"
                    . "<form method='post' action='".admin_url('admin-ajax.php')."'>"
                    . "<label>Email</label>"
                    . "<input type=email name='sub_email' required/>"
                    . "<input type='hidden' name='action' value='oop_handle_submit'>"                
                    . "<input type='submit' value='Subscribe' style='margin: 2% 0;'/>"
                    . "</form";
        }
        return $content . $display;
    }
    
    public function oop_handle_submit(){
        $is_success = 0;
        if(!empty($_POST['sub_email'])){
            $is_success = 1;
        }

        $url = add_query_arg('oop_success', $is_success, $_SERVER['HTTP_REFERER']);
        wp_redirect($url);
        die;
    }
}

new SubscriptionForm();
?>

