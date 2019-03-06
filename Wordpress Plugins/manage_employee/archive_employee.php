<?php
wp_enqueue_style('add_emp_css',plugins_url('css/list_employee.css', __FILE__ ));
global $wpdb;
if($_GET['archive']) {
	$emp_id = decode_string($_GET['archive']);
     
        if($wpdb->update($wpdb->prefix."employee_master",array('is_archive' => 0), 
	array( 'id' => $emp_id ),array('%d'),array( '%d' ))) {
		wp_redirect(add_query_arg(array('emp_arch_restore'=>1),get_permalink(get_option('list_employee_id'))));
                exit;
	} else {
		 employee_errors()->add('employee_arch_rest_error', __(EMP_ARCH_RESTORE_ERR_MSG));
	}
	
}

$all_emp = $wpdb->get_results($wpdb->prepare("SELECT id,emp_firstname,emp_lastname,cellno1,emp_worktype,emp_from FROM ".$wpdb->prefix."employee_master WHERE is_archive = %d",1	),ARRAY_A);
employee_show_error_messages();
bh_show_message();
$output.='
<a class="anchor_btn" href='.get_permalink(get_option('list_employee_id')).'>Back</a>

<div class="main_table"><table class="" id="rounded-corner">
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
				$output.='<tr><td colspan="5">'.NO_ARC_EMP_EXISTS.'</td></tr>';
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
    <td>'.$emp['emp_firstname'].' '.$emp['emp_lastname'].'</td>
    <td>'.$emp['cellno1'].'</td>
    <td>'.$emptype.'</td>
    <td>'.$empfor.'</td>
    <td>
    <a href='.add_query_arg('archive',encode_string($emp['id']),get_permalink(get_option('archive_employee_id'))).' onclick="return confirm(\'Do you really want to restore this employee?\')">Restore</a></td>
    </tr>';
 }
                        }

$output .='</table></div>';

return $output;