<?php
 #-------------------------------------------------------------------------- 
 # Spoiler Alert Content Plugin 
 # ------------------------------------------------------------------------- 
 # version   0.2 
 # date      26 March 2015 
 # author    Konstantinos Galanakis - https://github.com/kmgalanakis 
 # copyright Copyright (C) 2015 Konstantinos Galanakis. All Rights Reserved 
 # license   GNU/GPL license v2: https://www.gnu.org/licenses/gpl-2.0.html 
 #--------------------------------------------------------------------------

//no direct accees
defined('_JEXEC') or die('resticted aceess');

function importShortCodeFiles($path)
{
    $shortcodes = array();

    $pluginshortcodes = glob($path . '/shortcodes/*.php');

    foreach ((array)$pluginshortcodes as $value)
	    $shortcodes[] = basename($value);

    $shortcodes = array_unique($shortcodes);

    require_once('wp_shortcodes.php');

    foreach ($shortcodes as $shortcode)
    {
	require_once(__DIR__ . '/../shortcodes/' . $shortcode);
    }
}
