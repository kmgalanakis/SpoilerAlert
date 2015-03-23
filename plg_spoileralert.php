<?php
/*@@phpsig@@*/

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
		
		require_once('core/shortcode_include.php');
		
		importShortCodeFiles($path);

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

}
