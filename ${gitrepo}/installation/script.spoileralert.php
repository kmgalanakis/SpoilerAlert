<?php
/* @@phpsig@@ */

// No Direct Access
defined('_JEXEC') or die;

// Script
class plgContentPlg_SpoilerAlertInstallerScript {

    private $extensionName;
    private $humanReadableName;
    private $additionalPlugins;
    private $description;

    function __construct()
    {
	$this->extensionName = "plg_spoileralert";
	$this->humanReadableName = "Spoiler Alert";
	$this->additionalPlugins = array('plg_spoileralertbutton' => "Spoiler Alert Editor Button");
    }

    function postflight($type, $parent)
    {
	$app = JFactory::getApplication();
	$db = JFactory::getDBO();

	$db->setQuery("UPDATE #__extensions SET enabled = 1 WHERE folder='content' AND element = '$this->extensionName'");

	$db->execute();

	$status = new stdClass;
	$status->plugins = array();
	$src = $parent->getParent()->getPath('source');
	$manifest = $parent->getParent()->manifest;
	$plugins = $manifest->xpath('plugins/plugin');
	foreach ($plugins as $plugin)
	{
	    $name = (string)$plugin->attributes()->plugin;
	    $group = (string)$plugin->attributes()->group;
	    $path = $src . '/plugins/' . $group;
	    if (JFolder::exists($src . '/plugins/' . $group . '/' . $name))
	    {
		$path = $src . '/plugins/' . $group . '/' . $name;
	    }
	    $installer = new JInstaller;
	    $result = $installer->install($path);

	    $query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element=" . $db->Quote($name) . " AND folder=" . $db->Quote($group);
	    $db->setQuery($query);
	    $db->query();
	    $status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
	}
	//echo "<p>Installed</p>";
	$this->results($status, $parent, "install");
    }

    function uninstall($parent)
    {
	$db = JFactory::getDBO();
	$status = new stdClass;
	$status->plugins = array();
	$manifest = $parent->getParent()->manifest;
	$plugins = $manifest->xpath('plugins/plugin');
	foreach ($plugins as $plugin)
	{
	    $name = (string)$plugin->attributes()->plugin;
	    $group = (string)$plugin->attributes()->group;
	    $query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = " . $db->Quote($name) . " AND folder = " . $db->Quote($group);
	    $db->setQuery($query);
	    $extensions = $db->loadColumn();
	    if (count($extensions))
	    {
		foreach ($extensions as $id)
		{
		    $installer = new JInstaller;
		    $result = $installer->uninstall('plugin', $id);
		}
		$status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
	    }
	}
	$this->results($status, $parent, "uninstall");
    }

    private function results($status, $parent, $action)
    {
	$type = (array)$parent->getParent()->manifest->attributes()->type;
	$group = (array)$parent->getParent()->manifest->attributes()->group;

	$language = JFactory::getLanguage();
	$languagePath = $parent->getParent()->getPath('extension_root');
	$language->load('installation', $languagePath);
	$language->load('plg_content_plg_spoileralert', $languagePath);

	$this->description = JText::_('PLG_DESCRIPTION');

	if ($action == "install")
	{
	    $resultTrue = JText::_('PLG_INSTALLATION_INSTALLED');
	    $resultFalse = JText::_('PLG_INSTALLATION_NOT_INSTALLED');
	}
	else
	{
	    $resultTrue = JText::_('PLG_INSTALLATION_UNINSTALLED');
	    $resultFalse = JText::_('PLG_INSTALLATION_NOT_UNINSTALLED');
	}

	$rows = 0;

	?>
	<script type="text/javascript">
	//	    var jq = jQuery.noConflict();
	//	    jq(document).ready(function () {
	//		jq('.span12').prev().hide();
	//		jq([tr]).prev().hide();
	//	    });
	    var targetDivJ25 = document.querySelectorAll(".adminform tr")[0];
	    if (typeof targetDivJ25 !== 'undefined') {
		targetDivJ25.style.display = 'none';
	    }
	    var targetDivJ30 = document.querySelectorAll("#j-main-container .span12")[0];
	    if (typeof targetDivJ30 !== 'undefined') {
		targetDivJ30.style.display = 'none';
	    }
	//	    function fn()
	//	    {
	//		var targetDivJ25 = document.querySelectorAll(".adminform tr")[0];
	//		if (typeof targetDivJ25 !== 'undefined') {
	//		    targetDivJ25.style.display = 'none';
	//		}
	//		var targetDivJ30 = document.querySelectorAll("#j-main-container .span12")[0];
	//		if (typeof targetDivJ30 !== 'undefined') {
	//		    targetDivJ30.style.display = 'none';
	//		}
	//		
	//	    }
	//	    function ready(fn) {
	//		if (document.readyState != 'loading') {
	//		    fn();
	//		} else {
	//		    document.addEventListener('DOMContentLoaded', fn);
	//		}
	//	    }
	//	    ready(fn);
	</script>
	<!--<img src="<?php //echo JURI::root(true);           ?>/plugins/content/incptvtweetable/images/incptvtweetable173x48.jpg" alt="Inceptive Tweetable" align="right" />-->
	<?php echo ($action == "install") ? "<h2>" . JText::_('PLG_INSTALLATION_INST_STATUS') . "</h2>" : "<h2>" . JText::_('PLG_INSTALLATION_UNINST_STATUS') . "</h2>"; ?>
	<div>
	    <?php echo $this->description; ?>
	</div>
	<table class="adminlist table table-striped">
	    <thead>
		<tr>
		    <th class="title" colspan="2"><?php echo JText::_('PLG_INSTALLATION_EXTENSION'); ?></th>
		    <th width="30%"><?php echo JText::_('PLG_INSTALLATION_STATUS'); ?></th>
		</tr>
	    </thead>
	    <tfoot>
		<tr>
		    <td colspan="3"></td>
		</tr>
	    </tfoot>
	    <tbody>
		<tr>
		    <th><?php echo JText::_('PLG_INSTALLATION_' . strtoupper($type[0])); ?></th>
		    <th><?php echo JText::_('PLG_INSTALLATION_GROUP'); ?></th>
		    <th></th>
		</tr>
		<tr class="row0">
		    <td class="key"><?php echo $this->humanReadableName; ?></td>
		    <td class="key"><?php echo JText::_('PLG_INSTALLATION_GROUP_' . strtoupper($group[0])); ?></td>
		    <td><strong><?php echo JText::_('PLG_INSTALLATION_' . strtoupper($action) . 'ED'); ?></strong></td>
		</tr>
		<?php if (count($status->plugins)): ?>
		    <?php foreach ($status->plugins as $plugin): ?>
			<tr class="row<?php echo( ++$rows % 2); ?>">
			    <td class="key"><?php echo ucfirst($this->additionalPlugins[$plugin['name']]); ?></td>
			    <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			    <td><strong><?php echo ($action == "install") ? $resultTrue : $resultFalse; ?></strong></td>
			</tr>
		    <?php endforeach; ?>
		<?php endif; ?>
	    </tbody>
	</table>
	<?php
    }

}
