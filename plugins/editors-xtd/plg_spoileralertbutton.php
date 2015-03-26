
<?php
 #-------------------------------------------------------------------------- 
 # Spoiler Alert Content Plugin 
 # ------------------------------------------------------------------------- 
 # version   ${extension.version} 
 # date      26 March 2015 
 # author    Konstantinos Galanakis - https://github.com/kmgalanakis 
 # copyright Copyright (C) 2015 Konstantinos Galanakis. All Rights Reserved 
 # license   GNU/GPL license v2: https://www.gnu.org/licenses/gpl-2.0.html 
 #--------------------------------------------------------------------------

defined('_JEXEC') or die;
class plgButtonPlg_SpoilerAlertButton extends JPlugin
{
    function onDisplay($name)
    {
        $document = JFactory::getDocument();
	$document->addStyleSheet(JURI::base(true).'/../plugins/editors-xtd/plg_spoileralertbutton/css/style.css');
        $jsCode = "function insertShortCode(nameOfEditor) { jInsertEditorText('[spoiler]This is a dummy spoiler text[/spoiler]', nameOfEditor); } ";
        $document->addScriptDeclaration($jsCode);
        $button = new JObject();
	$button->set('modal',false);
	$button->set('class','btn');
        $button->set('text','Spoiler Alert');
        $button->set('onclick', 'insertShortCode(\''.$name.'\')');
        $button->set('name', 'spoiler');
        return $button;

    }

}
?>

