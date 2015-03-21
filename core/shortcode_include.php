<?php

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
	require_once(__DIR__.'/../shortcodes/' . $shortcode);
    }
}
