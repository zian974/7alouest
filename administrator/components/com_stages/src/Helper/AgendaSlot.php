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


class AgendaSlotGroup extends CMSObject {

    var $color;
    var $label;
    var $sublabel;


    public function __construct( $label ) {
        $this->color = self::translateColor( $label );
        $this->label = self::translateLabel( $label );
        $this->sublabel = self::translateSubLabel( $label );
    }


    public static function translateColor( $label ) {
        $data = [
            'nonlicence69' => 'rouge-2',
            'nonlicence711' => 'orange',
            'nonlicence1014' => 'rouge',
            'nonlicence1217' => 'rouge',
            'licence69' => 'vert-2',
            'licence611' => 'vert-2',
            'licence711' => 'vert-2',
            'licence1014' => 'vert',
            'licence1217' => 'vert'
        ];     

       return $data[ $label ];
    }


    public static function translateLabel( $label ) {
        $data = [
            'nonlicence35' => 'Non licenciés',
            'nonlicence69' => 'Non licenciés',
            'nonlicence711' => 'Non licenciés',
            'nonlicence1014' => 'Non licenciés',
            'nonlicence1217' => 'Non licenciés',
            'licence35' => 'Licenciés',
            'licence69' => 'Licenciés',
            'licence611' => 'Licenciés',
            'licence711' => 'Licenciés',
            'licence1014' => 'Licenciés',
            'licence1217' => 'Licenciés'
        ];   
        return mb_strtoupper($data[ $label ]);
    }


    public static function translateSubLabel( $label ) {
        $data = [
            'nonlicence35' => '3-5 ans',
            'nonlicence69' => '6-9 ans',
            'nonlicence711' => '7-11 ans',
            'nonlicence1014' => '10-14 ans',
            'nonlicence1217' => '12-17 ans',
            'licence35' => '3-5 ans',
            'licence69' => '6-9 ans',
            'licence611' => '6-11 ans',
            'licence711' => '7-11 ans',
            'licence1014' => '10-14 ans',
            'licence1217' => '12-17 ans'
        ];    

       return $data[ $label ];
    }

}


/**
 * Stages_slots helper.
 *
 * @since  0.1.0
 */
class AgendaSlot extends CMSObject implements \JsonSerializable
{
    private $schedule = null;
    private $type;
    private $place;
    private $groupe;
    private $warning = '';
    
    function __construct() {
    }

    public function __set( $name, $value ) {
        switch ( $name ) {
            case 'schedule':
                $this->$name = self::translateSchedule( $value );
                break;
            case 'type':
                $this->$name = self::translateType( $value );
                break;
            case 'place': 
                $this->$name = self::translatePlace( $value );
                break;
            // case 'groupe':
            //     $this->$name = new StageslotGroupHelper();
            //     break;
            default:
                $this->$name = $value;
        }
    }
    
    public function __get( $name ) {
        return $this->$name;
    }

    public static function translateSchedule( $typeValue ) {
       
        $data = [
            '0' => "8h30 - 11h30",
            '1' => "9h - 12h",
            '2' => "14h - 16h",
            '3' => "14h - 17h",
            '4' => "16h - 18h"
        ];        
         
        return $data[$typeValue];
    }
    
    /**
     * @deprecated version 0.1.2
     */
//    public static function translatePeriode( $label ) {
//        $data = [
//            0 => '8h30 - 11h',
//            1 => '14h00 - 17h00'
//        ]; 
//       return $data[ $label ];
//    }

    public static function translatePlace( $typeValue ) {

        $data = [
            'les-avirons' => 'Les Avirons - Ravine des Avirons',
            'saint-leu-mur' => 'Saint-Leu - Mur de Saint-Leu',
            'saint-leu-colim' => 'Saint-Leu - Ravine des Colimaçons',
            '3bassins-barrage' => 'Trois-bassins - Barrage'
        ];        
        return $data[$typeValue];
    } 
    
    public static function translateHoraire( $label ) {
        $data = [
            '0' => 'Matin',
            '1' => 'Après-midi'
        ];     

       return $data[ $label ];
    }

    public static function translateType( $label ) {

        $data = [
            'voies' => 'Voies',
            'bloc' => 'Bloc',
            'voiesbloc' => 'Voies & bloc'
        ];       

        return $data[ $label ];
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
