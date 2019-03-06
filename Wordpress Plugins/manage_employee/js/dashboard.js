jQuery(document).ready(function(){

    jQuery('.other_time').timepicker({ 'timeFormat': 'hh:mm TT' });
    jQuery('#dash_date').datepicker({ dateFormat: 'yy-mm-dd',
                                       onSelect : function( dateText, inst ) {
   jQuery("#date_pick_form").submit();
  }
                                    });
});