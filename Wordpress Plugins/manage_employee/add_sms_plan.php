<?php
$url = admin_url('admin.php?page=manage_options');
if($_POST){
    global $wpdb;
    extract($_POST);
    update_option('sms_account_sid',trim(strip_tags($account_sid)));
    update_option('sms_auth_token',trim(strip_tags($auth_token)));
    update_option('sms_phone_number',trim(strip_tags($my_phone_number)));
    add_message(SMS_CREDENTIAL_SUCCESS_MSG,1) ;
}
wp_enqueue_script('validity_js',plugins_url('js/jquery.validate.js', __FILE__ ));
wp_enqueue_script('sms_api',plugins_url('js/sms_api.js', __FILE__ ));
wp_enqueue_style('admin_page_sms_css',plugins_url('css/admin_page_css.css', __FILE__ ));

bh_show_message();
?>
<h1> SMS API Configuration Setting</h1>
<form id="sms_api_form" action="<?php echo $url; ?>" method="post" >
    <p>
        <label><?php _e(ACCOUNT_SID)?><em>*</em></label>
        <input type="text" name="account_sid" value="<?php echo get_option('sms_account_sid');?>"size="40" />
    </p>
    <p>
        <label><?php _e(AUTHENTICATION_TOKEN)?><em>*</em></label>
        <input type="text" name="auth_token" value="<?php echo get_option('sms_auth_token');?>" size="40" />
    </p>
    <p>
        <label><?php _e(OUR_SMS_PHONE_NUMBER)?><em>*</em></label>
        <input type="text" name="my_phone_number" value="<?php echo get_option('sms_phone_number');?>" size="20" />
    </p>
    <input type="submit" value="Submit" />
</form>