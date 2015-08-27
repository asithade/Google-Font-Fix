<?php
/**
 * Plugin Name: Google Font Fix
 * Plugin URI: https://github.com/zjhzxhz/google-font-fix
 * Description: Use 360 Open Fonts Service to replace Google's for Chinese users.
 * Author: 谢浩哲
 * Author URI: http://zjhzxhz.com
 * Version: 1.2.1
 * License: GPL v2.0
 */

define('PLUGIN_PATH', plugin_dir_path(__FILE__));
require_once(PLUGIN_PATH . "geo/geoip.inc");

function google_apis_fix($buffer) {
    $geoData     = geoip_open(PLUGIN_PATH . 'geo/GeoIP.dat', GEOIP_STANDARD);
    $countryCode = geoip_country_code_by_addr($geoData, $_SERVER['REMOTE_ADDR']);
    geoip_close($geoData);
    
    if( $countryCode === 'CN' ) {
        return str_replace(array(
            'https://libs.googleapis.com',
            'https://ajax.googleapis.com',
            'https://fonts.googleapis.com',
        ), array(
            'http://libs.useso.com',
            'http://ajax.useso.com',
            'http://fonts.useso.com',
        ), $buffer);
    }
    else {
        return $buffer;
    }
}

function gff_buffer_start() {
	ob_start("google_apis_fix");
}

function gff_buffer_end() {
	while ( ob_get_level() > 0 ) {
		ob_end_flush();
	}
}

add_action('init', 'gff_buffer_start');
add_action('shutdown', 'gff_buffer_end');

?>
