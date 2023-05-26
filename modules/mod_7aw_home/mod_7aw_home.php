
<?php
/**
 * @package     7aw
 * @subpackage  modules.mod_7aw_home
 * 
 * @author     Yann "Zian" CUIDET <zian.cuidet@protonMail.com>
 * @link       https://zian.re
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.7alouest.re/>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

require ModuleHelper::getLayoutPath('mod_7aw_home', $params->get('layout', 'default'));