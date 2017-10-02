<?php

/**
 * @Author: suifengtec
 * @Date:   2017-10-02 17:33:14
 * @Last Modified by:   suifengtec
 * @Last Modified time: 2017-10-02 20:52:33
 **/
  

if ( ! defined( 'ABSPATH' ) ){
	exit;	
}

/*

既适用于 WPBakery Visual Composer, 又适用于没有启用 WPBakery Visual Composer 的 WordPress 应用的短代码。


启用 VC 时的测试：

mkdir abcd && cd abcd|||touch main.go && main.go

未启用 VC 时的测试:

[cwp_terminal_cmds  cmds='mkdir abcd && cd abcd|||touch main.go && main.go'][/cwp_terminal_cmds]

*/
class CWP_VC_T2_Module_SC{

	public function __construct(){

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if (is_plugin_active('js_composer/js_composer.php')) {
			require_once(WP_PLUGIN_DIR . '/js_composer/include/classes/shortcodes/shortcodes.php');
		}

		/*
		注册短代码
		*/
		add_action('init', array($this, 'addComponentsToVC'),11);
	}

	/**
	 * 可一次添加多个VC组件,这里仅添加1个作为示例
	 */
	public function addComponentsToVC(){

		if (class_exists('Vc_Manager')) {

			vc_map(array(
				'name' => '命令行的命令',
				'description' =>'显示命令行的命令',
				'base' => 'cwp_terminal_cmds',
				'category' => 'CWP',
				/*
				可自定义icon
				*/
				'icon' => '',
				'params' => array(
				array(
				"type" => "textfield",
				"heading" => '命令行的命令',
				"param_name" => "cmds",
				"description" =>'如需多行,以<code>|||</code> 作为换行符'
				),
				)
			));

		}


		add_shortcode( 'cwp_terminal_cmds', array($this,'cwp_terminal_cmds_handler'));
	}

	/**
	 * 短代码的前台输出
	 * @param array $atts 参数数组
	 */
	public function cwp_terminal_cmds_handler($atts, $content =''){

		/*
		获取短代码的参数
		 */
		$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'cwp_terminal_cmds', $atts ) : $atts;
		extract(shortcode_atts(array(
			'cmds' => '',
		), $atts, 'cwp_terminal_cmds'));

		/*
		开始输出和返回
		 */
		ob_start();

		if(false===strpos($cmds, '|||')){
			$cmds[] = $cmds;
		}else{
			$cmds = explode('|||', $cmds);
		}
?>
<div class="terminal-monitor">
          <div class="terminal-menu">
            <div class="terminal-btns terminal-btn-close"></div>
            <div class="terminal-btns terminal-btn-min"></div>
            <div class="terminal-btns terminal-btn-zoom"></div>
          </div>
          <div class="terminal-screen">
            <ul class="shell-body">
		<?php  if(!empty($cmds)):foreach($cmds as $c):?>
              		<li><?php echo $c; ?></li>
		<?php  endforeach;endif; ?>
              	<li><span class="terminal-cursor">_</span></li>
            </ul>
          </div>
        </div>
<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}

}
/*EOF*/
