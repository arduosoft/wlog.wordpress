<?php
/**
 * @package Wlog
 * @version 1.0
 */
/*
Plugin Name: Wlog
Plugin URI: 
Description: Wordpress plugin for Wlog. See https://github.com/arduosoft/wlog for more info. 
Author: Francesco Minà
Version: 0.1
Author URI: https://github.com/arduosoft/wlog.wordpress
*/

define('EXECUTE_INTERNAL_HANDLER',TRUE);
define('WLOG_OPTIONS','wlog_options');

require_once('classes/class.wlog.core.php');
require_once('classes/class.wlog.errorhandler.php');
require_once('classes/class.wlog.settings.php');

?>
