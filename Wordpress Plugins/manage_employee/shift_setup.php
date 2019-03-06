<?php
global $wpdb;
wp_enqueue_style('shift_setup_css',plugins_url('css/shift_setup.css', __FILE__ ));
wp_enqueue_style('time_pick_css_emp',plugins_url('css/jquery-ui-timepicker-addon.css', __FILE__ ));
wp_enqueue_style('jquery_ui_css_emp',plugins_url('css/jquery-ui.css', __FILE__ ));
wp_enqueue_script('jquery_ui_js_emp',plugins_url('js/jquery-ui.min.js', __FILE__ ));
wp_enqueue_script('timepicker_js_emp',plugins_url('js/jquery-ui-timepicker-addon.js', __FILE__ ));
wp_enqueue_script('validity_js',plugins_url('js/jquery.validate.js', __FILE__ ));
wp_enqueue_script('shift_setup_js',plugins_url('js/shift_setup.js', __FILE__ ));

$code_array = array(
     REGULAR_VAL=>'Regular',
     TRAINER_VAL=>'Trainer',
     TRAINEE_VAL=>'Trainee',
     COMMONCALL_VAL=>'Comm on Call'
    );


$column="em.emp_firstname";
$sorttype= "ASC";
$emp_id='';
if($_GET['delete']) {
	$emp_id_del = decode_string($_GET['delete']);
	if($wpdb->delete($wpdb->prefix."shift_master", array("emp_id"=>$emp_id_del))) {
		add_message(EMP_SHIFT_DEL_SUCCESS_MSG,1);
	} else {
		 employee_errors()->add('employee_shift_del_error', __(EMP_SHIFT_DEL_ERROR_MSG));
	}
	
}

if($_POST){
  $_POST = array_map( 'stripslashes_deep', $_POST );
  extract($_POST);
  if($radioapp_1=='other1'){
   $radioapp_1=$other_1;
  }
  if($radioapp_2=='other2'){
   $radioapp_2=$other_2;
  }
  if($radioapp_3=='other3'){
   $radioapp_3=$other_3;
  }
  
  if($submit=='Submit'){
   $wpdb->insert($wpdb->prefix.'shift_master', 
                            array( 
                                'emp_id'=>	$emp_id,
				'firstapp_time'			=>	$radioapp_1,
                                'firstapp_code'			=>	$emp_code1,
                                'secondapp_time'			=>	$radioapp_2,
                                'secondapp_code'			=>	$emp_code2,
                                'thirdapp_time'			=>	$radioapp_3,
                                'thirdapp_code'			=>	$emp_code3,
				'last_updated'			=> 	time()
                              ), 
                            array( 
                                '%d',
                                '%s',
                                '%d',
                                '%s',
                                '%d',
                                '%s',
                                '%d',
				'%s'
                            ) 
                        );
      $user_id = $wpdb->insert_id;
       if($user_id){
		add_message(EMP_SHIFT_SETUP_SUCCESS_MSG,1);
		
		}else{
		    employee_errors()->add('employee_shift_error', __(EMP_SHIFT_ERR_MSG));
		}
  }
  if($submit=='Update'){
   
    if($wpdb->update($wpdb->prefix.'shift_master', 
                            array(                               
				'firstapp_time'			=>	$radioapp_1,
                                'firstapp_code'			=>	$emp_code1,
                                'secondapp_time'		=>	$radioapp_2,
                                'secondapp_code'		=>	$emp_code2,
                                'thirdapp_time'			=>	$radioapp_3,
                                'thirdapp_code'			=>	$emp_code3,
				'last_updated'			=> 	time()
                              ),
			    array( 'emp_id' => decode_string($edit_emp_id) ),
			    array( 
                                '%s',
                                '%d',
                                '%s',
                                '%d',
                                '%s',
                                '%d',
				'%s'
                            ),
			    array( '%d' )
                        )){
      
      add_message(EMP_SHIFT_SETUP_UPDATE_SUCCESS_MSG,1);
      //$column="sm.last_updated";
      //$sorttype="DESC";
      
      }
    $emp_id='';
    
  }
 
}
if($_GET['edit']){
	
 $emp_id = decode_string($_GET['edit']);

 $emp_data =$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."shift_master WHERE emp_id = %d",$emp_id),ARRAY_A);
 
 
 $all_emp=$wpdb->get_results($wpdb->prepare("SELECT em.id,em.emp_firstname,em.emp_lastname FROM ".$wpdb->prefix."employee_master em where em.id=%d",$emp_id),ARRAY_A);
 $select_option='';
 
 $selected_app1_time = $emp_data['firstapp_time'];
 $selected_app1_code = $emp_data['firstapp_code'];
 
 $selected_app2_time = $emp_data['secondapp_time'];
 $selected_app2_code = $emp_data['secondapp_code'];
 
 $selected_app3_time = $emp_data['thirdapp_time'];
 $selected_app3_code = $emp_data['thirdapp_code'];
 
 if($selected_app1_time!='' && !($selected_app1_time==EIGHT_THIRTY.' '.TIME_AM or $selected_app1_time==NINE_AM.' '.TIME_AM or $selected_app1_time==ONE_THIRTY.' '.TIME_PM)){
 $other1 = true;
}
if($selected_app2_time!='' && !($selected_app2_time==EIGHT_THIRTY.' '.TIME_AM or $selected_app2_time==NINE_AM.' '.TIME_AM or $selected_app2_time==ONE_THIRTY.' '.TIME_PM) ){
 $other2 = true;
}
if($selected_app3_time!='' && !($selected_app3_time==EIGHT_THIRTY.' '.TIME_AM or $selected_app3_time==NINE_AM.' '.TIME_AM or $selected_app3_time==ONE_THIRTY.' '.TIME_PM)){
 $other3 = true;
}
 
}
else{
	$emp_id='';
 
$all_emp = $wpdb->get_results($wpdb->prepare("SELECT em.id,em.emp_firstname,em.emp_lastname FROM ".$wpdb->prefix."employee_master as em WHERE em.is_archive = %d and em.id NOT IN(select emp_id from ".$wpdb->prefix."shift_master) order by em.id desc",0),ARRAY_A);
$select_option =  '<option value="">'.SELECT.'</option>';


}

