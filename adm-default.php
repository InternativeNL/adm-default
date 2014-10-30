<?php
/*
Plugin Name: Admium default options
Plugin URI: www.admium.nl
Description: Several default options for Admium Wordpress CMS.
Author: Admium
Version: 0.8
Author URI: www.admium.nl
License: GPLv2 or later
Text Domain: adm-default
Domain Path: /languages
GitHub Plugin URI: AdmiumNL/adm-default
*/

///////////////////////////////////////////////////////////////////////////////

function adm_remove_wordpress_generator() {
	return '';
}
add_filter('the_generator', 'adm_remove_wordpress_generator', 1);

///////////////////////////////////////////////////////////////////////////////
// Style the login page to Admium design //////////////////////////////////////

function adm_add_custom_login_image() {
    echo '
    <style type="text/css">
        #login {
        	width: 295px !important;
        }
        #loginform, #lostpasswordform {
            border-radius: 5px;
            -webkit-border-radius: 5px;
    		-webkit-box-shadow: 0px 1px 2px 0 rgba(0,0,0,0.3);
    		box-shadow: 0px 1px 2px 0 rgba(0,0,0,0.3);
    		background: #ffffff; /* Old browsers */
    		-webkit-appearance: none;
        }
        #loginform #wp-submit {
            margin-top:12px;
        }
        #wp-submit {
            width:100%;
        }
        #nav a, #backtoblog a {
            color: #ffffff !important;
        }
        .login #backtoblog, .login #nav {
            text-align:center !important;
        }
        h1 a { 
            background-image:url(http://www.admium.nl/plugin/admium_logo.png) !important;
            background-size: 295px 85px !important;
            height: 85px !important;
            width: 295px !important;
        }
        body {
            background:none !important;
        }
        html {
            background: url(http://www.admium.nl/plugin/background.php) no-repeat center center fixed !important;
            -webkit-background-size: cover !important;
            -moz-background-size: cover !important;
            -o-background-size: cover !important;
            background-size: cover !important;
        }
        .wp-core-ui .button-primary {
            background: #228fcf !important;
        }
    </style>
    ';
}
add_action('login_head', 'adm_add_custom_login_image');

///////////////////////////////////////////////////////////////////////////////
// Change link and title for the Wordpress logo on the login page

function adm_add_custom_login_url(){
    return "http://www.admium.nl/";
}
add_filter('login_headerurl', 'adm_add_custom_login_url');

function adm_add_custom_login_title(){
    return 'Admium - online strategie &amp; realisatie';
}
add_filter('login_headertitle', 'adm_add_custom_login_title');

///////////////////////////////////////////////////////////////////////////////
// Change copyright notification at the bottom of the page

function adm_add_custom_footer_admin () {
	echo '&copy; '.date("Y").' - Admium';
}
add_filter('admin_footer_text', 'adm_add_custom_footer_admin');

///////////////////////////////////////////////////////////////////////////////
// Disable certain default Wordpress dashboard widgets

function adm_disable_default_dashboard_widgets() {
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core'); // remove incoming links widget
	remove_meta_box('dashboard_plugins', 'dashboard', 'core'); // remove plugins widget
	remove_meta_box('dashboard_quick_press', 'dashboard', 'core'); // remove quick press widget
	remove_meta_box('dashboard_primary', 'dashboard', 'core'); // remove wordpress blog feed widget
	remove_meta_box('dashboard_secondary', 'dashboard', 'core'); // remove other wordpress news widget
	remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal'); // remove gravity forms widget
	remove_meta_box('yoast_db_widget', 'dashboard', 'normal'); // remove yoast widget
}
add_action('admin_menu', 'adm_disable_default_dashboard_widgets', 1);

///////////////////////////////////////////////////////////////////////////////
// Disable Wordpress update notification for non admin users

function adm_disable_update_notification() 
{
    if (!current_user_can('update_core')) {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }
}
add_action( 'admin_head', 'adm_disable_update_notification', 1 );

///////////////////////////////////////////////////////////////////////////////
// Add iFrame to Wordpress dashboard which loads Admium documentation

function adm_custom_dashboard_widgets() {
	wp_add_dashboard_widget('custom_help_widget', __('Need help? Visit the Admium service website', 'adm-default'), function(){
    	echo '<iframe src="http://service.admium.nl/widget/" width="100%" height="500"></iframe>';
	});
	
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;
	
	// Get the regular dashboard widgets array 
	// (which has our new widget already but at the end)
	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	
	// Backup and delete our new dashboard widget from the end of the array
	$example_widget_backup = array('custom_help_widget' => $normal_dashboard['custom_help_widget']);
	unset($normal_dashboard['custom_help_widget']);

	// Merge the two arrays together so our widget is at the beginning
	$sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);

	// Save the sorted array back into the original metaboxes 
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}
add_action('wp_dashboard_setup', 'adm_custom_dashboard_widgets');

///////////////////////////////////////////////////////////////////////////////

require (dirname(__FILE__).'/shortcodes.php');