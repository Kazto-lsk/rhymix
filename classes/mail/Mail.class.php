<?php

/**
 * Mail class for XE Compatibility
 * 
 * @author Kijin Sung <kijin@kijinsung.com>
 */
class Mail extends Rhymix\Framework\Mail
{
	/**
	 * Set parameters for using Gmail
	 *
	 * @param string $account_name Email address
	 * @param string $account_passwd Email password
	 * @return void
	 */
	public static function useGmailAccount($account_name, $account_passwd)
	{
		self::useSMTP(null, 'smtp.gmail.com', $account_name, $account_passwd, 'ssl', 465);
	}
	
	/**
	 * Set parameters for using SMTP protocol
	 *
	 * @param bool $auth SMTP authentication
	 * @param string $host SMTP host address
	 * @param string $user SMTP user id
	 * @param string $pass STMP user password
	 * @param string $secure method ('ssl','tls')
	 * @param int $port STMP port
	 *
	 * @return bool TRUE if SMTP is set correct, otherwise return FALSE
	 */
	public static function useSMTP($auth = null, $host = null, $user = null, $pass = null, $secure = null, $port = 25)
	{
		self::$default_transport = Rhymix\Framework\Drivers\Mail\SMTP::getInstance(array(
			'host' => $host,
			'port' => $port,
			'secure' => $secure,
			'user' => $user,
			'pass' => $pass,
		));
	}
	
	/**
	 * Set additional parameters
	 */
	public function setAdditionalParams($additional_params)
	{
		// no-op
	}
	
	/**
	 * Set the sender (From:).
	 *
	 * @param string $name Sender name
	 * @param string $email Sender email address
	 * @return void
	 */
	public function setSender($name, $email)
	{
		$this->setFrom($email, $name ?: null);
	}
	
	/**
	 * Get the sender.
	 *
	 * @return string
	 */
	public function getSender()
	{
		return $this->getFrom() ?: false;
	}
	
	/**
	 * Set Recipient (To:)
	 *
	 * @param string $name Recipient name
	 * @param string $email Recipient email address
	 * @return void
	 */
	public function setReceiptor($name, $email)
	{
		return $this->addTo($email, $name ?: null);
	}
	
	/**
	 * Get Recipient (To:)
	 *
	 * @return string
	 */
	public function getReceiptor()
	{
		$list = $this->getRecipients();
		return $list ? array_first($list) : false;
	}
	
	/**
	 * Set Subject
	 *
	 * @param string $subject The subject
	 * @return void
	 */
	public function setTitle($subject)
	{
		return $this->setSubject($subject);
	}
	
	/**
	 * Get Subject
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->getSubject();
	}
	
	/**
	 * Set BCC
	 *
	 * @param string $bcc
	 * @return void
	 */
	public function setBCC($bcc)
	{
		return $this->addBcc($bcc);
	}
	
	/**
	 * Get the Plain content of body message
	 *
	 * @return string
	 */
	public function getPlainContent()
	{
		return chunk_split(base64_encode(str_replace(array("<", ">", "&"), array("&lt;", "&gt;", "&amp;"), $this->message->getBody())));
	}
	
	/**
	 * Get the HTML content of body message
	 * 
	 * @return string
	 */
	public function getHTMLContent()
	{
		return chunk_split(base64_encode($this->content_type != 'text/html' ? nl2br($this->message->getBody()) : $this->message->getBody()));
	}
	
	/**
	 * Add file attachment
	 *
	 * @param string $filename File name to attach
	 * @param string $original_filename Real path of file to attach
	 * @return void
	 */
	public function addAttachment($filename, $original_filename)
	{
		return $this->attach($original_filename, $filename);
	}
	
	/**
	 * Add content attachment
	 *
	 * @param string $original_filename Real path of file to attach
	 * @param string $cid Content-CID
	 * @return void
	 */
	public function addCidAttachment($original_filename, $cid = null)
	{
		return $this->embed($original_filename, $cid);
	}
	
	/**
	 * Process the images from attachments
	 *
	 * @return void
	 */
	public function procAttachments()
	{
		// no-op
	}
	
	/**
	 * Process the images from body content. This functions is used if Mailer is set as mail not as SMTP
	 * 
	 * @return void
	 */
	public function procCidAttachments()
	{
		// no-op
	}
	
	/**
	 * Check if DNS of param is real or fake
	 * 
	 * @param string $email_address Email address to check
	 * @return bool
	 */
	public function checkMailMX($email_address)
	{
		if(!self::isVaildMailAddress($email_address))
		{
			return FALSE;
		}
		list($user, $host) = explode("@", $email_address);
		if(function_exists('checkdnsrr'))
		{
			if(checkdnsrr($host, "MX") || checkdnsrr($host, "A"))
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		return TRUE;
	}
	
	/**
	 * Check if param is a valid email or not
	 * 
	 * @param string $email_address Email address to check
	 * @return string
	 */
	public function isVaildMailAddress($email_address)
	{
		if(preg_match("/([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)/i", $email_address))
		{
			return $email_address;
		}
		else
		{
			return '';
		}
	}

	/**
	 * Gets the MIME type of param
	 *
	 * @param string $filename filename
	 * @return string MIME type of ext
	 */
	function returnMIMEType($filename)
	{
		return Rhymix\Framework\MIME::getTypeByFilename($filename);
	}

}
/* End of file Mail.class.php */
/* Location: ./classes/mail/Mail.class.php */
