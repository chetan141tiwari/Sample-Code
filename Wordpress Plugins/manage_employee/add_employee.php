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

wp_enqueue_script('validity_js',plugins_url('js/jquery.validate.js', __FILE__ ));
wp_enqueue_script('mask_js',plugins_url('js/jquery.inputmask.js', __FILE__ ));
wp_enqueue_script('alphanumeric_pack',plugins_url('js/alphanumeric.pack.js', __FILE__ ));
wp_enqueue_script('validate_emp_js',plugins_url('js/validate_emp.js', __FILE__ ));
wp_localize_script( 'validate_emp_js', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

wp_enqueue_style('add_emp_css',plugins_url('css/add_employee.css', __FILE__ ));



global $wpdb;
if($_POST){
    $_POST = array_map( 'stripslashes_deep', $_POST );
	extract($_POST);
        if(!empty($first_name) && !empty($first_cellno) && !empty($emptype) && !empty($empfrom) ){
                $wpdb->insert( 
                            $wpdb->prefix.'employee_master', 
                            array( 
                                'emp_firstname' 		=>	strtolower($first_name),
				'emp_lastname'			=>	strtolower($last_name),
                                'cellno1'			=>	$first_cellno,
                                'cellno2'			=>	$second_cellno,
                                'cellno3'			=>	$third_cellno,
                                'emp_worktype'			=>	$emptype,
                                'is_archive'			=>	0,
                                'emp_from'                      =>      $empfrom 
                                
                            ), 
                            array( 
                                '%s',
                                '%s',
                                '%s',
                                '%s',
                                '%s',
                                '%d',
                                '%d',
                                '%d'
                            ) 
                        );
                $emp_id = $wpdb->insert_id;
               if($emp_id){
		wp_redirect(add_query_arg(array('emp_add'=>1),get_permalink(get_option('list_employee_id'))));
		exit;
		}else{
		    employee_errors()->add('employee_add_error', __(EMP_ADD_ERR_MSG));
		}
	}else{
	    employee_errors()->add('employee_error', __(EMP_ALL_INFO_ERR));
	}
       
}

    employee_show_error_messages();

$output = '<div class="emp_add_div">
     <form id="add_employee" method="post" >
	<div class="form-group" >
            <div class="labelbox"> <label>'.EMP_FIRST_NAME.'<span>*</span> :</label></div>
                <div class="inputbox">
                    <input type="text" placeholder="'.EMP_FIRST_NAME.'" name="first_name" id="first_name" tabindex = "2" />
                   
                </div>
            </div>
       
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_LAST_NAME.'<span>*</span> :</label></div>
                <div class="inputbox">
                    <input type="text" placeholder="'.EMP_LAST_NAME.'" name="last_name" id="last_name" tabindex = "3" />
                    
                </div>
        </div>
       
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_CELL1.'<span>*</span> :</label></div>
                <div class="inputbox">
                    <input type="text" placeholder="'.EMP_CELL1.'" name="first_cellno" id="first_cellno" tabindex = "4" />
                   
                </div>
           
        </div>
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_CELL2.' :</label></div>
                <div class="inputbox">
                    <input type="text" placeholder="'.EMP_CELL2.'" name="second_cellno" id="second_cellno" tabindex = "5" />
                   
                </div>
            
        </div>
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_CELL3.' :</label></div>
                <div class="inputbox">
                    <input type="text" placeholder="'.EMP_CELL3.'" name="third_cellno" id="third_cellno" tabindex = "6" />
                   
                </div>
            
        </div>
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_TYPE.'<span>*</span> :</label></div>
                <div class="inputbox">
                    <label><input type="radio" name="emptype" value="1" tabindex = "7"/>'.EMP_COMMERCIAL.'
                    </label>
                     <label><input type="radio" name="emptype" value="2" tabindex = "8"/>'.EMP_RESIDENTIAL.'
                    </label>
                   <label class="error" style="display:none;" for="emptype" generated="true"></label>
                </div>
                
            
        </div>
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_FOR.'<span>*</span> :</label></div>
                <div class="inputbox">
                    <label><input type="radio" name="empfrom" value="1" tabindex = "9"/>'.EMP_NASH.'
                    </label>
                     <label><input type="radio" name="empfrom" value="2" tabindex = "10"/>'.EMP_JACK.'
                    </label>
                   <label class="error" style="display:none;" for="empfrom" generated="true"></label>
                </div>
            
        </div>
        
        <input type="submit" value="Submit" tabindex = "11" disabled="disabled" />
        <a class="anchor_btn" href='.get_permalink(get_option('list_employee_id')).' tabindex = "12">Cancel</a>
     </form>
</div>';

return $output;