<?php
global $wpdb;

if($_POST){
    $_POST = array_map( 'stripslashes_deep', $_POST );
	extract($_POST);
        $error = array();
        if(!empty($first_name) && !empty($first_cellno) && !empty($emptype) && !empty($empfrom) ){
                if($wpdb->update( 
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
                            array( 'id' => decode_string($_GET['edit']) ),
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
                        )){ 
               
		wp_redirect(add_query_arg(array('emp_edit'=>1),get_permalink(get_option('list_employee_id'))));
		exit;
		}else{
		    wp_redirect(add_query_arg(array('emp_edit'=>1),get_permalink(get_option('list_employee_id'))));
		exit;
		}
	}else{
	    employee_errors()->add('employee_edit_error', __(EMP_ALL_INFO_ERR));
	}
}

$emp_data =$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."employee_master WHERE id = %d",decode_string($_GET['edit'])));

wp_enqueue_script('validity_js',plugins_url('js/jquery.validate.js', __FILE__ ));
wp_enqueue_script('mask_js',plugins_url('js/jquery.inputmask.js', __FILE__ ));
wp_enqueue_script('alphanumeric_pack',plugins_url('js/alphanumeric.pack.js', __FILE__ ));
wp_enqueue_script('validate_emp_edit_js',plugins_url('js/validate_emp_edit.js', __FILE__ ));
wp_localize_script( 'validate_emp_edit_js', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ),'user_id' => decode_string($_GET['edit'])));

wp_enqueue_style('add_emp_css',plugins_url('css/add_employee.css', __FILE__ ));

bh_show_message();
$output = '<div class="emp_add_div">
     <form id="edit_employee" method="post" >
	<div class="form-group" >
            <div class="labelbox"> <label>'.EMP_FIRST_NAME.'<span>*</span> :</label></div>
                <div class="inputbox">
                    <input type="text" value="'.ucwords($emp_data->emp_firstname).'" name="first_name" id="first_name" tabindex = "2" />
                   
                </div>
            </div>
       
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_LAST_NAME.'<span>*</span> :</label></div>
                <div class="inputbox">
                    <input type="text" value="'.ucwords($emp_data->emp_lastname).'" name="last_name" id="last_name" tabindex = "3" />
                    
                </div>
        </div>
       
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_CELL1.'<span>*</span> :</label></div>
                <div class="inputbox">
                    <input type="text" value="'.$emp_data->cellno1.'" name="first_cellno" id="first_cellno" tabindex = "4" />
                   
                </div>
           
        </div>
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_CELL2.' :</label></div>
                <div class="inputbox">
                    <input type="text" value="'.$emp_data->cellno2.'" name="second_cellno" id="second_cellno" tabindex = "5" />
                   
                </div>
            
        </div>
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_CELL3.' :</label></div>
                <div class="inputbox">
                    <input type="text" value="'.$emp_data->cellno3.'" name="third_cellno" id="third_cellno" tabindex = "6" />
                   
                </div>
            
        </div>
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_TYPE.'<span>*</span> :</label></div>
                <div class="inputbox">';
                $sel1='';
                $sel2='';
                if($emp_data->emp_worktype==1){
                    $sel1="checked=checked";
                }else{
                    $sel2="checked=checked";
                }
                
                $empfromsel1='';
                $empfromsel2='';
                if($emp_data->emp_from==1){
                    $empfromsel1="checked=checked";
                }else{
                    $empfromsel2="checked=checked";
                }
                    $output.='<label><input type="radio" '.$sel1.' name="emptype" value="1" tabindex = "7"/>'.EMP_COMMERCIAL.'
                    </label>
                     <label><input type="radio" '.$sel2.' name="emptype" value="2" tabindex = "8"/>'.EMP_RESIDENTIAL.'
                    </label>
                   <label class="error" style="display:none;" for="emptype" generated="true"></label>
                </div>
                
            
        </div>
        
        <div class="form-group" >
            <div class="labelbox"> <label>'.EMP_FOR.'<span>*</span> :</label></div>
                <div class="inputbox">
                    <label><input type="radio" '.$empfromsel1.' name="empfrom" value="1" tabindex = "9"/>'.EMP_NASH.'
                    </label>
                     <label><input type="radio" '.$empfromsel2.' name="empfrom" value="2" tabindex = "10"/>'.EMP_JACK.'
                    </label>
                   <label class="error" style="display:none;" for="empfrom" generated="true"></label>
                </div>
            
        </div>
        <input type="hidden" value='.$emp_data->id.' />
        <input type="submit" value="Submit" tabindex = "11" disabled="disabled" />
        <a class="anchor_btn" href='.get_permalink(get_option('list_employee_id')).' tabindex = "12">Cancel</a>
     </form>
</div>';

return $output;