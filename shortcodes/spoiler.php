<?php
/*@@phpsig@@*/

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

$document = JFactory::getDocument();

$path = JURI::root(true).'/plugins'.str_replace("shortcodes", "", str_replace("plugins", "", strstr(realpath(dirname(__FILE__)), 'plugins')));

$document->addStyleSheet($path.'css/style.css');

$document->addScript($path.'js/spoiler.js');

jimport('joomla.registry.registry');

if(!function_exists('spoiler_sc')){
    
    function spoiler_sc($atts, $content=''){
	
	extract(shortcode_atts(array("type" => '', "style" =>'', "close" => true), $atts));
	
	$spoilerID = 'id'.rand(0, 9999);
	
	$data = '<spoiler id="sa_'.$spoilerID.'">'. do_shortcode( $content ) . '</spoiler>';
	 
	$plugin = JPluginHelper::getPlugin('content', 'plg_spoileralert');    
	
	$pluginParams = new JRegistry();
	
	$pluginParams->loadString($plugin->params, 'JSON');
	
	$max = (isset($atts['max']) && $atts['max'] != '' ? $atts['max'] : $pluginParams->get('max', 4));
	
	$partial = (isset($atts['partial']) && $atts['partial'] != '' ? $atts['partial'] : $pluginParams->get('partial', 2));
	
	$options = "max: $max, partial: $partial,";
	
	$data .= "  <script>"
		.	"var jq = jQuery.noConflict();"
		.	"jq(document).ready("
		.	    "jq('spoiler#sa_$spoilerID, .spoiler').spoilerAlert({ $options })"
		.	");"
		.    "</script>";	
	 
	return $data;
	 
    }
	
    add_shortcode('spoiler','spoiler_sc');
}

