<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Site\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Model\ListModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\Database\ParameterType;
use \Joomla\Utilities\ArrayHelper;
use Zianstages\Component\Stages\Administrator\Helper\SlotsHelper;

/**
 * Methods supporting a list of Stagesdays records.
 *
 * @since  0.1.0
 */
class SlotsModel extends ListModel
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
				'state', 'a.state',
				'ordering', 'a.ordering',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'slot_date', 'a.slot_date',
				'slot_periode', 'a.slot_periode',
				'slot_type', 'a.slot_type',
				'slot_place', 'a.slot_place',
				'slot_public', 'a.slot_public',
				'slot_message', 'a.slot_message',
				'stage_id', 'a.stage_id',
				'color_stage', 'a.color_stage',
			);
		}

		parent::__construct($config);
	}


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState("a.id", "ASC");

		$context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $context);

		// Split context into component and optional section
		$parts = FieldsHelper::extract($context);

		if ($parts)
		{
			$this->setState('filter.component', $parts[0]);
			$this->setState('filter.section', $parts[1]);
		}
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
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__stages_slots` AS a');
		
		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');

		// Join over the foreign key 'stage_id'
		$query->select('`#__s`.`label` AS label');
		$query->join('LEFT', '#__stages_stage AS #__s ON #__s.`id` = a.`stage_id`');
		
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

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');	
			}
		}
		
		// Filtering slot_type
		$filter_slot_type = $this->state->get("filter.slot_type");

		if ($filter_slot_type !== null && (is_numeric($filter_slot_type) || !empty($filter_slot_type)))
		{
			$query->where('FIND_IN_SET(' . $db->quote($filter_slot_type) . ', ' . $db->quoteName('a.slot_type') . ')');
		}

		// Filtering slot_place
		$filter_slot_place = $this->state->get("filter.slot_place");

		if ($filter_slot_place !== null && (is_numeric($filter_slot_place) || !empty($filter_slot_place)))
		{
			$query->where("a.`slot_place` = '".$db->escape($filter_slot_place)."'");
		}

		// Filtering slot_public
		$filter_slot_public = $this->state->get("filter.slot_public");

		if ($filter_slot_public !== null && (is_numeric($filter_slot_public) || !empty($filter_slot_public)))
		{
			$query->where("a.`slot_public` = '".$db->escape($filter_slot_public)."'");
		}
        
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', "a.id");
		$orderDirn = $this->state->get('list.direction', "ASC");

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

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
			// Get the title of every option selected.
			$options      = explode(',', $oneItem->slot_type);

			$options_text = array();

			foreach ((array) $options as $option)
			{

				if ($option !== "")
				{
					$options_text[] = Text::_('COM_STAGES_SLOTS_SLOT_TYPE_OPTION_' . strtoupper($option));
				}
			}
          
			$oneItem->slot_type = !empty($options_text) ? implode(', ', $options_text) : $oneItem->slot_type;
			$oneItem->slot_place = !empty($oneItem->slot_place) ? 
                Text::_('COM_STAGES_SLOTS_SLOT_PLACE_OPTION_' . strtoupper($oneItem->slot_place)) : '';
			$oneItem->slot_public = !empty($oneItem->slot_public) ? 
                Text::_('COM_STAGES_SLOTS_SLOT_PUBLIC_OPTION_' . strtoupper($oneItem->slot_public)) : '';
		}

		return $items;
	}
}
