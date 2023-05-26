<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_Stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Administrator\Model;

// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Model\ListModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\Database\ParameterType;
use \Joomla\Utilities\ArrayHelper;
use Zianstages\Component\Stages\Administrator\Helper\StagesStagiairesHelper;

/**
 * Methods supporting a list of Stagiaires records.
 *
 * @since  0.1.0
 */
class StagiairesModel extends ListModel
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
				'date', 'a.date',
				'horaire', 'a.horaire',
				'nom', 'a.nom',
				'prenom', 'a.prenom',
				'licence', 'a.licence',
				'email', 'a.email',
				'telephone', 'a.telephone',
				'ddn', 'a.ddn',
				'pointure', 'a.pointure',
				'reglement', 'a.reglement',
				'presence', 'a.presence',
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
		$app  = Factory::getApplication('com_stages');
		
		// List state information.
		parent::populateState('date', 'ASC');

		$context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		
		$this->setState('filter.search', $context);
		

		if ( empty($this->state->get('filter.stage_id'))) {
			$this->state->set('filter.stage_id', $app->getUserState('com_stages.agenda.stage_id'));
		}


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
		$query->from('`#__stages_stagiaires` AS a');
		
		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');

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

		// Filtering licence
		$filter_stageId = $this->state->get("filter.stage_id");

		if ($filter_stageId !== null && (is_numeric($filter_stageId) || !empty($filter_stageId)))
		{
			$query->where("a.`stage_id` = ".$db->escape($filter_stageId)."");
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
				$query->where('( a.date LIKE ' . $search . '  OR  a.horaire LIKE ' . $search . '  OR  a.nom LIKE ' . $search . '  OR  a.prenom LIKE ' . $search . ' )');
			}
		}
		

		// Filtering horaire
		$filter_horaire = $this->state->get("filter.horaire");

		if ($filter_horaire !== null && (is_numeric($filter_horaire) || !empty($filter_horaire)))
		{
			$query->where("a.`horaire` = '".$db->escape($filter_horaire)."'");
		}

		// Filtering licence
		$filter_licence = $this->state->get("filter.licence");

		if ($filter_licence !== null && (is_numeric($filter_licence) || !empty($filter_licence)))
		{
			$query->where("a.`licence` = '".$db->escape($filter_licence)."'");
		}

		// Filtering reglement
		$filter_reglement = $this->state->get("filter.reglement");

		if ($filter_reglement !== null && (is_numeric($filter_reglement) || !empty($filter_reglement)))
		{
			$query->where("a.`reglement` = '".$db->escape($filter_reglement)."'");
		}

		// Filtering date
		$filter_date = $this->state->get("filter.date");
        

        if ($filter_date !== null && !empty($filter_date))
		{
			$query->where("a.`date` = '".$db->escape($filter_date)."'");
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'date');
		$orderDirn = $this->state->get('list.direction', 'ASC');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		$query->order($db->quoteName('horaire') . ' ASC');


		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		foreach ($items as $oneItem)
		{
			$oneItem->horaire = $oneItem->horaire == 0 || !empty($oneItem->horaire) ? 
				Text::_('COM_STAGES_HORAIRE_OPTION_' . strtoupper($oneItem->horaire)) : '';
		}

		return $items;
	}
}
