<?php
/*
Plugin Name:Manage User Comment
Description: Plugin for manage comment on user profile
Author: Chetan Tiwari
Version: 1.0
*/

add_action('admin_menu', 'add_usercomment_menu');
function add_usercomment_menu(){
       add_menu_page('Manage User Comment','Manage User Comment','10','manage_options','manage_user_comments');
}

function manage_user_comments(){
   include("manage_comments.php");
   
}
function update_ucomment($cid,$c_operation){
    global $wpdb;
    switch($c_operation){
        case 'approve_ucomment':
            $wpdb->update( 'wp_user_comments', array('comment_approved' => '1'), array( 'id' => $cid ), array( '%d' ) , array( '%d' ) );
        break;
        case 'unapprove_ucomment':
            $wpdb->update( 'wp_user_comments', array('comment_approved' => '0'), array( 'id' => $cid ), array( '%d' ) , array( '%d' ) );
        break;
        case 'delete_ucomment':
            $wpdb->delete( 'wp_user_comments', array( 'id' => $cid ), array( '%d' ) );
        break;
    }
}