<?php
/*
Plugin Name: Manage Employee
Description: Used to manage the employee records
Version: 1.0
Author: Chetan Tiwari
*/
include('macro.php');
function create_plugin_pages($page_title,$page_code){
	global $wpdb;
		    
		    $the_page_title = $page_title;
		    $the_page_name = $page_title;

		    // the menu entry...
		    delete_option($page_code."_title");
		    add_option($page_code."_title", $the_page_title, '', 'yes');
		    // the slug...
		    delete_option($page_code."_name");
		    add_option($page_code."_name", $the_page_name, '', 'yes');
		    // the id...
		    delete_option($page_code."_id");
		    add_option($page_code."_id", '0', '', 'yes');

		    $the_page = get_page_by_title( $the_page_title );

		    if ( ! $the_page ) {

			// Create post object
			$_p = array();
			$_p['post_title'] = $the_page_title;
			$_p['post_content'] = "[" . $page_code . "]";
			$_p['post_status'] = 'publish';
			$_p['post_type'] = 'page';
			$_p['comment_status'] = 'closed';
			$_p['ping_status'] = 'closed';
			$_p['post_category'] = array(1); // the default 'Uncatrgorised'

			// Insert the post into the database
			$the_page_id = wp_insert_post( $_p );

		    }
		    else {
			// the plugin may have been previously active and the page may just be trashed...

			$the_page_id = $the_page->ID;

			//make sure the page is not trashed...
			$the_page->post_status = 'publish';
			$the_page_id = wp_update_post( $the_page );

		    }

		    delete_option( $page_code."_id" );
		    add_option($page_code."_id", $the_page_id );
}

function remove_plugin_pages($page_code){
	global $wpdb;
	$the_page_title = get_option( $page_code."_title" );
	$the_page_name = get_option( $page_code."_name" );
	//  the id of our page...
	$the_page_id = get_option( $page_code.'_id' );			/*get the page id*/
	if( $the_page_id ) {
	        wp_delete_post( $the_page_id ); // this will trash, not delete
	}
	delete_option($page_code."_title");
	delete_option($page_code."_name");
	delete_option($page_code."_id");
	
}

add_action('admin_menu', 'add_api_menu');
function add_api_menu(){
       add_menu_page('Sms API Credentials','Sms API Credentials','10','manage_options','sms_options');
}

function sms_options(){
   include("add_sms_plan.php");
   
}

register_activation_hook(__FILE__,'activeFunction_plugin');
function activeFunction_plugin(){
    create_plugin_pages(ADD_EMPLOYEE,'add_employee');
    create_plugin_pages(EDIT_EMPLOYEE,'edit_employee');
    create_plugin_pages(LIST_EMPLOYEE,'list_employee');
    create_plugin_pages(MANAGE_DASHBOARD,'emp_dashboard');
    create_plugin_pages(SHIFT_SETUP,'shift_setup');
    create_plugin_pages(ARCHIVE_EMP_PAGE,'archive_employee');
    create_plugin_table();    
}

register_deactivation_hook( __FILE__, 'deActiveFunction_plugin');
function deActiveFunction_plugin(){
    remove_plugin_pages('add_employee');
    remove_plugin_pages('edit_employee');
    remove_plugin_pages('list_employee');
    remove_plugin_pages('emp_dashboard');
    remove_plugin_pages('shift_setup');
    remove_plugin_pages('archive_employee');
    remove_plugin_table();
}

