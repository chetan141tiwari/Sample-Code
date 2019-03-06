jQuery(document).ready(function(){
    
    jQuery("#sms_api_form").validate({
                        
                       	rules: {
			
			account_sid: {
				required: true				//this field can't be left blank
                        },
                        auth_token:{
                                    required :true
                        },
                        my_phone_number:{
                                    required :true
                        }
                  },
                  
            	messages: {
			
			account_sid: {
 				required: 'Please enter account sid'	// error message if lname field left empty
                                
			},
                        auth_token:{
                                    required :'Please enter auth token'
                        },
                        my_phone_number:{
                                    required :'Please enter phone number'
                        }
		  }
                  });
});