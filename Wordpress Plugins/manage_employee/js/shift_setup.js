jQuery(document).ready(function(){
    jQuery("#submit_btn").focus();
    jQuery('input[type="radio"][class="other_radio"]').click(function(){
        jQuery(this).siblings().show();
        var nameinput = jQuery(this).attr('name');
        jQuery('input[type="text"][name=other_'+nameinput.split('_').pop()+']').show();
    });
    
    jQuery('input[type="radio"][class="no_other"]').click(function(){
        var nameinput2 = jQuery(this).attr('name');
        jQuery('input[type="text"][name=other_'+nameinput2.split('_').pop()+']').hide();
    });
    
     
    
    jQuery('.other_time').timepicker({ 'timeFormat': 'hh:mm TT' });
    
    jQuery("#shift_form").validate({
                        
                       	rules: {
			
			emp_id: {
				required: true				//this field can't be left blank
                        },
                        radioapp_1:{
                            required: true
                        },
                        emp_code1 : {
                                    required :true
                        },
                        emp_code2:{
                             required:'input[name=radioapp_2]:checked'
                        },
                        emp_code3:{
                             required:'input[name=radioapp_3]:checked'
                        },
                        other_1:{
                            required:'input[id=radio4_app1]:checked' 
                        },
                        other_2:{
                            required:'input[id=radio4_app2]:checked' 
                        },
                        other_3:{
                            required:'input[id=radio4_app3]:checked' 
                        },
                        radioapp_2:{
                            required : function(){
                                        if(jQuery("select[name='emp_code2']").val()>0){
                                            return true;
                                            }else{
                                            return false;
                                            }
                                    }
                            
                        },
                        radioapp_3:{
                            required : function(){
                                        if(jQuery("select[name='emp_code3']").val()>0){
                                            return true;
                                            }else{
                                            return false;
                                            }
                                    }
                            
                        }
                  },
                  
            	messages: {
			
			emp_id: {
 				required: 'Please select employee'	// error message if lname field left empty
                                
			},
                        emp_code1 : {
                                    required :'Please select employee code'
                        },
                        emp_code2:{
                             required:'Please select employee code'
                        },
                        emp_code3:{
                             required:'Please select employee code'
                        },
			radioapp_1:{
			    required:'Please select appointment time'
			},
			radioapp_2:{
			    required:'Please select appointment time'
			},
			radioapp_3:{
			    required:'Please select appointment time'
			},
			other_1:{
                            required:'Please enter appointment time'
                        },
                        other_2:{
                            required:'Please enter appointment time'
                        },
                        other_3:{
                            required:'Please enter appointment time'
                        }
		  }
        });
    
    
    
});