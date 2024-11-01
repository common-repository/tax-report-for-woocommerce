<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
if ( ! class_exists( 'IC_WooCommerce_Tax_Report_Init' ) ) { 
	include_once("ic-woocommerce-tax-report-function.php");
	class IC_WooCommerce_Tax_Report_Init extends  IC_WooCommerce_Tax_Report_Function{
		function __construct() {
			add_action( 'admin_menu',  array(&$this,'my_plugin_menu') );	
			add_action( 'admin_enqueue_scripts',  array(&$this,'admin_enqueue_scripts' ));
			add_action( 'wp_ajax_ic_tax_report_action',  array(&$this,'ic_tax_report_action') );	
		}
		function admin_enqueue_scripts(){
			if (isset($_REQUEST["page"])){
				$page  =  $_REQUEST["page"];
			 //	wp_enqueue_script( 'ic-tax-report-script', plugin_dir_url( __FILE__ ) . '../js/ic-tax-report-script.js' );
				wp_enqueue_script( 'ic-tax-report-script', plugins_url( '../js/ic-tax-report-script.js', __FILE__ ), array('jquery') );
				wp_enqueue_script( 'ic-tax-report-script-list', plugins_url( '../js/ic-woocommerce-tax-report-list.js', __FILE__ ), array('jquery') );
				wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_enqueue_script('jquery-ui-core');
				//wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
				wp_localize_script('ic-tax-report-script', 'ic_taxt_report_ajax_object', array( 'ic_taxt_report_ajax_url' => admin_url( 'admin-ajax.php' )) );
				wp_enqueue_style('ic-woocommerce-tax-report-css', plugins_url( '../css/ic-woocommerce-tax-report.css', __FILE__ ));
				wp_enqueue_style('normalize_styles-css', plugins_url( '../css/ic-normalize.css', __FILE__ ));
				$this->wp_enqueue_jqueryui();
				
			}
		}
		
		function wp_enqueue_jqueryui(){
			global $wp_scripts;
			$ui = $wp_scripts->query('jquery-ui-core');				
			$protocol = is_ssl() ? 'https' : 'http';
			$url = "$protocol://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css";
			wp_register_style('jquery-ui-smoothness', $url, false, NULL);
			wp_enqueue_style( 'jquery-ui-smoothness');
		}
		
		function my_plugin_menu(){
			//$option["role"] = "read";
			$option["role"] = "manage_options";
			$option["menu"] = "tax-menu";
			add_menu_page(esc_html__('Woo Tax Report','icwoocommercetax'), esc_html__('Woo Tax Report','icwoocommercetax'), $option["role"], $option["menu"],  array(&$this,'add_page'), 'dashicons-awards','63.15' );
			add_submenu_page($option["menu"], esc_html__('Dashboard','icwoocommercetax'), 		  esc_html__('Dashboard','icwoocommercetax'), 		  $option["role"], 	$option["menu"] , array(&$this,'add_page'));
			add_submenu_page($option["menu"], esc_html__('Tax Report','icwoocommercetax'), 	 esc_html__('Tax Report','icwoocommercetax'), 		 $option["role"], 	"ic-tax-report" , array(&$this,'add_page'));
			add_submenu_page($option["menu"], esc_html__('Other Plug-ins','icwoocommercetax'), 	 esc_html__('Other Plug-ins','icwoocommercetax'), 		 $option["role"], 	"ic-tax-addons" , array(&$this,'add_page'));
		}
		function ic_tax_report_action(){
			//echo json_encode($_REQUEST);
			$sub_action=$this->get_request("sub_action");
			switch ($sub_action) {
				case "ic_tax_report_list":
					include_once("ic-woocommerce-tax-report-list.php");
					$obj = new IC_WooCommerce_Tax_Report_List();
					$obj->page_ajax();
					break;
				case "blue":
					echo "Your favorite color is blue!";
					break;
				case "green":
					echo "Your favorite color is green!";
					break;
				default:
					echo "Your favorite color is neither red, blue, nor green!";
			}
			die;
		}
		function add_page(){
			if (isset($_REQUEST["page"])){
				$page  =  $_REQUEST["page"];
				switch ($page) {
					case "ic-tax-report":
						include_once("ic-woocommerce-tax-report-list.php");
						$obj = new IC_WooCommerce_Tax_Report_List();
						$obj->page_init();
						break;
					case "tax-menu":
						include_once("ic-woocommerce-tax-report-dashboard.php");
						$obj = new IC_WooCommerce_Tax_Report_Dashboard();
						$obj->page_init();
						break;
					case "ic-tax-addons":
						include_once("ic-woocommerce-tax-report-addons.php");
						$obj = new IC_WooCommerce_Tax_Report_Addons();
						$obj->page_init();
						break;
					case "green":
						echo "Your favorite color is green!";
						break;
					default:
						echo "Your favorite color is neither red, blue, nor green!";
				}
			}
		}
	}
}
?>