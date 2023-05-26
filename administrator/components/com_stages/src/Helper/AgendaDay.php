<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Administrator\Helper;

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Object\CMSObject;


use Zianstages\Component\Stages\Administrator\Helper\Slot;
use Zianstages\Component\Stages\Administrator\Helper\AgendaSlotGroup;

/**
 * Stages_slots helper.
 *
 * @since  0.1.0
 */
class AgendaDay extends CMSObject
{
    var $id;
    var $stage_id;
    var $day;
    var $dayTitle;
    var $dayIndex;
    var $daySubTitle;
    var $dayActive = NULL;
    var $timeSlots = [];

    
    /**
     * 
     * @param type $date
     */
    function __construct( $date ) {
        $dateDayFormatter = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE, NULL,NULL, "cccc");
        $dateMonthFormatter = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE, NULL,NULL, "LLLL");
        
        $this->day = $date->format('Y-m-d');
        $this->dayTitle = ucfirst($dateDayFormatter->format($date));
        $this->daySubTitle = $date->format('d') .' '. $dateMonthFormatter->format($date);
    }


    /**
     * 
     * @param type $obj
     * @return $this
     */
    public function populate( $obj ) 
    {

        $this->stage_id = $obj->stage_id;

        $timeSlots = new AgendaSlot;
        $timeSlots->id = $obj->id;
        $timeSlots->schedule = $obj->slot_periode;
        $timeSlots->ct_restant = $obj->ct_restant;
        $timeSlots->place = $obj->slot_place;
        $timeSlots->type = $obj->slot_type;

        $groupe = new AgendaSlotGroup(  $obj->slot_public );
        $groupe->warning =  '';
        
        $timeSlots->groupe = $groupe;
        
        
        if ( $timeSlots->schedule == 'morning' && !empty($this->timeSlots) ) {
            echo 'move';
        	array_unshift($this->timeSlots, $timeSlots);
        }
        else {
        	array_push($this->timeSlots, $timeSlots);
        }
        
        return $this;
    }

    
    /**
     * 
     * @return type
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    
}
