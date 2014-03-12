<?php
class DevramaLazyloadImagesAppDefaultController extends DevramaWordpressMVCInit {
	
	public function __construct(){
		parent::__construct(get_class($this), __DIR__);
	}
	
	//Define abstract class enqueueScripts
	protected function enqueueScripts(){
		$this->css = array(
				//'devrama-lazyload-images.css'
		);
		$this->js = array(
				'jquery.devrama.lazyload.min-0.9.3.js'
		);
	}
	
	public function _filter_the_content_15($content){
		$pattern = '/(<\s*?img\s+[^>]*?\s*)(src\s*=\s*["\'](.*?)["\'])[^>]*?width\s*=\s*["\'](.*?)["\'][^>]*?height\s*=\s*["\'](.*?)["\']([^>]*?>)/';
		$content = preg_replace($pattern, '$1 data-wp-content-image-lazy-src="$3" data-size="[$4:$5]" style="width:$4px; height:$5px;]" $6', $content);
		//print_r($matches);
		
		return $content;
		
	}
	
	public function _action_wp_footer(){
		echo 
		'
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$.DrLazyload({
				data_attr_name: \'wp-content-image-lazy-src\'
			});
		});
		</script>
		';
	}
	
	
}
