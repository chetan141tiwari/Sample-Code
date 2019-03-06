jQuery(document).ready(function(){
    var phone1chk = '';
    var phone2chk = '';
    var phone3chk = '';
    
    jQuery("#first_name").focus();
    jQuery('input[type="submit"]').removeAttr('disabled');
  
    jQuery("#first_cellno ").inputmask("mask",{"mask":"999-999-9999"});
    jQuery.validator.addMethod('validphone',function(){
        if(jQuery("#first_cellno").inputmask("isComplete")){
            return true;
            }
            return false;
    },'Please enter valid cell number');
    
    jQuery("#second_cellno ").inputmask("mask",{"mask":"999-999-9999"});
    jQuery.validator.addMethod('validphone2',function(){
        if(jQuery("#second_cellno").val() =='' || jQuery("#second_cellno").inputmask("isComplete")){
            return true;
            }
            return false;
    },'Please enter valid cell number');
    
    jQuery("#third_cellno ").inputmask("mask",{"mask":"999-999-9999"});
    jQuery.validator.addMethod('validphone3',function(){
        if(jQuery("#third_cellno").val() =='' || jQuery("#third_cellno").inputmask("isComplete")){
            return true;
            }
            return false;
    },'Please enter valid cell number');
    
    var ajaxurl=ajax_object.ajax_url;
    jQuery("#first_cellno").change(function(){
                  var data = {
                            action: 'check_userphones',
                            phone_num1: jQuery(this).val()
                    };
                                     jQuery.post(ajaxurl,data, function(response) {
                                     phone1chk=response;
                   });
                    
                  });
    jQuery.validator.addMethod('existphone1', function () {		
                                    
                              if (parseInt(phone1chk) >= 1) {
                                               return false;
                              }
                              return true;
                              
                  },'Cell phone number is already used. Please enter another number');
    
    jQuery("#second_cellno").change(function(){
                  var data = {
                            action: 'check_userphones',
                            phone_num1: jQuery(this).val()
                    };
                                     jQuery.post(ajaxurl,data, function(response) {
                                     phone2chk=response;
                   });
                    
                  });
    jQuery.validator.addMethod('existphone2', function () {		
                                    
                              if (parseInt(phone2chk) >= 1) {
                                               return false;
                              }
                              return true;
                              
                  },'Cell phone number is already used. Please enter another number');
    
    jQuery("#third_cellno").change(function(){
                  var data = {
                            action: 'check_userphones',
                            phone_num1: jQuery(this).val()
                    };
                                     jQuery.post(ajaxurl,data, function(response) {
                                     phone3chk=response;
                   });
                    
                  });
    jQuery.validator.addMethod('existphone3', function () {		
                                    
                              if (parseInt(phone3chk) >= 1) {
                                               return false;
                              }
                              return true;
                              
                  },'Cell phone number is already used. Please enter another number');
    
    
    jQuery("#add_employee").validate({
                        
                       	rules: {
			
			first_name: {
				required: true				//this field can't be left blank
                        },
                        last_name : {
                                    required :true
                        },
                        first_cellno : {
                                    required :true,
                                    validphone: true,
				    existphone1: true
                        },
                        second_cellno : {
                                    
                                    validphone2: true,
				    existphone2: true
                        },
                        third_cellno : {
                                    validphone3: true,
				    existphone3: true
                        },
                        emptype:{
                                    required :true
                        },
                        empfrom:{
                                    required :true
                        }
                  },
                  
            	messages: {
			
			first_name: {
 				required: 'Please enter first name'	// error message if lname field left empty
                                
			},
                        last_name : {
                                    required :'Please enter last name'
                        },
                        first_cellno: {
                                    required :'Please enter Cell Phone number'
                        },
                        emptype:{
                                    required :'Please select employee type'
                        },
                        empfrom:{
                                    required :'Please select employee from'
                        }
		  }
                  });
    jQuery("#first_name").alpha({allow:' '});
    jQuery("#last_name").alpha({allow:' '});
    
    
});