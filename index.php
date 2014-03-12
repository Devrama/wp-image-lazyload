<?php
/**
 * @package Devrama Lazyload Images
 */
/*
Plugin Name: Devrama Lazyload Images
Author: WON JONG YOO
*/

require_once(plugin_dir_path(__FILE__).'config.php');
if(!class_exists('DevramaWordpressMVC'))
	require_once(plugin_dir_path(__FILE__).'includes/devrama_wordpress_mvc.php');

if(!is_admin()){
	require_once(DEVRAMA_LAZYLOAD_IMAGES_PATH.'app/default/controller.php');
	new DevramaLazyloadImagesAppDefaultController();
}
