<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	require_once('ic-woocommerce-tax-report-function.php');

if ( ! class_exists( 'IC_WooCommerce_Tax_Report_Addons' ) ) {
	class IC_WooCommerce_Tax_Report_Addons extends IC_WooCommerce_Tax_Report_Function{
		private $token;
		
		private $api;
		
		public $constants 	=	array();
		
		public $plugin_key  =   "ic-tax-addons";
	
		/*public function __construct($constants) {
			global $ic_plugin_activated;
			
			$this->constants 	= $constants;
					
			$this->token 		= $this->constants['plugin_key'];
			
		}*/
		
		
		public function page_init() {
			
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.','icwoocommerce_textdomains' ) );
			}
			
			$this->define_constant();
			$this->display_content();
			
			
		}
		
		function define_constant(){
			if(!defined('WC_IS_MIS_FILE_PATH')) define( 'WC_IS_MIS_FILE_PATH', dirname( __FILE__ ) );
			if(!defined('WC_IS_MIS_DIR_NAME')) 	define( 'WC_IS_MIS_DIR_NAME', basename( WC_IS_MIS_FILE_PATH ) );
			if(!defined('WC_IS_MIS_FOLDER')) 	define( 'WC_IS_MIS_FOLDER', dirname( plugin_basename( __FILE__ ) ) );
			if(!defined('WC_IS_MIS_NAME')) 		define(	'WC_IS_MIS_NAME', plugin_basename(__FILE__) );
			if(!defined('WC_IS_MIS_URL')) 		define( 'WC_IS_MIS_URL', WP_CONTENT_URL . '/plugins/' . WC_IS_MIS_FOLDER );
			$this->constants['plugin_url'] 		= WC_IS_MIS_URL;
		}
		
		function display_content(){
?>
		<!--<script type="text/javascript" src="http://sam152.github.io/Javascript-Equal-Height-Responsive-Rows/grids.js"></script>
		<script type="text/javascript">
			jQuery(function($) {
				$('.col-md-4').responsiveEqualHeightGrid();
			});
		</script>-->
			<div class="ic_tax_report">
				<h2><?php _e('Other Plug-ins','icwoocommercemis_textdomains') ?></h2> 
				<div class="ic_plugins_container">
					<div class="row">
						<div class="col-md-4">
							<div class="ic_other_plugins">
								<div class="ic_plugin_img">
									<img src="<?php echo $this->constants['plugin_url']?>/assets/images/golden_report.jpg" alt="WooCommerce Advance Sales Report (Gold Version)" />
								</div>
								<div class="ic_plugin_content">
									<h2><a href="http://plugins.infosofttech.com/products/woocommerce-advance-sales-report/">WooCommerce Report (Gold Version)</a></h2>
									<span class="amount">$79</span>
									<ul class="UlList">
										<li>Summary detail on <strong>dashboard</strong></li>
										<li>Order item detail and normal detail report</li>
										<li><strong>Product</strong>, customer, recent order, coupon,<strong>refund</strong> detail report and many more.</li>
										<li>7 different <strong>crosstab report</strong></li>
										<li><strong>Product variation</strong> wise report</li>
										<li>Simple and variation stock List</li>
										<li>Export to <strong>csv</strong>, <strong>excel</strong>, <strong>pdf</strong>, <strong>print</strong>, <strong>invoice</strong></li>
										<li>Auto Email Reporting</li>
										<li><strong>Total 25+ reporting</strong></li>
									</ul>
									<div class="ic_readmore"><a href="http://plugins.infosofttech.com/products/woocommerce-advance-sales-report/">Read More</a></div>
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="ic_other_plugins">
								<div class="ic_plugin_img">
									<img src="<?php echo $this->constants['plugin_url']?>/assets/images/premium-gold-report.jpg" alt="WooCommerce Advance Sales Report (Premium Gold Version)">
								</div>
								
								<div class="ic_plugin_content">
									<h2><a href="http://plugins.infosofttech.com/woocommerce-advance-sales-report-premium-gold/">WooCommerce Report (Premium Gold Version)</a></h2>
									<span class="amount">$169</span>
									<ul class="UlList">
										<li>Improvised Dashboard (today’s, total, other useful summary)</li>
										<li>Sales Summary by Map and graph View</li>
										<li><strong>Projected Vs Actual Sales</strong></li>
										<li>Detail reports</li>
										<li>8 different all detail report</li>
										<li>8 different crosstab report</li>
										<li>Variation reporting with Advance Variation Filters</li>
										<li>Simple and variation <strong>stock List</strong></li>
										<li>Projected/Actual sales report</li>
										<li>Tax report by city, state, country, tax name many more</li>
										<li><strong>Total 40+ reports</strong></li>
									</ul>
									<div class="ic_readmore"><a href="http://plugins.infosofttech.com/woocommerce-advance-sales-report-premium-gold/">Read More</a></div>
								</div>
							</div>
						</div>
						
						
						
						<div class="col-md-4">
							<div class="ic_other_plugins">
								<div class="ic_plugin_img">
									<img src="<?php echo $this->constants['plugin_url']?>/assets/images/inventory.jpg" alt="WooCommerce Inventory Plugin" />
								</div>
								
								<div class="ic_plugin_content">
									<h2><a href="http://plugins.infosofttech.com/products/woocommerce-inventory-management/">Inventory Management</a></h2>
									<ul class="UlList">
										<li><strong>Opening stock report</strong></li>
										<li><strong>Purchase Entry</strong></li>
										<li>Stock Adjustment Entry</li>
										<li><strong>Vendor Details</strong></li>
										<li><strong>Stock/Item Ledger</strong></li>
										<li>Purchase List</li>
										<li><strong>Stock adjustment List</strong></li>
										<li>Other charges List</li>
										<li>Location List</li>
										<li><strong>Product ledger report</strong></li>
									</ul>
									<div class="ic_readmore"><a href="http://plugins.infosofttech.com/products/woocommerce-inventory-management/">Read More</a></div>
								</div>
							</div>
						</div>
						
						
						
						<div class="col-md-4">
							<div class="ic_other_plugins">
								<div class="ic_plugin_img">
									<img src="<?php echo $this->constants['plugin_url']?>/assets/images/subscription.jpg" alt="WooCommerce Subscription" />
								</div>
								
								<div class="ic_plugin_content">
									<h2><a href="http://plugins.infosofttech.com/woocommerce-subscription-report/">Subscription Report</a></h2>
									<!--<span class="amount">$129</span>-->
									<ul class="UlList">
										<li>Subscription wise <strong>dashboard summaries</strong></li>
										<li>Top n Subscription Countries</li>
										<li><strong>Subscription Summary</strong></li>
										<li><strong>Subscription Item List</strong></li>
										<li><strong>Subscription Expire List</strong></li>
										<li>Subscription Payment List</li>
										<li>Daily summary</li>
										<li><strong>Free or Trial Subscription Due</strong></li>
										<li>Free or Trial Subscription Due</li>
										<li><strong>Prod. / Month Crosstab</strong></li>
									</ul>
									<div class="ic_readmore"><a href="http://plugins.infosofttech.com/woocommerce-subscription-report/">Read More</a></div>
								</div>
							</div>
						</div>
						
						
						
						
					</div>
				</div>
			</div>
<?php   
		}
		
	}
}
