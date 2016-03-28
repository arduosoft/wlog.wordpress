<?php

namespace Wlog;

function error_log($msg)
{
	echo $msg;
}

class WlogCore
{

	private $options = array(
		'wlog_key'	=> '',
		'wlog_url'	=> '',
	);

	function __construct()
	{
		$this->setOptions();
	}

	
	function setOptions()
	{
		$options = get_option( WLOG_OPTIONS, array());
		$this->options = wp_parse_args( $options, $this->options );
		update_option( WLOG_OPTIONS, $this->options );

	}

	function getOptions()
	{
		return $this->options;
	}

	public function getKey()
	{
		return $this->options['wlog_key'];
	}

	public function getUrl()
	{
		return $this->options['wlog_url'];
	}

	

}

global $wlog_core;
$wlog_core = new WlogCore();



