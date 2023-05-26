<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_Stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Site\Field;

defined('JPATH_BASE') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Form\FormField;

/**
 * Supports an HTML select list of categories
 *
 * @since  0.1.0
 */
class StageslotField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since  0.1.0
	 */
	protected $type = 'slot';


    var $options;


    public function renderField($options=[]) {
        $this->options = $options;
        return parent::renderField($options);
    }


	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   0.1.0
	 */
	protected function getInput()
	{
		$liste = self::getData($this->options);

		// Initialize variables.
		$html = array();

		$html[] = '<select class="form-select" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'">';

		$selectedFlag = false;

		foreach( $liste as $labelWeek => $week ) {

			$html[] = '<optgroup label="'. $labelWeek .'">';

			foreach( $week as $day ) {
				
				$html[] = '<option value="'. $day->date.'"';
				if ( $this->value == $day->date ) $html[] = ' selected';
				$html[] = '>'. $day->date .'</option>';
			}

			$html[] = '</optgroup>';
		}

		$html[] = '</select>';

		return implode( $html );
	}


	private static function getData( $options ) {
		
		$days = [];

		$inc = 0;
		foreach( $options['days'] as $week ) {
			$item = [];

			foreach( $week->data as $data ) {
				$day = new \StdClass;
				$day->date = $data->day;
				$day->selected = false;
				
					

				$item[] = $day;
			}
			
			$inc++;
			$days['semaine '. $inc] = $item;
		} 

		return $days;
	}
}
