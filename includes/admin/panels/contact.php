<?php
namespace RSC\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\contact')):
	
class contact extends panel{
	
	public $name = 'contact';
	
	public $fields = array();
	
	public $lock_fields = array();
	
	public $use_preview = false;
	
	function get_label(){
		return __('Contact', RSC_TEXTDOMAIN);
	}
	
	function echo($settings){
?>
<section>
<p><a href="mailto:sandman.jp@gmail.com">sandman.jp@gmail.com</a></p>
<p>気に入っていただけたら、下記からコーヒー代でも援助していただけると励みになります！</p>
<p><a href="https://paypal.me/sandmanjp?country.x=JP&locale.x=ja_JP" target="_blank">Paypal (https://paypal.me/sandmanjp)</a></p>
</section>
<?php
	}
}
endif;