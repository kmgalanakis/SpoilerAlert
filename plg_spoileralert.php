<?php
/* ------------------------------------------------------------------------
  # plg_spoileralert.php - Spoiler Alert Content Plugin
  # ------------------------------------------------------------------------
  # author    Konstantinos Galanakis
  # copyright Copyright (C) 2015 Konstantinos Galanakis. All Rights Reserved
  # license   MIT License - http://en.wikipedia.org/wiki/MIT_License
  # website   https://github.com/kmgalanakis
  ------------------------------------------------------------------------- */


defined('_JEXEC') or die;

class plgContentPlg_SpoilerAlert extends JPlugin {

    public function onContentPrepare($context, &$article, &$params, $limitstart)
    {
	if (!JFactory::getApplication()->isAdmin())
	{
	    JHtml::_('jquery.framework');
	    
	    $document = JFactory::getDocument();
	    
	    $type = $document->getType();

	    if ($type == 'html')
	    {
		$currentHeadData = $document->getHeadData();
				
		$path = JPATH_PLUGINS . str_replace("plugins", "", strstr(realpath(dirname(__FILE__)), 'plugins'));
		
		$this->importShortCodeFiles($path);

		$data = do_shortcode(shortcode_unautop($article->text));
		
		$newHeadData = $document->getHeadData();
		
		$scripts =  (array)  array_diff_key($currentHeadData['scripts'], $newHeadData['scripts']);
		$styles  =  (array) array_diff_key($currentHeadData['styleSheets'], $newHeadData['styleSheets']);

		$finalHeadData = '';

		foreach ($scripts as $key => $type)
		{
		    $document->addScript($key);
		}

		foreach ($styles as $key => $type)
		{
		    $document->addStyleSheet($key);
		}
		
		$article->text = $data;
	    }
	}
    }

    public function importShortCodeFiles($path)
    {
	$shortcodes = array();

	$pluginshortcodes = glob($path . '/shortcodes/*.php');

	foreach ((array)$pluginshortcodes as $value) $shortcodes[] = basename($value);

	$shortcodes = array_unique($shortcodes);

	require_once('core/wp_shortcodes.php');

	foreach ($shortcodes as $shortcode)
	{
	    require_once('shortcodes/' . $shortcode);
	}
    }

}
