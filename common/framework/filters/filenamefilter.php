<?php

namespace Rhymix\Framework\Filters;

/**
 * The filename filter class.
 */
class FilenameFilter
{
	/**
	 * Remove illegal and dangerous characters from a filename.
	 * 
	 * @param string $filename
	 * @return string
	 */
	public static function clean($filename)
	{
		// Replace dangerous characters with safe alternatives, maintaining meaning as much as possible.
		$illegal = array('\\', '/', '<', '>', '{', '}', ':', ';', '|', '"', '~', '`', '@', '#', '$', '%', '^', '&', '*', '?');
		$replace = array('', '', '(', ')', '(', ')', '_', ',', '_', '', '_', '\'', '_', '_', '_', '_', '_', '_', '', '');
		$filename = str_replace($illegal, $replace, $filename);
		
		// Remove control characters.
		$filename = preg_replace('/([\\x00-\\x1f\\x7f\\xff]+)/u', '', $filename);
		
		// Standardize whitespace characters.
		$filename = trim(preg_replace('/[\\pZ\\pC]+/u', ' ', $filename));
		
		// Remove excess spaces and replacement characters.
		$filename = trim($filename, ' .-_');
		$filename = preg_replace('/__+/', '_', $filename);
		
		// Change .php files to .phps to make them non-executable.
		if (strtolower(substr($filename, strlen($filename) - 4)) === '.php')
		{
			$filename = substr($filename, 0, strlen($filename) - 4) . '.phps';
		}
		
		// Truncate filenames over 127 chars long, or extensions over 16 chars long.
		if (mb_strlen($filename, 'UTF-8') > 127)
		{
			$extension = strrchr($filename, '.');
			if (mb_strlen($extension, 'UTF-8') > 16) $extension = mb_substr($extension, 0, 16);
			$filename = mb_substr($filename, 0, 127 - mb_strlen($extension)) . $extension;
		}
		
		return $filename;
	}
}