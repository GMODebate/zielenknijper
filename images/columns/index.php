<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
#print_r($_REQUEST);exit;
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */

	require_once('../zk_config.php');
	chdir(ZK_DIR);
	
	ignore_user_abort(true);
	define('WP_USE_THEMES', true);

	$columnist_key = '';
	
	if ($_REQUEST["page"] && preg_match('|^columns/([^/]+)\.html$|Ui',$_REQUEST["page"],$out)) {
		$columnist_key = $out[1];
	} elseif($_REQUEST["page"]) {
		header("HTTP/1.0 404 Not Found");
		exit;
	} else {
		header('Location: /');
		exit;
	}

	// pre start MySQL
	require_once( ZK_DIR . '/wp-load.php' );
	
	if ($columnist_key) {
		$columnist = mysql_fetch_assoc(mysql_query("SELECT c.id,c.description,c.description_footer,c.wp_id,c.name,c.name_title,c.university,c.university_url,c.name_suffix,c.profession,c.email,c.website,c.seo_key,c.date_started,IF (c.photo='',0,1) as photo,c.wp_link_key FROM `columns` as c WHERE c.seo_key='".addslashes($columnist_key)."' LIMIT 1"));
		if (!$columnist) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
		$_SERVER['REQUEST_URI'] = '/columns/'.$columnist["wp_link_key"];
	} else {
		$_SERVER['REQUEST_URI'] = '/columns/';
	}

	/** Loads the WordPress Environment and Template */
	require('./wp-blog-header.php');

