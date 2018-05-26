<?php
namespace App\Common;

/**
 * This is the class which has generic utility methods.
 *
 * @author Ajay Garga
 *        
 */
require_once __DIR__.'/AppConstants.php';

class Util {
    
	public static function isEmpty($value) {
		if (isset ( $value ) && (is_string ( $value ) || is_numeric ( $value ))) {
			$value = ( string ) $value;
			$value = trim ( $value );
		}
		
		return empty ( $value );
	}
        
	public static function mkdir($path, $mode = null, $recursive = true) {
		if (! $mode) {
			$mode = AppConstants::DEFAULT_MODE;
		}
		
		$return = @mkdir ( $path, 0777, $recursive );
		@chmod ( $path, $mode );
		return $return;
	}
        
	public static function putFileContent($path, $data) {
		$return = file_put_contents ( $path, $data );
		@chmod ( $path, AppConstants::DEFAULT_MODE );
		return $return;
	}
        
	public static function startsWith($haystack, $needle) {
		$length = strlen ( $needle );
		return (substr ( $haystack, 0, $length ) === $needle);
	}
        
	public static function endsWith($str, $sub) {
		return (substr ( $str, strlen ( $str ) - strlen ( $sub ) ) == $sub);
	}
        
	/**
	 * Transform an "action" token into a method name
	 *
	 * @param string $action        	
	 * @return string
	 */
	public static function getMethodFromAction($action) {
		$method = str_replace ( array (
				'.',
				'-',
				'_' 
		), ' ', $action );
		$method = ucwords ( $method );
		$method = str_replace ( ' ', '', $method );
		$method = lcfirst ( $method );
		$method .= AppConstants::ACTION;
		
		return $method;
	}
        
}