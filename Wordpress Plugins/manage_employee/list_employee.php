<?php
/***** Check user login and role *****/
if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $current_user = wp_get_current_user();//print_r($current_user->roles[0]);
	if($current_user->roles[0] != 'editor') {
		wp_redirect(site_url());
        exit;
	}
} else {
    wp_redirect(site_url());
    exit;
}

wp_enqueue_style('add_emp_css',plugins_url('css/list_employee.css', __FILE__ ));
global $wpdb;
if($_GET['emp_add']==1){
    add_message(EMP_ADD_SUCCESS_MSG,1) ;
}
if($_GET['emp_del']==1){
    add_message(EMP_DEL_SUCCESS_MSG,1);
}
if($_GET['emp_edit']==1){
    add_message(EMP_EDIT_SUCCESS_MSG,1);
}
if($_GET['emp_arch']==1){
    add_message(EMP_ARCH_SUCCESS_MSG,1);
}
if($_GET['emp_arch_restore']==1){
    add_message(EMP_ARCH_RESTORE_SUCCESS_MSG,1);
}

if($_GET['delete']) {
	$emp_id = decode_string($_GET['delete']);
	if($wpdb->delete($wpdb->prefix."employee_master", array("id"=>$emp_id))) {
		wp_redirect(add_query_arg(array('emp_del'=>1),get_permalink(get_option('list_employee_id'))));
                exit;
	} else {
		 employee_errors()->add('employee_del_error', __(EMP_ALL_INFO_ERR));
	}
	
}
if($_GET['archive']) {
	$emp_id = decode_string($_GET['archive']);
     
        if($wpdb->update($wpdb->prefix."employee_master",array('is_archive' => 1), 
	array( 'id' => $emp_id ),array('%d'),array( '%d' ))) {
		wp_redirect(add_query_arg(array('emp_arch'=>1),get_permalink(get_option('list_employee_id'))));
                exit;
	} else {
		 employee_errors()->add('employee_arch_error', __(EMP_ARCH_ERR_MSG));
	}
	
}

	
employee_show_error_messages();
bh_show_message();
$output.='
<a class="anchor_btn" href='.get_permalink(get_option('add_employee_id')).'>Add New Employee</a>
<a class="anchor_btn" href='.get_permalink(get_option('archive_employee_id')).'>Archive Employee</a>
<form>
<input type="text" placeholder="User Name" name="searchterm" id="searchterm" value="'.$_GET['searchterm'].'">
<select id="sel_form" name="from">
	  <option value="">'.SELECT.' '.EMP_FOR.'</option>
          <option value="1" '.($_GET['from']==1?'selected="selected"':'').'>'.EMP_NASH.'</option>
	  <option value="2" '.($_GET['from']==2?'selected="selected"':'').'>'.EMP_JACK.'</option>                     
</select>

<select id="sel_form" name="type">
	  <option value="">'.SELECT.' '.EMP_TYPE.'</option>
          <option value="1" '.($_GET['type']==1?'selected="selected"':'').'>'.EMP_COMMERCIAL.'</option>
	  <option value="2" '.($_GET['type']==2?'selected="selected"':'').'>'.EMP_RESIDENTIAL.'</option>                     
</select>
<input type="submit" value="Apply Fiter">
<a class="anchor_btn" href='.get_permalink(get_option('list_employee_id')).'>Reset Filter </a>
</form>';
$total='';
$where_clause ='';

    if($_GET['from']!=''){
	$where_from = ' and emp_from='.$_GET['from'];
	$where_clause .= $where_from;
    }
    if($_GET['type']!=''){
	$where_type = ' and emp_worktype='.$_GET['type'];
	$where_clause .= $where_type;
    }
    if($_GET['searchterm']!=''){
	$where_serach = " and emp_firstname like '%".strtolower($_GET['searchterm'])."%'";
	$where_clause .= $where_serach;
    }
    
    $total= $wpdb->get_var("select count(id) from ".$wpdb->prefix."employee_master WHERE is_archive = 0 $where_clause");
  
$pagenum = isset( $_GET['pnum'] ) ? absint( $_GET['pnum'] ) : 1;
	$limit = 100;      /*limit for the pagination per page*/
	$offset = ( $pagenum - 1 ) * $limit;
	$num_of_pages = ceil( $total / $limit );
	$paginatin=  " limit $offset,$limit ";
	
	$page_links = paginate_links( array(
	      'base' => get_permalink(get_option('list_employee_id')).'%_%',
	      'format'       => '?pnum=%#%',
	      'prev_text' => __( '&laquo; Previous'),
	      'next_text' => __( 'Next &raquo;'),
	      'total' => $num_of_pages,
	     'add_args' =>array(
				'searchterm' => $_GET['searchterm'] ? $_GET['searchterm'] :'',
				'type' => $_GET['type'] ? $_GET['type'] :'',
				'from' => $_GET['from'] ? $_GET['from']:'' ),		      'current' => $pagenum
	     ));

    
    
    $all_emp = $wpdb->get_results("SELECT id,emp_firstname,emp_lastname,cellno1,emp_worktype,emp_from FROM ".$wpdb->prefix."employee_master WHERE is_archive = 0 $where_clause order by emp_firstname ASC $paginatin",ARRAY_A);


$output .='<div class="main_table"><table class="" id="rounded-corner">
        <thead>
            <tr>
                <th  width="20%">'.FULL_EMP_NAME.'</th>
		<th   width="20%" >'.EMP_CELL1.'</th>
		<th   width="20%" >'.EMP_TYPE.'</th>
		<th  width="20%" >'.EMP_FOR.'</th>
		<th   width="20%" >'.EMP_ACTIONS.'</th>
            </tr>
        </thead>';
	if(empty($all_emp)){
				$output.='<tr><td colspan="5">'.EMP_NOT_EXISTS.'</td></tr>';
			}else{
 foreach($all_emp as $emp){
    $empfor='';
    $emptype='';
    
    if($emp['emp_from']==1)
    $empfor= EMP_NASH;
    else
    $empfor= EMP_JACK;
    
    if($emp['emp_worktype']==1)
    $emptype= EMP_COMMERCIAL;
    else
    $emptype= EMP_RESIDENTIAL;
    
    $output.='<tr>
    <td>'.ucwords($emp['emp_firstname']).' '.ucwords($emp['emp_lastname']).'</td>
    <td>'.$emp['cellno1'].'</td>
    <td>'.$emptype.'</td>
    <td>'.$empfor.'</td>
    <td><a href='.add_query_arg('delete',encode_string($emp['id']),get_permalink(get_option('list_employee_id'))).' onclick="return confirm(\'Do you really want to delete this employee?\')">Delete</a>
    <a href='.add_query_arg('edit',encode_string($emp['id']),get_permalink(get_option('edit_employee_id'))).'>Edit</a>
    <a href='.add_query_arg('archive',encode_string($emp['id']),get_permalink(get_option('list_employee_id'))).' onclick="return confirm(\'Do you really want to archive this employee?\')">Archive</a></td>
    </tr>';
 }
}

$output .='</table>
<div class="pagination_links">'.$page_links.'</div>
</div>';


return $output;