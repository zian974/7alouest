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

use \Joomla\CMS\Factory;
use \Joomla\CMS\Form\FormField;

/**
 * Supports an HTML select list of categories
 *
 * @since  0.1.0
 */
class DateField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since  0.1.0
	 */
	protected $type = 'date';
    
    protected $min;
    protected $max;
    
    public function renderField( $options = [] )
    {
        $this->min = $options['min'];
        $this->max = $options['max'];    
        
        return parent::renderField( $options );
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
		// Initialize variables.
		$html = array();
		
        // Load user
		$user_id = $this->value;
		
        $html[] = '<input type="date" id="'.$this->id.'" 
			name="'.$this->name.'" 
			value="'.$this->value.'" 
			class="form-control" 
			required="'.$this->required.'"';
        
        if ( !empty($this->min) ) {
            $html[] = "min='$this->min'";
        }
        if ( !empty($this->max) ) {
            $html[] = "max='$this->max'";
        }
        if ( $this->readonly) {
            $html[] = 'disabled ';
        }
        if ( $this->pattern) {
            $html[] = 'pattern="'.$this->pattern.'" ';
        }
        
        $html[] = '>';

		return implode( $html );
	}
}
