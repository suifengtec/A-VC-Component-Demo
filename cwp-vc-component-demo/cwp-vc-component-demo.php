<?php
/**
 * @Author: suifengtec
 * @Date:   2017-10-02 17:31:54
 * @Last Modified by:   suifengtec
 * @Last Modified time: 2017-10-02 20:55:58
 **/
/**
 * Plugin Name: A VC  Component Demo
 * Plugin URI: http://coolwp.com/PluginSlug.html
 * Description: WPBakery Visual Composer 扩展组件示例.
 * Author: suifengtec
 * Author URI: https://coolwp.com
 * Version: 0.9.0
 * Text Domain: cwpvct2
 * Domain Path: /languages/
 *
 */

if ( ! defined( 'ABSPATH' ) ){
	exit;	
}

if ( ! class_exists( 'CWP_VC_T2' ) ) :

final class CWP_VC_T2 {

	private static $instance;

	public function __wakeup() {}
	public function __clone() {}
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof CWP_VC_T2 ) ) {
			self::$instance = new self();
			self::$instance->setup_constants();
			self::$instance->hooks();
		}

		return self::$instance;

	}

	public function hooks(){
		
		spl_autoload_register( array( __CLASS__, '_autoload' ));
		
		add_action( 'wp_enqueue_scripts', array(__CLASS__, 'wp_enqueue_scripts'),99 );
		new CWP_VC_T2_Module_SC;

	}
	

	public static function wp_enqueue_scripts(){
		
		/*在前台载入所需的CSS*/
		wp_enqueue_style( 'cwpvct2-frontend-css', CWP_VC_T2_PLUGIN_URL . 'assets/css/terminal.css' );
	}

	/**
	 * 自动载入类文件
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public static function _autoload( $class ) {

	    if ( stripos( $class, 'CWP_VC_T2_' ) !== false ) {

	        $module = ( stripos( $class, '_Module_' ) !== false ) ? true : false;
		if($module){
	            $class_name = str_replace( array('CWP_VC_T2_Module_', '_'), array('', '-'), $class );
	            $filename = dirname( __FILE__ ) . '/modules/' . strtolower( $class_name ) . '.php';

	        }

	        if ( file_exists( $filename ) ) {
	            require_once $filename;
	        }
	    }
	}

	/**
	 * 常量定义
	 * @return [type] [description]
	 */
	private function setup_constants() {

		if ( ! defined( 'CWP_VC_T2_PLUGIN_URL' ) ) {
			define( 'CWP_VC_T2_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
	}

}

global $cwpvct2;
$cwpvct2 = CWP_VC_T2::instance();

endif;