function create_plugin_table(){
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
     $employee_master = $wpdb->prefix . "employee_master";
     $shift_master = $wpdb->prefix . "shift_master";
     $sms_master = $wpdb->prefix . "sms_master";
     $break_table = $wpdb->prefix . "break_table";
	
	$sql = "CREATE TABLE IF NOT EXISTS $employee_master (			
		`id` mediumint(9) AUTO_INCREMENT,
		`emp_firstname` varchar(25),
		`emp_lastname` varchar(25),
		`cellno1` varchar(25),
		`cellno2` varchar(25),
		`cellno3` varchar(25),
		`emp_worktype` tinyint(1),
		`is_archive` tinyint(1),
		`emp_from` tinyint(1),
		UNIQUE KEY id (id)
	);";
	
	
	dbDelta($sql);
        
        $sql2 = "CREATE TABLE IF NOT EXISTS $shift_master (			
		`id` mediumint(9) AUTO_INCREMENT,
		`emp_id` mediumint(9),
		`firstapp_time` varchar(25),
		`firstapp_code` tinyint(2),
                `secondapp_time` varchar(25),
		`secondapp_code` tinyint(2),
                `thirdapp_time` varchar(25),
		`thirdapp_code` tinyint(2),
		`last_updated`  varchar(40),
		UNIQUE KEY id (id)
	);";
	dbDelta($sql2);
	
	$sql3 = "CREATE TABLE IF NOT EXISTS $sms_master (			
		`id` mediumint(9) AUTO_INCREMENT,
		`emp_id` mediumint(9),
		`client_name` varchar(50),
		`in_time` varchar(25)default 0,
		`out_time` varchar(25) default 0,
		`code` tinyint(2),
                `shift` tinyint(2),
		`date` varchar(25),
                `payment_type` tinyint(2),
		`payment_amount` tinyint(2),
		UNIQUE KEY id (id)
	);";
	dbDelta($sql3);
	
	$sql4 = "CREATE TABLE IF NOT EXISTS $break_table (			
		`id` mediumint(9) AUTO_INCREMENT,
		`sms_id` mediumint(9),
		`break_out` varchar(25) default 0,
		`break_in` varchar(25) default 0,
                `duration` varchar(25),
		`date` varchar(25),
                UNIQUE KEY id (id)
	);";
	dbDelta($sql4);
    
}

function remove_plugin_table(){
    global $wpdb;
     $employee_master = $wpdb->prefix . "employee_master";
     $shift_master = $wpdb->prefix . "shift_master"; 
  			
    //$wpdb->query("DROP TABLE IF EXISTS $employee_master");
    //$wpdb->query("DROP TABLE IF EXISTS $shift_master");
}

function list_employee_shortcode(){
    	return require_once(plugin_dir_path(__FILE__) . "list_employee.php");
}
add_shortcode("list_employee", "list_employee_shortcode");

function archive_employee_shortcode(){
    	return require_once(plugin_dir_path(__FILE__) . "archive_employee.php");
}
add_shortcode("archive_employee", "archive_employee_shortcode");

function add_employee_shortcode(){
    	 return require_once(plugin_dir_path(__FILE__) . "add_employee.php");
}
add_shortcode("add_employee", "add_employee_shortcode");

function edit_employee_shortcode(){
    	return require_once(plugin_dir_path(__FILE__) . "edit_employee.php");
}
add_shortcode("edit_employee", "edit_employee_shortcode");

function emp_dashboard_shortcode(){
    	return require_once(plugin_dir_path(__FILE__) . "emp_dashboard.php");
}
add_shortcode("emp_dashboard", "emp_dashboard_shortcode");

function shift_setup_shortcode(){
    	return require_once(plugin_dir_path(__FILE__) . "shift_setup.php");
}
add_shortcode("shift_setup", "shift_setup_shortcode");


// used for tracking error messages
function employee_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function employee_show_error_messages() {
	if($codes = employee_errors()->get_error_codes()) {
		echo '<div class="buser_errors">';
		    // Loop error codes and display errors
		   foreach($codes as $code){
		        $message = employee_errors()->get_error_message($code);
		        echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
		    }
		echo '</div>';
	}	
}

add_action('wp_ajax_check_userphones','checkUserPhones');
add_action('wp_ajax_nopriv_check_userphones','checkUserPhones');
function checkUserPhones(){
    global $wpdb;
    $phone1 = $_POST["phone_num1"];
    $phone_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM ". $wpdb->prefix.'employee_master' ." where cellno1='%s' or cellno2='%s' or cellno3='%s'",$phone1,$phone1,$phone1));
    echo $phone_count;
    die;
}

add_action('wp_ajax_check_userphonesedit','checkUserPhonesEdit');
add_action('wp_ajax_nopriv_check_userphonesedit','checkUserPhonesEdit');
function checkUserPhonesEdit(){
    global $wpdb;
    $phone1 = $_POST["phone_num1"];
    $id=$_POST["emp_id"];
    $phone_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM ". $wpdb->prefix.'employee_master' ." where (cellno1='%s' or cellno2='%s' or cellno3='%s') and id<>%d",$phone1,$phone1,$phone1,$id));
    echo $phone_count;
    die;
}





