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
class StagiaireuniqueRule extends FormRule {
    public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
        
        $db = Factory::getDbo();
		$query = $db->getQuery(true);
        $query
			->select('id' )
			->from($db->quoteName('#__stages_stagiaires', 'a'))
			->where( 'nom = ' . $db->quote($input->get('nom')) )
			->where( 'prenom = ' . $db->quote($input->get('prenom')) )
			->where( 'email = ' . $db->quote($input->get('email')) )
			->where( 'date = ' . $db->quote($input->get('date')) )
			->where( 'horaire = ' . $db->quote($input->get('horaire')) );

		$db->setQuery($query);

        $list = $db->loadObjectList();

        if ( count($list) > 0 && $list[0]->id != $input->get('id') ) {

            throw new \InvalidArgumentException(sprintf('Le stagiaire est déjà inscrit sur ce créneau', \get_class($this)));
        }

        return true;
    }
}