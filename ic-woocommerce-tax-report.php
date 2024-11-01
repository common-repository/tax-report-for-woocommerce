<?php 
/**
Plugin Name: Tax Report for WooCommerce 
Description: Tax Report for WooCommerce Plugin shows Tax summaries and Shipping Tax Summaries, Tax detail report shows each Order wise tax details.
Version: 2.2
Author: Infosoft Consultants
Author URI: http://plugins.infosofttech.com
Plugin URI: https://wordpress.org/plugins/tax-report-for-woocommerce/

Tested Wordpress Version: 6.1.x
WC requires at least: 3.5.x
WC tested up to: 7.4.x
Requires at least: 5.7
Requires PHP: 5.6

Text Domain: icwoocommercetax
Domain Path: /languages/

*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'IC_WooCommerce_Tax_Report' ) ) { 
	class IC_WooCommerce_Tax_Report{
		
		function __construct() {
			
			
			
			add_filter( 'plugin_action_links_tax-report-for-woocommerce/ic-woocommerce-tax-report.php', array( $this, 'plugin_action_links' ), 9, 2 );
			add_action( 'init', array( $this, 'load_plugin_textdomain' ));
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ));
		}
		
		function plugins_loaded(){
			if(is_admin()){
				include_once("include/ic-woocommerce-tax-report-init.php");
				$obj_init = new IC_WooCommerce_Tax_Report_Init();
			}
		}
		
		function plugin_action_links($plugin_links, $file = ''){
			$plugin_links[] = '<a target="_blank" href="'.admin_url('admin.php?page=tax-menu').'">' . esc_html__( 'Dashboard', 'icwoocommercetax' ) . '</a>';
			return $plugin_links;
		}
		
		function load_plugin_textdomain(){
			load_plugin_textdomain( 'icwoocommercetax', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
		}
	}
	$obj = new  IC_WooCommerce_Tax_Report();
}
?>