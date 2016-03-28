<?php


class WlogErrorHandler
{
	private $old_error_handler;

	function __construct()
	{
		$this->old_error_handler = set_error_handler( array($this,'wlog_error_handler'),E_ALL);

		register_shutdown_function( array($this,'wlog_fatal_handler') );

		add_filter( 'http_request_timeout', array($this,'wlog_timeout_extend') );

		

	}

	function wlog_timeout_extend( $time )
	{
	    // Default timeout is 5
	    return 10;
	}

	function wlog_error_handler($errno, $errstr, $errfile, $errline)
	{  
		$this->send(		
			array(
				'errno'		=> $errno,
				'errstr'	=> $errstr,
				'errfile'	=> $errfile,
				'errline'	=> $errline
				)
		);

		return EXECUTE_INTERNAL_HANDLER;
	}
 	

 	function wlog_fatal_handler()
 	{
		$errfile = "unknown file";
		$errstr  = "shutdown";
		$errno   = E_CORE_ERROR;
		$errline = 0;

		$error = error_get_last();

		// TODO: only on real error.

		if( $error !== NULL)
		{
			$errno   = $error["type"];
			$errfile = $error["file"];
			$errline = $error["line"];
			$errstr  = $error["message"];


			$this->send(		
				array(
				'errno'		=> $errno,
				'errstr'	=> $errstr,
				'errfile'	=> $errfile,
				'errline'	=> $errline
				)
			);
			
		}

	}

	function send($error)
	{
		global $wlog_core;
		$url = $wlog_core->getUrl();

		$msg = sprintf("Error [%s] in line %s in file %s => %s"
			,$error["errno"]
			,$error["errline"]
			,$error["errfile"]
			,$error["errstr"]
		);

		$postData = array(
			  'SourceDate'		=> date("c"),
			  'Message'			=> $msg,
			  "Level"			=> 'Error',
			  "ApplicationKey"	=> $wlog_core->getKey()
		);

		$req = array(
			'method' 		=> 'POST',
			'timeout' 		=> 60,
			'redirection' 	=> 5,
			'httpversion' 	=> '1.0',
			'blocking' 		=> true,
			'headers'		=> array('Content-Type' => 'application/json'),
			'body'			=> json_encode($postData),
			'cookies' 		=> array()
		);

		$response = wp_remote_post( $url, $req);

	
		if ( is_wp_error( $response ) )
		{
		   $error_message = $response->get_error_message();
		   echo "Something went wrong: $error_message";
		}
		else
		{
		   echo 'Response:<pre>';
		   print_r( $response );
		   echo '</pre>';
		}

	}

 
}

global $wlog_error_handler;
$wlog_error_handler = new WlogErrorHandler();