employee_show_error_messages();
bh_show_message();

$output ='<div class="emp-wra">
<form id="shift_form" method="post" action="'.get_permalink(get_option('shift_setup_id')).'">
 <div class="box-top"> <div class="box-top-left"> <h3>'.EMP_NAME.'</h3>
     <select name="emp_id" id="emp_id_sel" >'.$select_option;
       
       foreach($all_emp as $emp){
        $output.='<option value='.$emp['id'].'>'.ucwords($emp['emp_firstname']).' '.ucwords($emp['emp_lastname']).'</option>';
       }
      $output.='</select></div>
</div>
     
     
<div class="employee-from-outer">
   <div class="box-outer"> <h3>'.APP1.'</h3> <div class="box">
            
              <p>
                 <label>
                   <input type="radio" class="no_other" name="radioapp_1" value="'.EIGHT_THIRTY.' '.TIME_AM.'" id="radio1_app1" '.($selected_app1_time==EIGHT_THIRTY.' '.TIME_AM?'checked="checked"':'').'/>
                   '.EIGHT_THIRTY.TIME_AM.'</label>
                 
                 <label>
                   <input type="radio" class="no_other" name="radioapp_1" value="'.NINE_AM.' '.TIME_AM.'" id="radio2_app1" '.($selected_app1_time==NINE_AM.' '.TIME_AM?'checked="checked"':'').'/>
                   '.NINE_AM.TIME_AM.'</label>
                 
                 <label>
                   <input type="radio" class="no_other" name="radioapp_1" value="'.ONE_THIRTY.' '.TIME_PM.'" id="radio3_app1" '.($selected_app1_time==ONE_THIRTY.' '.TIME_PM?'checked="checked"':'').' />
                   '.ONE_THIRTY.TIME_PM.'</label>
                 
                 <label class="box-label-other">
                   <input type="radio" class="other_radio" name="radioapp_1" value="other1" id="radio4_app1" '.($other1?'checked="checked"':'').'/>
                   '.OTHER.' <input type="text" name="other_1" '.($other1?'value="'.$selected_app1_time.'"':'style="display:none;"').' class="other_time select-other"/></label>
                  <label class="error" for="radioapp_1" style="display:none;"generated="true" ></label>
                 
                 <label class="box-code-other"> '.CODE.'<select name="emp_code1" class="select-code">
                 <option value="">'.SELECT.'</option>';
                 foreach($code_array as $key=>$value){
                  $output .= '<option value="'.$key.'" '.($selected_app1_code==$key?'selected="selected"':'').'>'.$value.'</option>';
                 }
                 
                 
             $output .= '</select></label>
              </p>
           
     </div> </div>
     
     <div class="box-outer"> <h3>'.APP2.'</h3> <div class="box"> 
              <p>
                 <label>
                   <input type="radio" class="no_other" name="radioapp_2" value="'.EIGHT_THIRTY.' '.TIME_AM.'" id="radio1_app2" '.($selected_app2_time==EIGHT_THIRTY.' '.TIME_AM?'checked="checked"':'').' />
                   '.EIGHT_THIRTY.' '.TIME_AM.'</label>
                
                 <label>
                   <input type="radio" class="no_other" name="radioapp_2" value="'.NINE_AM.' '.TIME_AM.'" id="radio2_app2" '.($selected_app2_time==NINE_AM.' '.TIME_AM?'checked="checked"':'').' />
                   '.NINE_AM.TIME_AM.'</label>
                 
                 <label>
                   <input type="radio" class="no_other" name="radioapp_2" value="'.ONE_THIRTY.' '.TIME_PM.'" id="radio3_app2" '.($selected_app2_time==ONE_THIRTY.' '.TIME_PM?'checked="checked"':'').' />
                   '.ONE_THIRTY.TIME_PM.'</label>
                 
                 <label class="box-label-other">
                   <input type="radio" class="other_radio" name="radioapp_2" value="other2" id="radio4_app2" '.($other2?'checked="checked"':'').'/>
                    '.OTHER.' <input type="text" name="other_2" '.($other2?'value="'.$selected_app2_time.'"':'style="display:none;"').' class="select-other other_time"></label>
                 <label class="error" for="radioapp_2" style="display:none;" generated="true" ></label>
                <label class="box-code-other"> '.CODE.'<select name="emp_code2" class="select-code">
                 <option value="">'.SELECT.'</option>';
                  foreach($code_array as $key=>$value){
                  $output .= '<option value="'.$key.'" '.($selected_app2_code==$key?'selected="selected"':'').'>'.$value.'</option>';
                 }
                $output.='                 
                 </select></label>
              </p>
           </div> </div>
           
    <div class="box-outer"> <h3>'.APP3.'</h3>  <div class="box last"> 
              <p>
                 <label>
                   <input type="radio" class="no_other" name="radioapp_3" value="'.EIGHT_THIRTY.' '.TIME_AM.'" id="radio1_app3" '.($selected_app3_time==EIGHT_THIRTY.' '.TIME_AM?'checked="checked"':'').' />
                   '.EIGHT_THIRTY.TIME_AM.'</label>
                
                 <label>
                   <input type="radio" class="no_other" name="radioapp_3" value="'.NINE_AM.' '.TIME_AM.'" id="radio2_app3" '.($selected_app3_time==NINE_AM.' '.TIME_AM?'checked="checked"':'').' />
                   '.NINE_AM.TIME_AM.'</label>
                 
                 <label>
                   <input type="radio" class="no_other" name="radioapp_3" value="'.ONE_THIRTY.' '.TIME_PM.'" id="radio3_app3" '.($selected_app3_time==ONE_THIRTY.' '.TIME_PM?'checked="checked"':'').' />
                   '.ONE_THIRTY.TIME_PM.'</label>
                
                 <label class="box-label-other">
                   <input type="radio" class="other_radio" name="radioapp_3" value="other3" id="radio4_app3" '.($other3?'checked="checked"':'').' />
                    '.OTHER.'<input type="text" name="other_3" '.($other3?'value="'.$selected_app3_time.'"':'style="display:none;"').' class="other_time select-other" /></label>
                  <label class="error" for="radioapp_3" style="display:none;" generated="true"></label>
                 <label class="box-code-other"> '.CODE.'<select name="emp_code3" class="select-code">
                 <option value="">'.SELECT.'</option>';
                  foreach($code_array as $key=>$value){
                  $output .= '<option value="'.$key.'" '.($selected_app3_code==$key?'selected="selected"':'').'>'.$value.'</option>';
                 }
                 $output.='</select></label>
              </p>
           </div></div>
            <div class="box-top-right-mobile">
	    <input type="hidden" name="edit_emp_id" value="'.($emp_id?encode_string($emp_id):'').'">
      <input type="submit" name="submit" id="submit_btn"  value="'.($emp_id?'Update':'Submit').'">
