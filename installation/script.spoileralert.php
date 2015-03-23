<?php
/*@@phpsig@@*/

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
	$this->description = '<p>'
		. '<a href="https://github.com/kmgalanakis/SpoilerAlert" title="Spoiler Alert">Spoiler Alert</a> by <a href="https://github.com/kmgalanakis" title="Konstantinos Galanakis">Konstantinos Galanakis</a>  is a Joomla Conten Plugin based on a jQuery plugin, developed by Joshua Hull, that create spoiler alerts by bluring the desired parts of your page.<br />'
		. '</p>'
		. '<p>'
		. 'As he says, don\'t spoil it! Hide copy and images with a bit of SVG blur. Taste on mouseover. Eat on click.'
		. '</p>'
		. '<p>'
		. 'Do you publish spoilers? Do you wish you could have them on your page in a way that wasn\'t fucking rude? With Spoiler Alert! you can! Hide spoilers with a bit of blur.'
		. '</p>'
		. '<p>'
		. 'But for Joomla. Also compatible with K2.'
		. '</p>';

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
	$this->results($status, "install");
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
	$this->results($status, "uninstall");
    }

    private function results($status, $action)
    {
	$language = JFactory::getLanguage();
	$languagePath = JPATH_PLUGINS . 'DS' . 'content' . 'DS' . "$this->extensionName";

	if ($action == "install")
	{
	    $resultTrue = "Installed";
	    $resultFalse = "Not installed";
	}
	else
	{
	    $resultTrue = "Unistalled";
	    $resultFalse = "Not unistalled";
	}

	$rows = 0;

	?>
	<!--<img src="<?php //echo JURI::root(true);    ?>/plugins/content/incptvtweetable/images/incptvtweetable173x48.jpg" alt="Inceptive Tweetable" align="right" />-->
	<?php echo ($action == "install") ? "<h2>Installation status</h2>" : "<h2>Uninstallation status</h2>"; ?>
	<div>
	    <?php echo $this->description; ?>
	</div>
	<table class="adminlist table table-striped">
	    <thead>
		<tr>
		    <th class="title" colspan="2">Extension</th>
		    <th width="30%">Status</th>
		</tr>
	    </thead>
	    <tfoot>
		<tr>
		    <td colspan="3"></td>
		</tr>
	    </tfoot>
	    <tbody>
		<tr>
		    <th>Plugin</th>
		    <th>Group</th>
		    <th></th>
		</tr>
		<tr class="row0">
		    <td class="key"><?php echo $this->humanReadableName; ?></td>
		    <td class="key">Content</td>
		    <td><strong>Installed</strong></td>
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
