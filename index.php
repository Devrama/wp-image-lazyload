<?php
/**
 * @package Devrama Lazyload Images
 *
 * Plugin Name: Devrama Lazyload Images
 * Plugin URI: http://wordpress.org/plugins/devrama-image-lazyload/
 * Description: Devrama Image Lazyload loads images in the content of your post as you scroll down. It makes the page load faster and reduce server traffic.
 * Version: 0.9.33
 * Author: calmgracian
 * Author URI: http://devrama.com/
 * License: MIT License
 */

require_once(plugin_dir_path(__FILE__).'config.php');
if(!class_exists('DevramaWordpressMVC'))
	require_once(plugin_dir_path(__FILE__).'includes/devrama_wordpress_mvc.php');

if(!is_admin()){
	require_once(DEVRAMA_LAZYLOAD_IMAGES_PATH.'app/default/controller.php');
	new DevramaLazyloadImagesAppDefaultController();
}
