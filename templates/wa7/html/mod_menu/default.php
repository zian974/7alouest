<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseScript('mod_menu', 'mod_menu/menu.min.js', [], ['type' => 'module']);
$wa->registerAndUseScript('mod_menu', 'mod_menu/menu-es5.min.js', [], ['nomodule' => true, 'defer' => true]);

$id = '';

if ($tagId = $params->get('tag_id', ''))
{
	$id = ' id="' . $tagId . '"';
}

?>

<ul<?php echo $id; ?> class="mod-menu mod-list nav level1<?php echo $class_sfx; ?> ">

<?php foreach ($list as $i => &$item)
{
	$level2 = "";
	if ( $item->level == 1 ) {
		$level2 = " level2";
	}

	$itemParams = $item->getParams();
	$class      = 'nav-link item-' . $item->id;

	if ($item->id == $default_id)
	{
		$class .= ' default';
	}

	if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id))
	{
		$class .= ' current';
	}

	if (in_array($item->id, $path))
	{
		$class .= ' active';
	}
	elseif ($item->type === 'alias')
	{
		$aliasToId = $itemParams->get('aliasoptions');

		if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
		{
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path))
		{
			$class .= ' alias-parent-active';
		}
	}

	if ($item->type === 'separator')
	{
		$class .= ' divider';
	}

	if ($item->deeper)
	{
		$class .= ' deeper';
	}

	if ($item->parent)
	{
		$class .= ' parent';
	}


	echo '<li class="' . $class . '">';
	switch ($item->type) :
		case 'separator':
		case 'component':
		case 'heading':
		case 'url':
			require ModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
			break;

		default:
			require ModuleHelper::getLayoutPath('mod_menu', 'default_url');
			break;
			
	endswitch;

	// The next item is deeper.
	if ($item->deeper)
	{	
		if ( $item->level == 1 ) echo "<div class='level2-wrapper container'>";
		echo '<ul class="mod-menu__sub list-unstyled small'. $level2 .'">';
	}
	// The next item is shallower.
	elseif ($item->shallower)
	{
		$level2WrapperEnd = "";
		if ( $item->level == 1 ) $level2WrapperEnd = "</div>";
		echo '</li>';
		echo str_repeat('</ul>'. $level2WrapperEnd .'</li>', $item->level_diff);
	}
	// The next item is on the same level.
	else
	{
		echo '</li>';
	}
}
?>

    <li class="nav-fake">
        <div class="nav-fake-inner"></div>
    </li>
</ul>
