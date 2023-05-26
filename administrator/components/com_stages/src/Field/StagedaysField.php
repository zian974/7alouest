<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Zianstages\Component\Stages\Administrator\Field;


\defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
/**
 * Form Field class for the Joomla Framework.
 *
 * @since  3.7.0
 */
class StagedaysField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since  3.7.0
	 */
	protected $type = 'stagedays';

    public $stage_id;
    
    public function renderField( $params = [] ) {
        if ( $params['stage_id'] ) {
            $this->stage_id = $params['stage_id'];
        }
        return parent::renderField();
    }
    
    
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array  An array of JHtml options.
	 *
	 * @since   2.5.0
	 */
	protected function getOptions()
	{
        $options = [];
		// Get the client id.
        $stageId = $this->form->getValue('stage_id')??$this->form->getValue('filter.stage_id');
		$stageId = (int) $stageId;

        $db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->select(
				[
					"DISTINCT ".$db->quoteName('slot_date', 'text'),
				]
			)
			->from($db->quoteName('#__stages_slots'))
			->order($db->quoteName('slot_date') . " ASC")
			->where(
				[
					$db->quoteName('stage_id') . ' = ' . $stageId,
				]
			);

		$items = $db->setQuery($query)->loadObjectList();
		if ($items)
		{
			$lang = Factory::getLanguage();
			$options[] = HTMLHelper::_('select.option', '', $header_title);
			foreach ($items as &$item)
			{
				// Load language
				$extension = $item->value;
				$options[] = HTMLHelper::_('select.option', $item->text, HTMLHelper::_('date', $item->text, Text::_('l d F Y')));
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
