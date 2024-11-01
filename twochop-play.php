<?php
/*
Plugin Name: TwoChop Play
Plugin URI: http://support.twochop.com/wordpress-plugin
Description: Formerly only available to Wordpress VIP customers, TwoChop is now available to all WordPress users. The TwoChop Play plugin lets you add simple fun games directly on to any blog post. The games could be customized and made directly relevant to the content of your post. Games that are available include crossword puzzles, trivia, picture puzzles, etc. More game types are coming.
Version: 1.4.5
Author: TwoChop
Author URI: http://www.twochop.com

Copyright 2009-2011  (c) twochop.com
*/

if(!(class_exists('twochop_play_public')))
{
class twochop_play_public {

	/* 
	* Constructor
	*/
	function twochop_play_public()
	{
		//standard install - url pointing to standard plugin directory + twochop plugin directory
		define('twochop_public_play_URLPATH', WP_PLUGIN_URL.'/'.plugin_basename( dirname(__FILE__) ).'/' );
		
		$this->init_hooks();
	}

	function twochop_media_button( ) {
	        global $post_ID;
	        $iframe_post_id = (int) ($post_ID);
	        $title = esc_attr( __( 'TwoChop play - public' ) );
			$site_url = admin_url( "/admin-ajax.php?post_id=$iframe_post_id&amp;wp_tcfrm=preed&amp;action=tcfrm_preed_pub&amp;TB_iframe=true&amp;width=800&amp;height=600");
	        echo '<a href="' . $site_url . '&id=add_form" class="thickbox" title="' . $title . '"><img src="' . twochop_public_play_URLPATH . 'assets/twochop_icon.gif" alt="' . $title . '" width="20" height="20" /></a>';
	}

	/**
	* Initializes the hooks for the plugin
	*/
	function init_hooks() {
		add_shortcode('twochop-public', array(&$this, 'twochop_shortcode'));

		add_action( 'media_buttons', array(&$this,'twochop_media_button'), 999 );

		if(is_admin()){
			if ( !empty( $_GET['wp_tcfrm'] ) && $_GET['wp_tcfrm'] == 'psted' ) {
				add_action( 'parse_request', 'twochop_public_parse_wp_request_pst');
				add_action( 'wp_ajax_tcfrm_psted_pub', 'twochop_public_parse_wp_request_pst');
			}

			if ( !empty( $_GET['wp_tcfrm'] ) && $_GET['wp_tcfrm'] == 'preed' ) {
				add_action( 'parse_request', 'twochop_public_parse_wp_request_pre');
				add_action( 'wp_ajax_tcfrm_preed_pub', 'twochop_public_parse_wp_request_pre');
			}
		}
	}
	
	/**
	 * Shortcode takes in two variables (ID and IDTYPE) to render TwoChop gaming button
	 *
	 * Example: [twochop-public idtype="1" id="16116881866483215034"]
	 *
	 * @return string
	 */
	function twochop_shortcode($atts, $content = null) {
		global $post;
		extract(
			shortcode_atts(array(
				'idtype'	=>	'',
				'id'	=>	'',
			), $atts));

		if ($idtype && $id) :
			$html = '<script language="javascript" type="text/javascript" src="http://www.twochop.com/games/scripts/tcplayb11.js"></script>';
			$html .= '<script language="javascript" type="text/javascript">';
			$html .= 'if (typeof(tcplayb11)!=\'undefined\'){'; 
			$html .= sprintf('    tcplayb11.createPlayButton("%s","%s", "" ,"");', esc_js(strip_tags($idtype)), esc_js(strip_tags($id)));
			$html .= '}';
			$html .= '</script>';
			return $html;
		endif;
		return '';
	}
}
}

//standard install
add_action( 'plugins_loaded', create_function( '', 'global $twochop_play_public; $twochop_play_public = new twochop_play_public();' ) );

function twochop_public_parse_wp_request_pst( $wp ) {
	twochop_public_display_form_view_pst( );
	exit;
}

function twochop_public_display_form_view_pst( ) {
	require_once(dirname (__FILE__).'/forms/posteditor.php');
}

function twochop_public_parse_wp_request_pre( $wp ) {
	twochop_public_display_form_view_pre( );
	exit;
}

function twochop_public_display_form_view_pre( ) {
	require_once(dirname (__FILE__).'/forms/preeditor.php');
}

?>