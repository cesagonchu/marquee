<?php
/**
 * ****************************************************************************
 * marquee - MODULE FOR XOOPS
 * Copyright (c) Herv� Thouzard (http://www.herve-thouzard.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Herv� Thouzard (http://www.herve-thouzard.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         marquee
 * @author 			Herv� Thouzard (http://www.herve-thouzard.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */

if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}


/**
 * Returns a module's option
 *
 * Return's a module's option (for the news module)
 *
 * @package Marquee
 * @author Herv� Thouzard (http://www.herve-thouzard.com)
 * @copyright	(c) Herv� Thouzard
 * @param string $option	module option's name
 */
function marquee_getmoduleoption($option, $repmodule = 'marquee')
{
	global $xoopsModuleConfig, $xoopsModule;
	static $tbloptions = Array();
	if(is_array($tbloptions) && array_key_exists($option,$tbloptions)) {
		return $tbloptions[$option];
	}

	$retval=false;
	if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
		if(isset($xoopsModuleConfig[$option])) {
			$retval = $xoopsModuleConfig[$option];
		}
	} else {
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname($repmodule);
		$config_handler =& xoops_gethandler('config');
		if ($module) {
		    $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
	    	if(isset($moduleConfig[$option])) {
	    		$retval = $moduleConfig[$option];
	    	}
		}
	}
	$tbloptions[$option] = $retval;
	return $retval;
}

/**
 * Verify if the current "user" is a bot or not
 *
 * If you have a problem with this function, insert the folowing code just before the line if(isset($_SESSION['news_cache_bot'])) { :
 * return false;
 *
 * @package Marquee
 * @author Herv� Thouzard (http://www.herve-thouzard.com)
 * @copyright	(c) Herv� Thouzard
 */
function marquee_isbot()
{
	if(isset($_SESSION['marquee_cache_bot'])) {
		return $_SESSION['marquee_cache_bot'];
	} else {
		// Add here every bot you know separated by a pipe | (not matter with the upper or lower cases)
		// If you want to see the result for yourself, add your navigator's user agent at the end (mozilla for example)
		$botlist = 'AbachoBOT|Arachnoidea|ASPSeek|Atomz|cosmos|crawl25-public.alexa.com|CrawlerBoy Pinpoint.com|Crawler|DeepIndex|EchO!|exabot|Excalibur Internet Spider|FAST-WebCrawler|Fluffy the spider|GAIS Robot/1.0B2|GaisLab data gatherer|Google|Googlebot-Image|googlebot|Gulliver|ia_archiver|Infoseek|Links2Go|Lycos_Spider_(modspider)|Lycos_Spider_(T-Rex)|MantraAgent|Mata Hari|Mercator|MicrosoftPrototypeCrawler|Mozilla@somewhere.com|MSNBOT|NEC Research Agent|NetMechanic|Nokia-WAPToolkit|nttdirectory_robot|Openfind|Oracle Ultra Search|PicoSearch|Pompos|Scooter|Slider_Search_v1-de|Slurp|Slurp.so|SlySearch|Spider|Spinne|SurferF3|Surfnomore Spider|suzuran|teomaagent1|TurnitinBot|Ultraseek|VoilaBot|vspider|W3C_Validator|Web Link Validator|WebTrends|WebZIP|whatUseek_winona|WISEbot|Xenu Link Sleuth|ZyBorg';
		$botlist = strtoupper($botlist);
		$currentagent = strtoupper(xoops_getenv('HTTP_USER_AGENT'));
		$retval = false;
		$botarray = explode('|',$botlist);
		foreach($botarray as $onebot) {
			if(strstr($currentagent,$onebot)) {
				$retval = true;
				break;
			}
		}
	}
	$_SESSION['marquee_cache_bot'] = $retval;
	return $retval;
}

/**
 * Escape a string so that it can be included in a javascript string
 */
function marquee_javascript_escape($string)
{
	return str_replace("'","\\'",$string);
}
