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

use \Joomla\CMS\Table\Table;
use \Joomla\CMS\Factory;
use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Plugin\PluginHelper;
use \Joomla\CMS\MVC\Model\ItemModel;
use \Joomla\CMS\Helper\TagsHelper;
use Zianstages\Component\Stages\Site\Helper\StageSlotsHelper;
use \Joomla\CMS\Object\CMSObject;

/**
 * Slot model.
 *
 * @since  0.1.0
 */
class SlotModel extends ItemModel
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 *
	 * @since  0.1.0
	 */
	protected $text_prefix = 'COM_STAGES';

	/**
	 * @var    string  Alias to manage history control
	 *
	 * @since  0.1.0
	 */
	public $typeAlias = 'com_stages.slot';

	/**
	 * @var    null  Item data
	 *
	 * @since  0.1.0
	 */
	public $_item = null;



	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   0.0.5
	 *
	 * @throws Exception
	 */
	protected function populateState()
	{
		$app  = Factory::getApplication('com_stages');
		$user = Factory::getUser();

		// Check published state
		if ((!$user->authorise('core.edit.state', 'com_stages')) && (!$user->authorise('core.edit', 'com_stages')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		// Load state from the request userState on edit or from the passed variable on default
		if (Factory::getApplication()->input->get('layout') == 'edit')
		{
			$id = Factory::getApplication()->getUserState('com_stages.slot.edit.id');
		}
		else
		{
			$id = Factory::getApplication()->input->get('slot_id');
			Factory::getApplication()->setUserState('com_stages.slot.edit.id', $id);
		}
		$this->setState('slot.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('slot.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  Table    A database object
	 *
	 * @since   0.1.0
	 */
	public function getTable($type = 'Slot', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}


	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since   0.1.0
	 */
	public function getItem($id = null)
	{
		
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('slot.id');
			}
			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table && $table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if (isset($table->state) && $table->state != $published)
					{
						throw new \Exception(Text::_('COM_STAGES_AGENDA_ITEM_NOT_LOADED'), 403);
					}
				}

				// Convert the Table to a clean CMSObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, CMSObject::class);
			}

			if (empty($this->_item))
			{
				throw new \Exception(Text::_('COM_STAGES_AGENDA_ITEM_NOT_LOADED'), 404);
			}
		}

		

		if (isset($this->_item->created_by))
		{
			$this->_item->created_by_name = Factory::getUser($this->_item->created_by)->name;
		}

		if (isset($this->_item->modified_by))
		{
			$this->_item->modified_by_name = Factory::getUser($this->_item->modified_by)->name;
		}

		return $this->_item;
		
	}
}
