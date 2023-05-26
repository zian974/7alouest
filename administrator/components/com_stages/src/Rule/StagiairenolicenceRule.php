<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Zianstages\Component\Stages\Administrator\Rule;

\defined('JPATH_PLATFORM') or die;

use \Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormRule;
use Joomla\Registry\Registry;

/**
 * Form Rule class for the Joomla Platform.
 *
 * @since  1.7.0
 */
class StagiairenolicenceRule extends FormRule {
    public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		
		if ( $input->get('licence') ) {
			$input->set('pointure', null);
			$input->set('ddn', null);
			return true;
		}
		
        if ( empty($input->get('pointure') ) || empty($input->get('ddn') )) {
			Factory::getApplication()->enqueueMessage( "La date de naissance et la pointure du stagiaire doivent être saisi",
				'warning'
			);

			return false;
        }

        return true;
    }
}