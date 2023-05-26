<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Api\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Model\ListModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\Database\ParameterType;
use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Date\Date;
use Zianstages\Component\Stages\Administrator\Helper\{AgendaSlot, AgendaSlotGroup};
;

/**
 * Methods supporting a list of Stagesdays records.
 *
 * @since  0.1.0
 */
class AgendasModel extends ListModel
{
	/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'stage_id', 'a.stage_id'
			);
		}
		parent::__construct($config);
	}
    

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string A store id.
	 *
	 * @since   0.1.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

    
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  DatabaseQuery
	 *
	 * @since   0.1.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select([
            'a.id',
            'a.stage_id',
            'slot_date' ,
            'slot_periode',
            'slot_type',
            'slot_place',
            'slot_public',
            'slot_message',
            'YEAR(slot_date) as year',
            'WEEK(slot_date) as week',
            '(
                SELECT COUNT(ss.id) 
                FROM '
                    . $db->quoteName('#__stages_stagiaires', 'ss') . 
            '   WHERE ss.slot_id = a.id AND ss.horaire = a.slot_periode
                    AND ss.state = 1
                    AND ss.presence = 1
            ) as ct_stagiaires'
		]);
		$query->from( $db->quoteName('#__stages_slots', 'a') );

		// Join over the foreign key 'stage_id'
		$query->select('`#__s`.`label` AS label');
		$query->join('LEFT', '#__stages_stages AS #__s ON #__s.`id` = a.`stage_id`');
		

		// Filtering stage_id
		$filter_stageId = $this->state->get("filter.stage_id");

		if ($filter_stageId !== null && (is_numeric($filter_stageId) || !empty($filter_stageId)))
		{
			$query->where("a.`stage_id` = ".$db->escape($filter_stageId)."");
		}

        
		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif (empty($published))
		{
			$query->where('(a.state IN (0, 1))');
		}

        
        $query->order($db->escape('slot_date' . ' ' . 'ASC'));
		$query->order($db->escape('slot_periode' . ' ASC'));
        
//echo $query->__toString();exit;
        
		return $query;
	}

    
	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}
		try
		{
			// Load the list items and add the items to the internal cache.
			$this->cache[$store] = $this->_getList($this->_getListQuery(), $this->getStart(), $this->getState('list.limit'));
		}
		catch (\RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$items = $this->cache[$store];
        foreach ($items as $oneItem)
		{
            $diff = $this->state->get("filter.countMax", 0) - $oneItem->ct_stagiaires;
            $oneItem->ct_restant = ($diff > 0)?$diff:0;
		}
        
		return $items;
	}
}
