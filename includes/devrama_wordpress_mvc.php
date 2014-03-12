<?php 
/*
* Author: WON JONG YOO
* Author URI: http://devrama.com
*/


class DevramaWordpressMVC {
	protected $path = '';
	public $css = array();
	public $js = array();
	
	public function __construct($classname, $component_path = ''){
		$this->path = $component_path;
			
	}
	
	protected function callComponent($class_prefix, $component){
		require_once(dirname($this->path).'/'.$component.'/controller.php');
		$classname = $class_prefix.'App'.ucfirst(str_replace('_', '', $component)).'Controller';
		$obj = new $classname();
		return $obj;
	}
	
	public function loadView($filename, $vars = array()){
		if(!empty($vars)){
			foreach($vars AS $key => $value){
				$$key = $value;
			}
		}
		
		ob_start();
			include($this->path.'/views/'.$filename);
		$content = ob_get_clean();
		
		
		
		return $content;
	}
	
	public function loadModel($filename){
		require_once($this->path.'/models/'.$filename);
	}
		
	public function getCSS($css_path){
		$base_url = plugins_url('views/css/', $this->path);
		return $base_url.$css_path;
	}
	
	public function getJS($js_path){
		$base_url = plugins_url('views/js/', $this->path);
		return $base_url.$js_path;
	}
	
	public function getImage($image_path){
		$base_url = plugins_url('views/images/', $this->path);
		return $base_url.$image_path;
	}
}

abstract class DevramaWordpressMVCInit extends DevramaWordpressMVC {
	private $enqueue_scripts_priority = 11;
	
	abstract protected function enqueueScripts(); //add $css and $js (maybe with conditions)
	
	public function __construct($classname, $component_path = ''){
		parent::__construct($classname, $component_path);
		
		foreach(get_class_methods($classname) AS $method){

			$priority = 10;
			$last_part = '';

			$is_filter = strpos($method, '_filter_') === 0 ? TRUE : FALSE;
			if(!$is_filter) $is_action = strpos($method, '_action_') === 0 ? TRUE : FALSE;

			if($is_filter || $is_action){
				$last_part = substr($method, strrpos($method, '_')+1);
				if(is_numeric($last_part)) $priority = intval($last_part);
				else $last_part = '';
			}
			
			$text_to_replace = ( $last_part == '') ? array() : array('_'.$last_part);

			if($is_filter){
				$text_to_replace[] = '_filter_';
				add_filter(str_replace($text_to_replace, '', $method), array($this, $method), $priority);
			}
			else if($is_action){
				$text_to_replace[] = '_action_';
				add_action(str_replace($text_to_replace, '', $method), array($this, $method), $priority);
			}

		}

		$this->enqueueScripts();
		add_action('wp_enqueue_scripts', array($this, '_enqueueScripts'), $this->enqueue_scripts_priority);
	}

	public function _enqueueScripts(){
		$head = str_replace('/', '-', str_replace(DEVRAMA_LAZYLOAD_IMAGES_PATH, '', $this->path)).'-';
		
		$css_base_url = plugins_url('views/css/', $this->path.'/notexist.php');
		$js_base_url = plugins_url('views/js/', $this->path.'/notexist.php');

		foreach($this->css AS $filename)
			wp_enqueue_style($head.str_replace('.', '-', $filename), $css_base_url.$filename, array(), DEVRAMA_LAZYLOAD_IMAGES_VERSION);

		foreach($this->js AS $filename)
			wp_enqueue_script($head.str_replace('.', '-', $filename), $js_base_url.$filename, array(), DEVRAMA_LAZYLOAD_IMAGES_VERSION);

	}
}



?>
