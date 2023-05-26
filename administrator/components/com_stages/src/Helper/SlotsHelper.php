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

use Zianstages\Component\Stages\Administrator\Helper\AgendaWeeks;

/**
 * Stages_slots helper.
 *
 * @since  0.1.0
 */
class SlotsHelper
{

	public static function getIdByDateAndPeriode( $date, $period, $stage ) {
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('id')
			->from('#__stages_slots')
			->where('slot_date = ' . $db->quote($date))
			->where('slot_periode = ' . $db->quote($period))
			->where('stage_id = ' . (int)$stage);
// echo $query->__toString();exit;
		$db->setQuery($query);

		return $db->loadObject();
	}


	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}


	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  CMSObject
	 *
	 * @since   0.1.0
	 */
	public static function getActions()
	{
		$user   = Factory::getUser();
		$result = new CMSObject;

		$assetName = 'com_stages';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
    
    /**
     * @deprecated since version 0.1.3
     * 
	 * Gets the items
	 *
	 * @param   int     $stage_id     L'id du stage
	 *
	 * @return  mixed  Array with all the formatted items and the weeks list
	 */
//	public static function getList($stage_id)
//	{
//		$db = Factory::getDbo();
//		$query = $db->getQuery(true);
//
//		$query
//			->select([
//				'sd.id',
//				'sd.stage_id',
//                'slot_date' ,
//                'slot_periode',
//                'slot_type',
//                'slot_place',
//                'slot_public',
//                'slot_message',
//				'YEAR(slot_date) as year',
//				'WEEK(slot_date) as week',
//				'(
//					SELECT COUNT(ss.id) 
//					FROM '
//						. $db->quoteName('#__stages_stagiaires', 'ss') . 
//				'   WHERE ss.slot_id = sd.id AND ss.horaire = sd.slot_periode
//				) as ct_stagiaire' 
//            ])
//			->from( $db->quoteName('#__stages_slots', 'sd') )
//			->where('sd.stage_id = ' . (int) $stage_id )
//			->order('slot_date')
//			->order('slot_periode');;
//
//// echo $query->__toString();exit;
//
//		$db->setQuery($query);
//		
//		$daysBdd = $db->loadObjectList();
//		
//		$weeks = new AgendaWeeks($daysBdd);
//
//        return [ $weeks->weeks, $weeks->weeksList ];
//
//	}
}

