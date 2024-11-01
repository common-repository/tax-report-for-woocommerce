<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'IC_WooCommerce_Tax_Report_Function' ) ) { 
	class IC_WooCommerce_Tax_Report_Function{
		function __construct() {
		}
		function get_request($request=NULL,$value =''){
			$r = isset($_REQUEST[$request])?$_REQUEST[$request]:$value;
			return $r;
		}
		function print_array($data){
			print "<pre>";
			print_r($data);
			print "</pre>";
		}
		function get_date($date_format ="Y-m-d"){
			$date = date_i18n($date_format);
			return $date;
		}
		function get_pagination($total_pages = 50,$limit = 10,$adjacents = 3,$targetpage = "admin.php?page=RegisterDetail",$request = array()){		
				
				if(count($request)>0){
					unset($request['p']);
					//$new_request = array_map(create_function('$key, $value', 'return $key."=".$value;'), array_keys($request), array_values($request));
					//$new_request = implode("&",$new_request);
					//$targetpage = $targetpage."&".$new_request;
				}
				
				
				/* Setup vars for query. */
				//$targetpage = "admin.php?page=RegisterDetail"; 	//your file name  (the name of this file)										
				/* Setup page vars for display. */
				if(isset($_REQUEST['p'])){
					$page = $_REQUEST['p'];
					$_GET['p'] = $page;
					$start = ($page - 1) * $limit; 			//first item to display on this page
				}else{
					$page = false;
					$start = 0;	
					$page = 1;
				}
				
				if ($page == 0) $page = 1;					//if no page var is given, default to 1.
				$prev = $page - 1;							//previous page is page - 1
				$next = $page + 1;							//next page is page + 1
				$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
				$lpm1 = $lastpage - 1;						//last page minus 1
				
				
				
				$label_previous = __('previous', 'icwoocommerce_textdomains');
				$label_next = __('next', 'icwoocommerce_textdomains');
				
				/* 
					Now we apply our rules and draw the pagination object. 
					We're actually saving the code to a variable in case we want to draw it more than once.
				*/
				$pagination = "";
				if($lastpage > 1)
				{	
					$pagination .= "<div class=\"pagination\">";
					//previous button
					if ($page > 1) 
						$pagination.= "<a href=\"$targetpage&p=$prev\" data-p=\"$prev\">{$label_previous}</a>\n";
					else
						$pagination.= "<span class=\"disabled\">{$label_previous}</span>\n";	
					
					//pages	
					if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
					{	
						for ($counter = 1; $counter <= $lastpage; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>\n";
							else
								$pagination.= "<a href=\"$targetpage&p=$counter\" data-p=\"$counter\">$counter</a>\n";					
						}
					}
					elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
					{
						//close to beginning; only hide later pages
						if($page < 1 + ($adjacents * 2))		
						{
							for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
							{
								if ($counter == $page)
									$pagination.= "<span class=\"current\">$counter</span>\n";
								else
									$pagination.= "<a href=\"$targetpage&p=$counter\" data-p=\"$counter\">$counter</a>\n";					
							}
							$pagination.= "...";
							$pagination.= "<a href=\"$targetpage&p=$lpm1\" data-p=\"$lpm1\">$lpm1</a>\n";
							$pagination.= "<a href=\"$targetpage&p=$lastpage\" data-p=\"$lastpage\">$lastpage</a>\n";		
						}
						//in middle; hide some front and some back
						elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
						{
							$pagination.= "<a href=\"$targetpage&p=1\" data-p=\"1\">1</a>\n";
							$pagination.= "<a href=\"$targetpage&p=2\" data-p=\"2\">2</a>\n";
							$pagination.= "...";
							for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
							{
								if ($counter == $page)
									$pagination.= "<span class=\"current\">$counter</span>\n";
								else
									$pagination.= "<a href=\"$targetpage&p=$counter\" data-p=\"$counter\">$counter</a>\n";					
							}
							$pagination.= "...";
							$pagination.= "<a href=\"$targetpage&p=$lpm1\" data-p=\"$lpm1\">$lpm1</a>\n";
							$pagination.= "<a href=\"$targetpage&p=$lastpage\" data-p=\"$lastpage\">$lastpage</a>\n";		
						}
						//close to end; only hide early pages
						else
						{
							$pagination.= "<a href=\"$targetpage&p=1\" data-p=\"1\">1</a>\n";
							$pagination.= "<a href=\"$targetpage&p=2\" data-p=\"2\">2</a>\n";
							$pagination.= "...";
							for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
							{
								if ($counter == $page)
									$pagination.= "<span class=\"current\">$counter</span>\n";
								else
									$pagination.= "<a href=\"$targetpage&p=$counter\" data-p=\"$counter\">$counter</a>\n";					
							}
						}
					}
					
					//next button
					if ($page < $counter - 1) 
						$pagination.= "<a href=\"$targetpage&p=$next\" data-p=\"$next\">{$label_next}</a>\n";
					else
						$pagination.= "<span class=\"disabled\">{$label_next}</span>\n";
					$pagination.= "</div>\n";		
				}
				return $pagination;
			
		}//End Get Pagination
		function get_all_post_meta($post_id = 202,$meta_key = array()){
		
		
		
			global $wpdb;
			$query  ="";	
			$query .=" SELECT  *  ";
			$query .=" FROM {$wpdb->prefix}postmeta as postmeta  ";
			$query .=" WHERE 1=1 ";
			$query .=" AND postmeta.post_id= {$post_id} ";
			if (count($meta_key)>0 ){
				$str_meta_key = implode("','", $meta_key );
				$query .=" AND postmeta.meta_key IN ( '{$str_meta_key}' )";
			}
			$row = $wpdb->get_results( $query);
			$meta_row = array();
			foreach($row as $key=>$value){
				/*
				$value->meta_key;
				$value->meta_value;
				*/
				
				$meta_row[ltrim($value->meta_key,"_")] = $value->meta_value;
				
			}
			return $meta_row;
		}
		function ic_cr_get_country_name($country_code){			
			$country      = $this->get_wc_countries();//Added 20150225					
			return $country->countries[$country_code];
		}
		function get_wc_countries(){
				return class_exists('WC_Countries') ? (new WC_Countries) : (object) array();
			}
		function get_columns($report_name ="tax_report"){
			$columns  = array();
			if ($report_name=="tax_report"){
				$columns["order_id"] 		    = esc_html__("Order ID",'icwoocommercetax');
				$columns["order_date"] 		    = esc_html__("Order Date",'icwoocommercetax') ;
				$columns["order_item_name"]     = esc_html__("Order Item Name",'icwoocommercetax') ;
				$columns["billing_country"]     = esc_html__("Billing Country",'icwoocommercetax') ;
				$columns["billing_state"] 	    = esc_html__("Billing State",'icwoocommercetax') ;
				$columns["billing_postcode"]    = esc_html__("Billing Postcode",'icwoocommercetax') ;
				$columns["tax_amount"] 		    = esc_html__("Tax Amount",'icwoocommercetax') ;
				$columns["shipping_tax_amount"] = esc_html__("Shipping Tax Amount",'icwoocommercetax') ;
				$columns["total_tax"]     		= esc_html__("Total Tax",'icwoocommercetax') ;
			}
			if ($report_name=="recent_order"){
				$columns["order_id"] 		    	= esc_html__("Order Id",'icwoocommercetax') ;
				$columns["order_date"]     			= esc_html__("Order Date",'icwoocommercetax') ;
				$columns["billing_first_name"] 		= esc_html__("Billing First Name",'icwoocommercetax') ;
				$columns["billing_email"]    		= esc_html__("Billing Email",'icwoocommercetax') ;
				$columns["payment_method_title"]    = esc_html__("Payment Method Title",'icwoocommercetax') ;
				$columns["order_currency"] 			= esc_html__("Order Currency",'icwoocommercetax') ;
				$columns["order_total"] 	   		= esc_html__("Order Total",'icwoocommercetax') ;
			}
			if ($report_name=="dashbaord_stock_summary"){
				$columns["order_item_name"] 		= esc_html__("Tax Name",'icwoocommercetax') ;
				$columns["tax_amount"] 		    	= esc_html__("Tax Amount",'icwoocommercetax') ;
				$columns["shipping_tax_amount"]     = esc_html__("Shipping Tax Amount",'icwoocommercetax') ;
				$columns["total_tax"]     		    = esc_html__("Total Tax",'icwoocommercetax') ;
				
			}
			return $columns;
		}
	}
}
?>