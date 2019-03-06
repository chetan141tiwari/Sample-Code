<?php
global $wpdb;
/***** Check user login and role *****/
wp_enqueue_style('shift_setup_css',plugins_url('css/shift_setup.css', __FILE__ ));
wp_enqueue_style('time_pick_css_emp',plugins_url('css/jquery-ui-timepicker-addon.css', __FILE__ ));
wp_enqueue_style('jquery_ui_css_emp',plugins_url('css/jquery-ui.css', __FILE__ ));
wp_enqueue_script('jquery_ui_js_emp',plugins_url('js/jquery-ui.min.js', __FILE__ ));
wp_enqueue_script('timepicker_js_emp',plugins_url('js/jquery-ui-timepicker-addon.js',__FILE__));
wp_enqueue_script('dashboard',plugins_url('js/dashboard.js',__FILE__));
 $form_date = $_GET['dash_date']?$_GET['dash_date']:date('Y-m-d');

if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $current_user = wp_get_current_user();
	if($current_user->roles[0] != 'editor') {
		wp_redirect(site_url());
        exit;
	}
} else {
    wp_redirect(site_url());
    exit;
}
if($_POST){
    extract($_POST);
    if($shift_id_1!=''){
	$in_time_app1;
	$out_time_app1;
	
	$wpdb->update($wpdb->prefix.'sms_master', 
               array('client_name'=>strtolower($first_name),
		     'in_time'=>strtolower($last_name),
		     'out_time'	=> $first_cellno
                    ),
               array('emp_id' => decode_string($_GET['edit']),'id'=>$shift_id_1 ),
               array('%s','%s','%s')); 
    }
    if($shift_id_2!=''){
	$in_time_app2;
	$out_time_app2;
	
	$wpdb->update($wpdb->prefix.'sms_master', 
               array('client_name'=>strtolower($first_name),
		     'in_time'=>strtolower($last_name),
		     'out_time'	=> $first_cellno
                    ),
               array('emp_id' => decode_string($_GET['edit']),'id'=>$shift_id_2 ),
               array('%s','%s','%s'));
	
    }
    if($shift_id_3!=''){
	$in_time_app3;
	$out_time_app3;
	$wpdb->update($wpdb->prefix.'sms_master', 
               array('client_name'=>strtolower($first_name),
		     'in_time'=>strtolower($last_name),
		     'out_time'	=> $first_cellno
                    ),
               array('emp_id' => decode_string($_GET['edit']),'id'=>$shift_id_3 ),
               array('%s','%s','%s'));
	
    }
    
}

$code_array = array(
     REGULAR_VAL=>'Regular',
     TRAINER_VAL=>'Trainer',
     TRAINEE_VAL=>'Trainee',
     COMMONCALL_VAL=>'Comm on Call'
    );

$all_rec=array();
$edit_emp_array = array();
$intime1=''; $intime2='';$intime3='';
$outtime1=''; $outtime2='';$outtime3='';
if($_GET['edit']){
    
    $emp_id=decode_string($_GET['edit']);
        
    $all_rec = $wpdb->get_results($wpdb->prepare("select em.emp_firstname,em.emp_lastname, sm.* , smsm.* from ".$wpdb->prefix."sms_master as smsm join ".$wpdb->prefix."shift_master as sm on smsm.emp_id=sm.emp_id join ".$wpdb->prefix."employee_master as em on smsm.emp_id= em.id  where smsm.date=%s and smsm.emp_id=%d",strtotime($form_date),$emp_id),ARRAY_A);
$temp_array = array();
foreach($all_rec as $emp_data){
    $temp_array[] = $emp_data['shift'];
}

array_multisort($temp_array,$all_rec);



}

 $output.='<div>
 <form id="date_pick_form">
 <div class="date"><lable>'.DATE.'</label>
 <input type="text" name="dash_date" id="dash_date" value="'.$form_date.'" />

 </form>

<a class="anchor_btn" href='.get_permalink(get_option('shift_setup_id')).'>Shift Setup</a></div>
<div class="top_form_dash">
 <form id="edit_emp_dash" method="post">
 <div class="box-top"> <div class="box-top-left"> <h3>'.EMP_NAME.'</h3>
     <label>'.$all_rec[0]['emp_firstname'].' '.$all_rec[0]['emp_lastname'].'</label>
      
