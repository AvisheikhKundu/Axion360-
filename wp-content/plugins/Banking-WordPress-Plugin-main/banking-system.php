<?php
/*
Plugin Name: Banking System
Description: Banking System
Version:     1.0
Author:      
Domain Path: https://giobonline.com/
*/


//Admin		
	add_action('admin_menu', 'banking_system');
	function banking_system(){
	  add_menu_page('Banking Dashboard', 'Banking Dashboard', 'manage_options', 'theme-banking', 'settings_dashboard');
	   add_submenu_page( 'theme-banking', 'Account', 'Account', 'manage_options', 'account', 'account_url_func');
	  add_submenu_page( 'theme-banking', 'Transfer', 'Transfer', 'manage_options', 'transfer', 'transfer_url_func');
	  add_submenu_page( 'theme-banking', 'Transaction', 'Transaction', 'manage_options', 'transaction', 'transaction_url_func');

	}
	
	function settings_dashboard(){
	   include_once dirname(__FILE__) . '/admin_dashboard.php';	
		
	}
	function account_url_func(){
	  
		include_once dirname(__FILE__) . '/admin_account.php';	
	}
	function transfer_url_func(){
	  
		include_once dirname(__FILE__) . '/admin_transfer.php';	
	}
	
	function transaction_url_func(){
	   
		include_once dirname(__FILE__) . '/admin_transaction.php';	
	}
	
	
	// function to create the DB / Options / Defaults					
	function register_install() {
		global $wpdb;
		global $your_db_name;
		$charset_collate = $wpdb->get_charset_collate();
	
	 
		 $sql1 = "  CREATE TABLE `wp_transaction` (
					  `id` int(10) NOT NULL AUTO_INCREMENT,		
					  `users_id` varchar(10) DEFAULT NULL,		  
					  `transfer_id` varchar(10) DEFAULT NULL,
					  `debit` DECIMAL(10,2) DEFAULT NULL,
					  `credit` DECIMAL(10,2) DEFAULT NULL,
					  `created_at` datetime DEFAULT NULL,
					  `updated_at` datetime DEFAULT NULL,
					   UNIQUE KEY id (id)
					) $charset_collate;";
					
			 $sql2 = "
					CREATE TABLE `wp_transfer` (
					  `id` int(10) NOT NULL AUTO_INCREMENT,
					  `users_id` int(10) DEFAULT NULL,
					  `amount` DECIMAL(10,2) DEFAULT NULL,
					  `acc_name` varchar(256) DEFAULT NULL,
					  `acc_number` varchar(256) DEFAULT NULL,
					  `bank_name` varchar(256) DEFAULT NULL,
					  `bank_country` varchar(256) DEFAULT NULL,
					  `bank_address` varchar(256) DEFAULT NULL,
					  `bank_swift_code` varchar(256) DEFAULT NULL,
					  `bank_routing_number` varchar(256) DEFAULT NULL,
					  `document_file` varchar(256) DEFAULT NULL,
					  `transfer_status` varchar(256) DEFAULT NULL,
					  `transfer_type` varchar(256) DEFAULT NULL,
					  `created_at` datetime DEFAULT NULL,
					  `updated_at` datetime DEFAULT NULL,
					   UNIQUE KEY id (id)
					) $charset_collate;";
					
					
			 $sql3 = "
					CREATE TABLE `wp_account` (
					  `id` int(10) NOT NULL AUTO_INCREMENT,
					  `users_id` int(10) DEFAULT NULL,
					  `account_name` DECIMAL(10,2) DEFAULT NULL,
					  `account_number` varchar(256) DEFAULT NULL,
					  `file_picture` varchar(256) DEFAULT NULL,
					  `acc_status` varchar(256) DEFAULT NULL,
					  `created_at` datetime DEFAULT NULL,
					  `updated_at` datetime DEFAULT NULL,
					   UNIQUE KEY id (id)
					) $charset_collate;";
							
					
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql1);
			dbDelta($sql2);
			dbDelta($sql3);
		
	 
	}
	// run the install scripts upon plugin activation
	register_activation_hook(__FILE__,'register_install');
	
	
	
	 add_shortcode('member-dashboard', 'member_dashboard_func'); 
	
	function member_dashboard_func(){
		include_once dirname(__FILE__) . '/member_dashboard.php';  
	}
	


    add_shortcode('member-account', 'member_account_func'); 
	
	function member_account_func(){
		include_once dirname(__FILE__) . '/member_account.php';  
	}
	
	
	add_shortcode('member-transfer', 'member_transfer_func'); 
	
	function member_transfer_func(){
		include_once dirname(__FILE__) . '/member_transfer.php';  
	}
	
	add_shortcode('member-transaction', 'member_transaction_func'); 
	
	function member_transaction_func(){
		include_once dirname(__FILE__) . '/member_transaction.php';  
	}