<?php
/**
 * @version    CVS: 0.0.1
 * @package    Com_stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Administrator\Helper;
use Zianstages\Component\Stages\Administrator\Helper\AgendaDay;

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;


/**
 * Stages_slots helper.
 *
 * @since  0.0.1
 */
class AgendaWeeks
{
	var $stage_id = null;

	var $weeks = [];
	var $weeksList = [];

	public function __construct( $days ) {
		$this->formatListe($days);
	}

	private function formatListe($days) {

		for ($i=0; $i<count($days); $i++) {

			$week = null;

			foreach( $this->weeks as $weekitem  ) {		
				if( $weekitem->year === $days[$i]->year && $weekitem->week === $days[$i]->week ) {
					$week = $weekitem;
				}
			}
			
			if ( is_null($week) ) {
				$week = new \StdClass;
				$week->year = $days[$i]->year;
				$week->week = $days[$i]->week;
				$this->weeksList[] = "semaine" . (count($this->weeksList) + 1);
				$jour = new \DateTime();
				$jour = $jour->setISODate($days[$i]->year, $days[$i]->week, 0 );
				
				for ( $j=0; $j<7; $j++ ) {
					$dt = $jour->modify( '+1 day' );
					$week->data[$j] = new AgendaDay(clone $jour);
					$week->data[$j]->dayIndex = $j;
					if ( $j == 0 ) $week->data[$j]->dayActive = true;
				}
				array_push($this->weeks, $week);
			}

			$day = $days[$i];
            
			$key = array_search($day->slot_date, array_column($week->data, 'day'));
			$week->data[$key] = $week->data[$key]->populate($day);		

		}
	}

}

