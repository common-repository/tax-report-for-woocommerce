<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'IC_WooCommerce_Tax_Report_List' ) ) {
	include_once("ic-woocommerce-tax-report-function.php"); 
	class IC_WooCommerce_Tax_Report_List extends  IC_WooCommerce_Tax_Report_Function{
		function __construct() {
		}
		function page_init(){
			//$start_date= isset($_REQUEST["start_date"])?$_REQUEST["start_date"]:$this->get_date();	
			//$end_date= isset($_REQUEST["end_date"])?$_REQUEST["end_date"]:$this->get_date();	
			
			$start_date= $this->get_request("start_date",$this->get_date());
			$end_date= $this->get_request("end_date",$this->get_date());
			$input_type = "hidden";
			//$input_type = "text";
			
			//echo "Hello Anzar";
			//$this->get_query();
		?>
			<div class="ic_tax_report">
				<div id="navigation" class="hide_for_print ic_navigation ic_formwrap">
                        <div class="collapsible ic_section-header" id="section1"><h3><?php esc_html_e('Tax Detail Report','icwoocommercetax')?><span></span></h3></div>
                        <div class="container ic_search_report_form">
                            <div class="content">
                                <div class="search_report_form">
									<form id="frm_tax_report" name="frm_tax_report">
										<div class="form-table">
											<div class="form-group ic_form-group">
												<div class="ic_FormRow ic_firstrow">
													<div class="ic_label-text"><label for="start_date"><?php esc_html_e('From Date:','icwoocommercetax')?></label></div>
													<div class="ic_input-text"><input type="text" class="ic_tax_datepicker" name="start_date" id="start_date" readonly  value="<?php echo $start_date; ?>"></div>
												</div>
												<div class="ic_FormRow">
													<div class="ic_label-text"><label for="end_date"><?php esc_html_e('To Date:','icwoocommercetax')?></label></div>
													<div class="ic_input-text"><input type="text" class="ic_tax_datepicker" name="end_date" id="end_date" readonly  value="<?php echo $end_date; ?>"></div>
												</div>
											</div>
											
											<div class="ic_form-group">
												<div class="ic_FormRow">
													<input type="<?php echo $input_type ; ?>" name="action" value="ic_tax_report_action">
													<input type="<?php echo $input_type ; ?>" name="sub_action" value="ic_tax_report_list">
													<input type="<?php echo $input_type ; ?>" name="p"  value="1" class="_page">
													<input type="<?php echo $input_type ; ?>" name="limit"  value="10">
													<input type="submit" value="<?php esc_html_e('Search','icwoocommercetax')?>" class="ic_button">
												</div>
											</div>                                                
										</div>
									</form>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
				<div class="_ic_data"></div>
            <?php 
			
			?>
        <?php
		}
		
		function get_grid(){
			
			$limit = $this->get_request("limit",10);
			
			$row = $this->get_query("row");
			
			if(count($row)<=0){
				esc_html_e('Order not found.','icwoocommercetax');
				return false;
			}
			
			
			$count = $this->get_query("count");
	
			$column  =$this->get_columns();
			?>
				
				<div class="ic_table-responsive">
					<table class="widefat">
						<thead>
							<tr>
								<?php foreach($column as $key=>$value): ?>
								<th><?php echo $value; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
					<?php
					foreach($row as $key=>$value){
					?>
					<tr>
						<?php foreach($column as $k=>$v): ?>
						 <?php if ($k=="total_tax") :?>
                            <td style="text-align:right"><?php echo wc_price($value->tax_amount+$value->shipping_tax_amount); ?></td>
						 <?php elseif ($k=="shipping_tax_amount") :?>
                          <td style="text-align:right"><?php echo  wc_price($value->$k); ?></td>
                          <?php elseif ($k=="tax_amount") :?>
                          <td style="text-align:right"><?php echo  wc_price($value->$k); ?></td>
                           <?php elseif ($k=="billing_country") :?>
                             <td style="text-align:right"><?php echo $this->ic_cr_get_country_name($value->$k); ?></td>
                         <?php else: ?>
                         <td><?php echo $value->$k; ?></td>
						 <?php endif; ?>
						 <?php endforeach; ?>
					</tr>				
					<?php
					}
					?>
					</table>
				</div>
			</div>
			<?php
			echo $this->get_pagination($count ,$limit);
			
			//echo json_encode($count);
		}
		function get_query($type = "row"){
			global $wpdb;
			//$limit = 10;
			$start = 1;
			$meta_key = array();
			
			$start_date= $this->get_request("start_date",$this->get_date());
			$end_date= $this->get_request("end_date",$this->get_date());
			$p= $this->get_request("p",1);
			$limit= $this->get_request("limit",10);
			$start = ($p-1) * $limit;
			
			$query = "";
			
			if ($type =="count"){
				$query .=" SELECT  count(*) as count  ";
			
			}else{
				$query .=" SELECT    ";
			
			
				$query .=" post.ID as order_id ";
				$query .=" ,date_format( post.post_date, '%Y-%m-%d') as order_date ";
				$query .=" ,tax_amount.meta_value as tax_amount ";
				$query .=" ,shipping_tax_amount.meta_value as shipping_tax_amount ";
				
				
				$query .=" ,tax.order_item_name ";
			}
			
			
			$query .="  FROM {$wpdb->prefix}posts as post  ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as tax ON tax.order_id=post.ID ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as tax_amount ON tax_amount.order_item_id=tax.order_item_id ";
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as shipping_tax_amount ON shipping_tax_amount.order_item_id=tax.order_item_id ";
				
				
			$query .=" WHERE 1=1 ";
			$query .=" AND post.post_type='shop_order'";
			$query .=" AND post.post_status IN ('wc-on-hold','wc-processing','wc-completed')";
			
			$query .=" AND tax.order_item_type='tax'";
			
			$query .=" AND tax_amount.meta_key='tax_amount'";
			$query .=" AND shipping_tax_amount.meta_key='shipping_tax_amount'";
			
			$query .=" AND  date_format( post.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}' ";
			
			$query .="  ORDER BY post.post_date DESC ";
			
	
			if ($type =="count"){
				$row = $wpdb->get_var( $query);
			}else{
				$query .=" LIMIT $start, $limit "	;
				$row = $wpdb->get_results( $query);
			}
			
			if ($type =="row"){
				foreach($row as $key=>$value){
					$order_id =$value->order_id ;
					$all_meta = $this->get_all_post_meta($order_id,$meta_key);
					foreach($all_meta as $k=>$v){
						$row[$key]->$k =$v;
					}
				}
			}
			//$this->print_array($row);
			return $row;
		}
		function page_ajax(){
		
			$this->get_grid();
			die;
		}
		
		
			
	}
}
?>