</div>
     
     
<div class="employee-from-outer">
   <div class="box-outer"> <h3>'.APP1.'</h3> <div class="box">
	<label>Client Name:</label><input type="text" name="client_name_app1" value="'.$all_rec[0]['client_name'].'">
	<div class="middiv">
	<label> '.CODE.':</label><select name="emp_code1" class="select-code">
                 <option value="">'.SELECT.'</option>';
                 foreach($code_array as $key=>$value){
                  $output .= '<option value="'.$key.'" '.($all_rec[0]['code']==$key?'selected="selected"':'').'>'.$value.'</option>';
                 }
		 
		 $output.='
		 </select>
	<div class="in_out">
         <label>In Time:</label><input type="text" class="other_time" name="in_time_app1" value="'.($all_rec[0]['in_time']?date('h:i A',american_utc_time($all_rec[0]['in_time'])):'').'">    
	 <label>Out Time:</label><input type="text" class="other_time" name="out_time_app1" value="'.($all_rec[0]['out_time']?date('h:i A',american_utc_time($all_rec[0]['out_time'])):'').'">
	 <input type="hidden" name="shift_id_1" value="'.$all_rec[0]['id'].'"/>
	 </div>
    '.get_break_time($all_rec[0]['id'],$all_rec[0]['date'],1).'
     </div>
     </div>
     
     </div>
     
     <div class="box-outer"> <h3>'.APP2.'</h3> <div class="box">
     <label>Client Name:</label><input type="text" name="client_name_app2" value="'.$all_rec[1]['client_name'].'">
     <div class="middiv" >
	<label> '.CODE.':</label>
	<select name="emp_code2" class="select-code">
                 <option value="">'.SELECT.'</option>';
                 foreach($code_array as $key=>$value){
                  $output .= '<option value="'.$key.'" '.($all_rec[1]['code']==$key?'selected="selected"':'').'>'.$value.'</option>';
                 }
		 $output.='
	</select>
	<div class="in_out">
         <label>In Time:</label>
	 <input class="other_time" type="text" name="in_time_app2" value="'.($all_rec[1]['in_time']?date('h:i A',american_utc_time($all_rec[1]['in_time'])):'').'">
        <label>Out Time:</label>
	<input class="other_time" type="text" name="out_time_app2" value="'.($all_rec[1]['out_time']?date('h:i A',american_utc_time($all_rec[1]['out_time'])):'').'">
	<input type="hidden" name="shift_id_2" value="'.$all_rec[1]['id'].'"/>
           </div>
	   '.get_break_time($all_rec[1]['id'],$all_rec[1]['date'],1).'
	   </div>
	   </div>
	   </div>
           
    <div class="box-outer"> <h3>'.APP3.'</h3>  <div class="box last">
    <label>Client Name:</label><input type="text" name="client_name_app3" value="'.$all_rec[2]['client_name'].'">
    <div class="middiv">
	<label> '.CODE.':</label><select name="emp_code3" class="select-code">
                 <option value="">'.SELECT.'</option>';
                 foreach($code_array as $key=>$value){
                  $output .= '<option value="'.$key.'" '.($all_rec[2]['code']==$key?'selected="selected"':'').'>'.$value.'</option>';
                 }
		 $output  .='
		 </select>
		 <div class="in_out">
              <label>In Time:</label><input type="text" class="other_time" name="in_time_app3" value="'.($all_rec[2]['in_time']?date('h:i A',american_utc_time($all_rec[2]['in_time'])):'').'">
	      <label>Out Time:</label><input type="text" class="other_time" name="out_time_app3" value="'.($all_rec[2]['out_time']?date('h:i A',american_utc_time($all_rec[2]['out_time'])):'').'">
	      <input type="hidden" name="shift_id_2" value="'.$all_rec[2]['id'].'"/>
           </div>
	   '.get_break_time($all_rec[2]['id'],$all_rec[2]['date'],1).'
	   </div>
	   </div></div>
            <div class="box-top-right-mobile">
	    <input type="hidden" name="edit_emp_id" value="'.($emp_id?encode_string($emp_id):'').'">
      <input type="submit" name="submit" id="submit_btn"  value="Update">
<a class="anchor_btn" href="'.get_permalink(get_option('emp_dashboard_id')).'">Cancel</a> 
     </div>
 </form>
 </div>
 </div>';



$output .='<div>

</div>';
 
$all_emp = $wpdb->get_results($wpdb->prepare("select em.emp_firstname,em.emp_lastname, sm.* , smsm.* from ".$wpdb->prefix."sms_master as smsm join ".$wpdb->prefix."shift_master as sm on smsm.emp_id=sm.emp_id join ".$wpdb->prefix."employee_master as em on smsm.emp_id= em.id  where smsm.date=%s ",strtotime($form_date)),ARRAY_A);
 
 
foreach($all_emp as $emp_data){
    if(!isset($emp_array[$emp_data['emp_id']])){
	$emp_array[$emp_data['emp_id']] = array();
    }
    $emp_array[$emp_data['emp_id']][] = $emp_data;
       
    
}
if(!empty($emp_array)){
foreach ($emp_array as $key=>$empdata){
    $temp_array = array();
     foreach($empdata as $data){
	
	$temp_array[] = $data['shift'];
     }
     array_multisort($temp_array,$emp_array[$key]);
}
 
}

