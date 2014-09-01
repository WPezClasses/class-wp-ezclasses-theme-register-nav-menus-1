<?php
/** 
 * Methods related to defining and registering menus 
 *
 * (@link http://)
 *
 * PHP version 5.3
 *
 * LICENSE: TODO
 *
 * @package WP ezBoilerStrap
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.0
 * @license TODO
 */
 
/*
* == Change Log == 
*
* --- 28 August 2014 - Ready
*
*/

// No WP? Die! Now!!
if (!defined('ABSPATH')) {
	header( 'HTTP/1.0 403 Forbidden' );
    die();
}

if ( ! class_exists('Class_WP_ezClasses_Theme_Register_Nav_Menus_1') ) {
  class Class_WP_ezClasses_Theme_Register_Nav_Menus_1 extends Class_WP_ezClasses_Master_Singleton{
  
    protected $_arr_init;
		
	protected function __construct() {
	  parent::__construct();
	}
		
	/**
	 *
	 */
	public function ezc_init($arr_args = ''){
	 
	  $arr_init_defaults = $this->init_defaults();
	  
	  $this->_arr_init = WP_ezMethods::ez_array_merge(array($arr_init_defaults, $arr_args));
	}
		

		
    protected function init_defaults(){
	
	  $arr_defaults = array(
	    'active' 		=> true,
		'active_true'	=> true,
		'filters' 		=> false,	// currently NA but let's leave it for now
		'validation' 	=> false,
		'arr_args'		=> array(),
        ); 
	  return $arr_defaults;
	}
	
	
	
		/**
		 * 
		 */	
		public function ez_rnm( $arr_args = '' ){
		

		  if ( ! WP_ezMethods::array_pass($arr_args) ){
		    return array('status' => false, 'msg' => 'ERROR: arr_args is not valid', 'source' => get_class() . ' ' . __METHOD__, 'arr_args' => 'error');
		  }
				
		  $arr_args = WP_ezMethods::ez_array_merge(array( $this->_arr_init, $arr_args)); 
		  
			if ( $arr_args['active'] === true && WP_ezMethods::array_pass($arr_args['arr_args']) ){
			
			  $arr_nav_menus = $arr_args['arr_args'];
				
				// validate - optional
				/* TODO
				if ( isset($arr_args['validate']) && $arr_args['validate'] === true ){

					$arr_ret = $this->_obj_ezc_theme_register_nav_menus->nav_menus_validate($arr_nav_menus);

					if ( $arr_ret['status'] !== true){
					print_r( $arr_ret );	
						return $arr_ret;
					}
					$arr_nav_menus = $arr_ret['arr_args'];
				}
				*/
								
				// active_true
				if ( WP_ezMethods::ez_true($arr_args['active_true']) ){
					$arr_active_true_response = $this->register_nav_menus_active_true($arr_nav_menus);
					if ( $arr_active_true_response['status'] === false ){

						return $arr_active_true_response;
					} 
					$arr_nav_menus = $arr_active_true_response['arr_args'];
				}
				
				/**
				 * At this point we should be good to go.
				 */ 

				// do the wp_nav_menus
			  return $this->register_nav_menus_do($arr_nav_menus);			
			}
		}
		
/*
 * ===============================================================================================
 * ==> register_nav_menus + wp_nav_menu
 * ===============================================================================================
 */		
/* TODO
		public function nav_menus_validate($arr_args = NULL) {
			$str_return_source = 'Theme \ Register :: nav_menus_validate()'; 
			
			if ( $this->_bool_ezc_validate !== false ){

				if ( !is_array($arr_args)){
					return array('status' => false, 'msg' => 'ERROR: arr_args !is_array()', 'source' => $str_return_source, 'arr_args' => 'error');
				}
				
				$bool_validate_only_true = true;
				if ( isset($arr_args['validate_only_true']) && is_bool($arr_args['validate_only_true']) ){
					$bool_validate_only_true = $arr_args['validate_only_true'];
				}
				
				$arr_msg = array(); 
				foreach ($arr_args as $str_key => $arr_value){
				
					if ( ($bool_validate_only_true === true && isset($arr_value['active']) && $arr_value['active'] !== true) ){
						continue;
					}

					$arr_msg_detail = array();
					if ( is_array($arr_value) ){
						
						if ( !isset($arr_value['active']) || !isset($arr_value['description']) || !isset($arr_value['transient_active']) ){
							$arr_msg_detail[] = 'ERROR: !isset(): arr_args[active] || arr_args[description] || arr_args[transient_active]';
						}
						
						if ( isset($arr_value['active']) && !is_bool($arr_value['active'])) {
							$arr_msg_detail[] = 'ERROR: arr_arg[active] !is_bool()';
						}
						
						if ( isset($arr_value['transient_active']) && !is_bool($arr_value['transient_active'])) {
							$arr_msg_detail[] = 'ERROR: arr_arg[transient_active] !is_bool()';
						}
						
						if ( isset($arr_value['transient_name']) && !is_string($arr_value['transient_name'])) {
							$arr_msg_detail[] = 'ERROR: arr_arg[transient_name] !is_string()';
						}
						$arr_value['transient_name'] = sanitize_file_name($arr_value['transient_name']);
						
						if ( isset($arr_value['transient_time']) && !is_int($arr_value['transient_time'])) {
							$arr_msg_detail[] = 'ERROR: arr_arg[transient_time] !is_int()';
						}
					
						if ( isset($arr_value['description']) && !is_string($arr_value['description']) ) {
							$arr_msg_detail[] = 'ERROR: arr_arg[description] !is_string()';
						}
					} else {
						$arr_msg_detail[] = 'ERROR: Expected value of type array for this key';
					}
					
					if ( !empty($arr_msg_detail) ){
						$arr_msg[$str_key] = $arr_msg_detail;
					}	
				}
				
				if ( empty($arr_msg) ){
					return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
				} else {
					return array('status' => false, 'msg' => $arr_msg, 'source' => $str_return_source, 'arr_args' => 'error');
				}	
			} else {
				// TODO - improve msg? 
				return array('status' => true, 'msg' => 'validated off', 'source' => $str_return_source, 'arr_args' => $arr_args);
			}
		}
		
	*/	
		/**
		* 
		*/
		public function register_nav_menus_active_true($arr_args = '') {
			$str_return_source = get_class() . ' ' . __METHOD__;  

			if ( WP_ezMethods::array_pass($arr_args) ) {

				$arr_active_true = array();			
				foreach ($arr_args as $str_key => $arr_value){
					if ( WP_ezMethods::ez_true($arr_value['active']) ){
						$arr_active_true[$str_key] = $arr_value;
					}			
				}
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_active_true);
			} 
			// TODO what if the result is empty. there are no active === true
			return array('status' => false, 'msg' => 'ERROR: arr_args is not valid', 'source' => $str_return_source, 'arr_args' => 'error');
		}

	

		/**
		 * Create the transients for the menus
		 */		
		public function register_nav_menus_do($arr_args = '') {
		
		  $str_return_source = get_class() . ' > ' . __METHOD__; 

		  if ( WP_ezMethods::array_pass($arr_args) ){
			
			foreach ($arr_args as $str_key => $arr_value){
			  /**			  
			   * TODO - once validation is coded then we can remove these checks
			   */
			  if ( isset($arr_value['active']) && $arr_value['active'] === true && isset($arr_value['theme_location']) && is_string($arr_value['theme_location']) && isset($arr_value['description']) && is_string($arr_value['description']) ){
			    
				register_nav_menu($arr_value['theme_location'],$arr_value['description'] );
			  }
			}
				
			return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
			
		  } else { 
		  
		    return array('status' => false, 'msg' => 'ERROR: arr_args was not valid', 'source' => $str_return_source, 'arr_args' => 'error');
		  }	
		}
		
		
	} // END: class
} // END: if class exists


?>