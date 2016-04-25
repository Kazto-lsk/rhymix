<?php

namespace Rhymix\Framework\Drivers;

/**
 * The mail driver interface.
 */
interface MailInterface
{
	/**
	 * Create a new instance of the current mail driver, using the given settings.
	 * 
	 * @param array $config
	 * @return void
	 */
	public static function getInstance(array $config);
	
	/**
	 * Check if the current mail driver is supported on this server.
	 * 
	 * This method returns true on success and false on failure.
	 * 
	 * @return bool
	 */
	public static function isSupported();
	
	/**
	 * Send a message.
	 * 
	 * This method returns true on success and false on failure.
	 * 
	 * @param object $message
	 * @return bool
	 */
	public function send(\Rhymix\Framework\Mail $message);
}
