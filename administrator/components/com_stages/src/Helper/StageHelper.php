<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_Stages
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

/**
 * Stages helper.
 *
 * @since  0.1.0
 */
class StageHelper
{
	/**
	 * Retourne le nombre total de stagiaire du stage
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getNbStagiaires($stage_id )
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select(['id', 's.licence'])
			->from($db->quoteName('#__stages_stagiaires', 's'))
			->where($db->quoteName('s.stage_id') .'= ' . (int) $stage_id);
        $query->where('s.state = 1');
        $query->where('s.presence = 1');
        
		$db->setQuery($query);
        $list = $db->loadObjectList();
        
        $params = \Joomla\CMS\Component\ComponentHelper::getParams('com_stages');

        
        $sum = 0;
        foreach( $list as $v ) {
            if ( empty($v->licence) ) {
                $sum += $params->get('cost_non_licencie');
            }
            else {
                $sum += $params->get('cost_licencie');
            }
        }


        return (object)['nb_stagiaires' => count($list), 'income' => $sum ];
	}
}