$output .='<div class="dashboard_page">


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th>'.EMP_NAME.'</th>
    <th>'.APP1.'</th>
    <th>'.APP2.'</th>
    <th>'.APP3.'</th>
    
  </tr>';
  
  if(empty($emp_array)){
				$output.='<tr><td colspan="5">'.NO_SMS_RECORD_EXIST.'</td></tr>';
			}else{

 foreach($emp_array as $key=>$emp){
      $i=0;
    $output.='<tr>
    <td><div class="tbl_client_name">'.ucwords($emp[$i]['emp_firstname']).' '.ucwords($emp[$i]['emp_lastname']).'</div>
    <a href="'.add_query_arg(array('dash_date'=>$_GET['dash_date']?$_GET['dash_date']:date('Y-m-d'),'edit'=>encode_string($emp[$i]['emp_id'])),get_permalink(get_option('emp_dashboard_id'))).'">Edit</a>
    </td>';
    
    foreach($emp as $e){
	$diff='';
	
	if($e['out_time']!=''){
	    $blue_img = 'blue-on.png';
	}
	
	
	
   $output .='
   
    <td> <div class="status_img_div">';
    if($e['shift']==1){
	$in_time1= strtotime(date('h:i A',american_utc_time($e['in_time'])));
	
	$fisrtsfhtin_time = strtotime($e['firstapp_time']);
	
	
	
	$diff = (int)($in_time1) - (int)($fisrtsfhtin_time);
	
	 if($diff > DELAY_TIME_IN_SEC){
	  
	 $output .='<img src="'.plugins_url("img/green-off.png", __FILE__ ).'" />
    <img src="'.plugins_url("img/red-on.png", __FILE__ ).'" />';
	 }
	 else{
	   
	     $output .='<img src="'.plugins_url("img/green-on.png", __FILE__ ).'" />
    <img src="'.plugins_url("img/red-off.png", __FILE__ ).'" />';
	 }
	}
	if($e['shift']==2){
	$in_time1= strtotime(date('h:i A',american_utc_time($e['in_time'])));
	$fisrtsfhtin_time = strtotime($e['secondapp_time']);
	 $diff = $in_time1-$fisrtsfhtin_time;
	 if($diff > DELAY_TIME_IN_SEC){
	    $output .='<img src="'.plugins_url("img/green-off.png", __FILE__ ).'" />
    <img src="'.plugins_url("img/red-on.png", __FILE__ ).'" />';
	 }else{
	    $output .='<img src="'.plugins_url("img/green-on.png", __FILE__ ).'" />
    <img src="'.plugins_url("img/red-off.png", __FILE__ ).'" />';
	 }
	}
	if($e['shift']==3){
	$in_time1= strtotime(date('h:i A',american_utc_time($e['in_time'])));
	$fisrtsfhtin_time = strtotime($e['thirdapp_time']);
	 $diff = $in_time1-$fisrtsfhtin_time;
	 if($diff > DELAY_TIME_IN_SEC){
	    $output .='<img src="'.plugins_url("img/green-off.png", __FILE__ ).'" />
    <img src="'.plugins_url("img/red-on.png", __FILE__ ).'" />';
	 }else{
	     $output .='<img src="'.plugins_url("img/green-on.png", __FILE__ ).'" />
    <img src="'.plugins_url("img/red-off.png", __FILE__ ).'" />';
	 }
	}
    $output .='
   
    <img src="'.plugins_url("img/$blue_img", __FILE__ ).'" />
    </div>
    <div class="cname_code_div">
    <label><span class="heading_span">Client Name:</span><span class="value_span"> '.$e['client_name'].'</span></label> 
    <label><span class="heading_span">Code:</span><span class="value_span">'.$code_array[$e['code']].'</span> </label>
    </div>
    <div class="in_out_time_div">
    	<label>In Time:'.date('h:i A',american_utc_time($e['in_time'])).'
	<label>Out Time: '.date('h:i A',american_utc_time($e['out_time'])).'
	</div>
	<div class="break_out_in_div">
	'.get_break_time($e['id'],$e['date'],0).'
	</div>
	
    </td>
    ';
    
 }
 $output.='</tr>';
 $i++;
 }
 
}
  $output .='   
</table>
</div>
';

function american_utc_time($cust_time){
    if($cust_time!='')
    $cust_time -=18000;
    
    return $cust_time;
}

function get_break_time($sms_id,$my_date,$edit){
    global $wpdb;
    $data_break ='';
   
    $result = $wpdb->get_results($wpdb->prepare("select * from ".$wpdb->prefix."break_table where sms_id=%d and date=%s order by id",$sms_id,$my_date),ARRAY_A);
    if($edit==0){
	foreach($result as $data){
	    $data_break .='<label>Break Out: '.date('h:i',american_utc_time($data['break_out'])).' </label>
	    <label>Break In: '.date('h:i',american_utc_time($data['break_in'])).' </label>';
	}
    }else{
	foreach($result as $data){
	$data_break .='<label>Break Out: </label><input class="other_time" breakid='.$data['id'].' type="text" size="4" name="in_time"  value="'.date('h:i A',american_utc_time($data['break_out'])).'">
	
	<label>Break In: </label><input class="other_time" breakid='.$data['id'].' type="text" size="4" name="out_time"  value="'.date('h:i A',american_utc_time($data['break_in'])).'">';
    }
    }
    return $data_break;
    
}
return $output;