<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_Stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Administrator\Field;

defined('JPATH_BASE') or die;

use \Joomla\CMS\Form\FormField;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Date\Date;

/**
 * Supports an HTML select list of categories
 *
 * @since  0.1.0
 */
class TimeupdatedField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $type = 'timeupdated';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 *
	 * @since   0.1.0
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		$old_time_updated = $this->value;
		$hidden           = (boolean) $this->element['hidden'];

		if ($hidden == null || !$hidden)
		{
			if (!strtotime($old_time_updated))
			{
				$html[] = '-';
			}
			else
			{
				$jdate       = new Date($old_time_updated);
				$pretty_date = $jdate->format(Text::_('DATE_FORMAT_LC2'));
				$html[]      = "<div>" . $pretty_date . "</div>";
			}
		}

		$time_updated = Factory::getDate('now', Factory::getConfig()->get('offset'))->toSql(true);
		$html[]       = '<input type="hidden" name="' . $this->name . '" value="' . $time_updated . '" />';

		return implode($html);
	}
}
