// JavaScript Document
var p = 1;
jQuery(document).ready( function($) {
	
	
	
	//$( ".ic_tax_datepicker" ).datepicker();
	jQuery('.ic_tax_datepicker').datepicker({
        dateFormat : 'yy-mm-dd',
		maxDate: '+0d'
    });
	 
	$("#frm_tax_report").submit(function(e) {
		  // This does the ajax request
		  //ic_taxt_report_ajax_object
		  //ic_taxt_report_ajax_url
		//	alert(ic_taxt_report_ajax_object.ic_taxt_report_ajax_url);
			$.ajax({
				url: ajaxurl,
				data:$("#frm_tax_report").serialize(),
				success:function(data) {
					// This outputs the result of the ajax request
					console.log(data);
					//alert(data);
					$("._ic_data").html(data);
				},
				error: function(errorThrown){
					console.log(errorThrown);
					alert(JSON.stringify(errorThrown));
				}
			});  

		e.preventDefault();
	});
	
	//pagination
	$(document).on("click",".pagination a",function(e) {
		p = $(this).attr("data-p")
		$("._page").val(p);
		$( "#frm_tax_report" ).trigger( "submit" );
		return false;
		
	});
	$( "#frm_tax_report" ).trigger( "submit" );
});