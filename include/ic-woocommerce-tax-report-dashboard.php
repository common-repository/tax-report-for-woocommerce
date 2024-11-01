<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'IC_WooCommerce_Tax_Report_Dashboard' ) ) {
	include_once("ic-woocommerce-tax-report-function.php"); 
	class IC_WooCommerce_Tax_Report_Dashboard extends  IC_WooCommerce_Tax_Report_Function{
		function __construct() {
		}
		function page_init(){
			/*
			$start_date = $this->get_date();
			$end_date = $this->get_date();
			
			$this->get_tax_summary();
			$this->get_recent_order();
			
			$this->get_tax_by_date($start_date ,$end_date);
			$this->get_tax_by_date();
			*/
			$this->get_grid();
		}
		function get_grid(){
			$start_date = $this->get_date();
			$end_date = $this->get_date();
			$this->get_tax_summary();
			
			$this->get_recent_order();
			
			$this->get_tax_by_date($start_date ,$end_date);
			
			//$this->get_tax_by_date();
			
		}
		function get_tax_by_date($start_date =NULL ,$end_date =NULL){
			global $wpdb;
			
			$query = "";
			$query .=" SELECT    ";
			
			
			$query .=" SUM(ROUND(tax_amount.meta_value,2)) as tax_amount ";
			$query .=" ,SUM(ROUND(shipping_tax_amount.meta_value,2)) as shipping_tax_amount ";
			
			$query .=", (SUM(ROUND(tax_amount.meta_value,2)) + SUM(ROUND(shipping_tax_amount.meta_value,2)) )  as total_tax";
			//$query .=" ,tax.order_item_name ";
			
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
			
			if ($start_date && $end_date)
			$query .=" AND  date_format( post.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}' ";
			//$query .=" GROUP BY  tax.order_item_name ";
			
			$row = $wpdb->get_row( $query);
			//echo $row->total_tax;
			//$this->print_array($row);
		
			return $row ;
		}
		function get_tax_summary($start_date=NULL ,$end_date=NULL){
			global $wpdb;
			
			$start_date = $this->get_date();
			$end_date = $this->get_date();
			
			$column  =$this->get_columns("dashbaord_stock_summary");
			$query = "";
			$query .=" SELECT    ";
			
			
			$query .=" SUM(ROUND(tax_amount.meta_value,2)) as tax_amount ";
			$query .=" ,SUM(ROUND(shipping_tax_amount.meta_value,2)) as shipping_tax_amount ";
			$query .=" ,tax.order_item_name ";
			
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
			
			$query .=" GROUP BY  tax.order_item_name ";
			
			$row = $wpdb->get_results( $query);
			
			?>
			<div class="ic_tax_report">
			
				<h2 class="hide_for_print"><?php esc_html_e('WooTax Dashboard','icwoocommercetax')?></h2>
				<div class="ic_postbox">
					<h3><span class="title"><?php esc_html_e('Summary','icwoocommercetax')?></span></h3>
					<div class="row ic_summary">
						<div class="col-xs-3">
							<div class="ic_block ic_block-pink">
								<i class="fa fa-cube"></i>
								<h4><?php esc_html_e('Total Tax','icwoocommercetax')?></h4>
								<span class="ic_value">
									<?php
									  $total_tax = $this->get_tax_by_date();
									 echo wc_price(isset($total_tax->tax_amount)?$total_tax->tax_amount:0);
									 ?>
								</span>
							</div>
						</div>
						
						<div class="col-xs-3">
							<div class="ic_block ic_block-purple">
								<i class="fa fa-cube"></i>
								<h4><?php esc_html_e('Total Shipping Tax','icwoocommercetax')?></h4>
								<span class="ic_value">
								<span class="ic_value">
									<?php
									  $shipping_tax_amount = $this->get_tax_by_date();
									 echo wc_price(isset($shipping_tax_amount->shipping_tax_amount)?$shipping_tax_amount->shipping_tax_amount:0);
									 ?>
								</span>
							</div>
						</div>
						
						<div class="col-xs-3">
							<div class="ic_block ic_block-yellow">
								<i class="fa fa-cube"></i>
								<h4><?php esc_html_e('Today Tax','icwoocommercetax')?></h4>
								<span class="ic_value">
									<?php
									  $today_total_tax =  $this->get_tax_by_date($start_date,$end_date);
									  echo wc_price(isset($today_total_tax->tax_amount)?$today_total_tax->tax_amount:0);
									?>
								</span>
							</div>
						</div>
                        <div class="col-xs-3">
							<div class="ic_block ic_block-orange">
								<i class="fa fa-cube"></i>
								<h4><?php esc_html_e('Today Shipping Tax','icwoocommercetax')?></h4>
								<span class="ic_value">
									<?php
									  $today_shipping_tax_amount=  $this->get_tax_by_date($start_date,$end_date);
									  echo wc_price(isset($today_shipping_tax_amount->shipping_tax_amount)?$today_shipping_tax_amount->shipping_tax_amount:0);
									?>
								</span>
							</div>
						</div>
					</div>
				</div>
			
				
				<div class="ic_postbox">
					<h3><span class="title"><?php esc_html_e('Tax Summary By Tax Name','icwoocommercetax')?></span></h3>
					<div class="row ic_summary">
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
                        <td><?php  echo wc_price( ($value->tax_amount + $value->shipping_tax_amount)); ?></td>
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
				</div>				
			<?php
			
			//$this->print_array($row);
		}
		function get_recent_order(){
			global $wpdb;
			$meta_key = array();
			$column  =$this->get_columns("recent_order");
			
			$query = "SELECT  ";
			$query .=" post.ID as order_id ";
			$query .=" ,post.post_date as order_date ";
			$query .="  FROM {$wpdb->prefix}posts as post  ";
			$query .=" WHERE 1=1 ";
			$query .=" AND post.post_type='shop_order'";
			$query .=" AND post.post_status IN ('wc-on-hold','wc-processing','wc-completed')";
			$query .="  ORDER BY post.post_date DESC ";
			$query .=" LIMIT 10 "	;
			$row = $wpdb->get_results( $query);
			foreach($row as $key=>$value){
				$order_id =$value->order_id ;
				$all_meta = $this->get_all_post_meta($order_id,$meta_key);
				foreach($all_meta as $k=>$v){
					$row[$key]->$k =$v;
				}
			}
			
			?>
				<div class="ic_postbox">
					<h3><span class="title">Recent Order List</span></h3>
					<div class="row ic_summary">
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
                        <?php if ($k=="order_total") :?>
                        <td><?php echo wc_price($value->$k); ?></td>
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
				</div>
			</div>
			<?php
		}
	}
}
?>