<a class="anchor_btn" href="'.($emp_id?get_permalink(get_option('shift_setup_id')):get_permalink(get_option('list_employee_id'))).'">Cancel</a> 
     </div>
  </form>
    </div>';
    $total='';
    $where_clause ='';

    if($_GET['from']!=''){
	$where_from = ' and em.emp_from='.$_GET['from'];
	$where_clause .= $where_from;
    }
    if($_GET['type']!=''){
	$where_type = ' and em.emp_worktype='.$_GET['type'];
	$where_clause .= $where_type;
    }
    if($_GET['searchterm']!=''){
	$where_serach = " and em.emp_firstname like '%".strtolower($_GET['searchterm'])."%'";
	$where_clause .= $where_serach;
    }
    
    $pagenum = isset( $_GET['pnum'] ) ? absint( $_GET['pnum'] ) : 1;
	$limit = 100;      /*limit for the pagination per page*/
	$offset = ( $pagenum - 1 ) * $limit;
	
	$total = $wpdb->get_var("select count(sm.id) from ".$wpdb->prefix."shift_master as sm join ".$wpdb->prefix."employee_master as em on sm.emp_id=em.id where sm.id<>'' $where_clause ");
	$num_of_pages = ceil( $total / $limit );
	$paginatin=  " limit $offset,$limit ";
	
	$page_links = paginate_links( array(
	      'base' => get_permalink(get_option('shift_setup_id')).'%_%',
	      'format'       => '?pnum=%#%',
	      'prev_text' => __( '&laquo; Previous'),
	      'next_text' => __( 'Next &raquo;'),
	      'total' => $num_of_pages,
	      'add_args' =>array(
				'searchterm' => $_GET['searchterm'] ? $_GET['searchterm'] :'',
				'type' => $_GET['type'] ? $_GET['type'] :'',
				'from' => $_GET['from'] ? $_GET['from']:'' ),
	      'current' => $pagenum
	     ) );
 
    $all_emp = $wpdb->get_results("SELECT sm.*,em.emp_firstname,em.emp_lastname FROM ".$wpdb->prefix."shift_master as sm join ".$wpdb->prefix."employee_master as em on sm.emp_id=em.id where sm.id<>'' $where_clause order by $column $sorttype $paginatin",ARRAY_A);
    
    $output.='<form>
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
<div class="filter_btn"><input type="submit" value="Apply Fiter">
<a class="anchor_btn" href='.get_permalink(get_option('list_employee_id')).'>Reset Filter </a> </div>
</form>';
      
     $output.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th>'.EMP_NAME.'</th>
    <th>'.APP1.'</th>
    <th>'.APP2.'</th>
    <th>'.APP3.'</th>
    <th>'.ACTION.'</th>
  </tr>';
  if(empty($all_emp)){
				$output.='<tr><td colspan="5">'.EMP_NOT_EXISTS.'</td></tr>';
			}else{
 foreach($all_emp as $emp){
  $output.='<tr>
    <td>'.ucwords($emp['emp_firstname']).' '.ucwords($emp['emp_lastname']).'</td>
    <td>'.$emp['firstapp_time'].'-'.$code_array[$emp['firstapp_code']].' </td>
    <td>'.$emp['secondapp_time'].'-'.$code_array[$emp['secondapp_code']].'</td>
    <td>'.$emp['thirdapp_time'].'-'.$code_array[$emp['thirdapp_code']].'</td>
    <td>
    <a href='.add_query_arg('edit',encode_string($emp['emp_id']),get_permalink(get_option('shift_setup_id'))).'>Edit</a>
    <a href='.add_query_arg('delete',encode_string($emp['emp_id']),get_permalink(get_option('shift_setup_id'))).' onclick="return confirm(\'Do you really want to delete this shift information?\')">Delete</a>
    </td>
    </tr>';
 }
}
  $output .='   
</table>
<div class="pagination_links">'.$page_links.'</div>
  </div>';
  
 return $output;