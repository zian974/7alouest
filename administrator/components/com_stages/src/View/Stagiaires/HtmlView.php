<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_Stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Administrator\View\Stagiaires;
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use \Joomla\CMS\Form\Form;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use \Joomla\CMS\HTML\Helpers\Sidebar;
use \Joomla\CMS\Toolbar\Toolbar;
use \Joomla\CMS\Toolbar\ToolbarHelper;
use \Joomla\Component\Content\Administrator\Extension\ContentComponent;

use \Zianstages\Component\Stages\Administrator\Helper\StagesStagiairesHelper;

/**
 * View class for a list of Stagiaires.
 *
 * @since  0.1.0
 */
class HtmlView extends BaseHtmlView
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app = Factory::getApplication();
        
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
        $this->total = $this->get('Total');
                        
        // Get stage data
        $model = Factory::getApplication()->bootComponent('com_stages')
				->getMVCFactory()->createModel('Stage', 'Administrator', ['ignore_request' => true]);
        $this->stage = $model->getItem( $this->state->get('filter.stage_id'));
        $this->filterForm->setField(new \SimpleXMLElement('<field name="stage_id" type="hidden" />'), 'filter');
        $this->filterForm->setValue('stage_id', 'filter', $this->state->get('filter.stage_id'));

        // Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors));
		}
		
		$this->addToolbar();

		$this->sidebar = Sidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   0.1.0
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = StagesStagiairesHelper::getActions();

		ToolbarHelper::title(Text::_('COM_STAGES_TITLE_STAGIAIRES'), "generic");

		$toolbar = Toolbar::getInstance('toolbar');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/src/View/Stagiaires';

		$toolbar->linkButton('close', 'JTOOLBAR_CLOSE')
			->url('index.php?option=com_stages&view=stages')
			->buttonClass('btn btn-danger')
			->icon('icon-cancel');

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				$toolbar->linkButton('nouveau', 'JTOOLBAR_NEW')
					->url('index.php?option=com_stages&view=stagiaire&layout=edit&stage_id='.$this->state->get('filter.stage_id') )
					->buttonClass('btn btn-success')
					->icon('fas fa-plus');
			}
		}

		if ($canDo->get('core.edit.state')  || count($this->transitions))
		{
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('fas fa-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();

			if (isset($this->items[0]->state))
			{
				$childBar->publish('stagiaires.publish')->listCheck(true);
				$childBar->unpublish('stagiaires.unpublish')->listCheck(true);
				$childBar->archive('stagiaires.archive')->listCheck(true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				$toolbar->delete('stagiaires.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
			}

			$childBar->standardButton('duplicate')
				->text('JTOOLBAR_DUPLICATE')
				->icon('fas fa-copy')
				->task('stagiaires.duplicate')
				->listCheck(true);

			if (isset($this->items[0]->checked_out))
			{
				$childBar->checkin('stagiaires.checkin')->listCheck(true);
			}

			if (isset($this->items[0]->state))
			{
				$childBar->trash('stagiaires.trash')->listCheck(true);
			}
		}

		

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{

			if ($this->state->get('filter.state') == ContentComponent::CONDITION_TRASHED && $canDo->get('core.delete'))
			{
				$toolbar->delete('stagiaires.delete')
					->text('JTOOLBAR_EMPTY_TRASH')
					->message('JGLOBAL_CONFIRM_DELETE')
					->listCheck(true);
			}
		}

		if ($canDo->get('core.admin'))
		{
			$toolbar->preferences('com_stages');
		}

		$toolbar->linkButton('stage', 'COM_STAGES_TITLE_STAGE')
			->url('index.php?option=com_stages&task=stage.edit&id='.$this->state->get('filter.stage_id') )
			->buttonClass('btn btn-danger')
			->icon('fas fa-edit');

		$toolbar->linkButton('agenda', 'COM_STAGES_TITLE_AGENDA')
			->url('index.php?option=com_stages&view=slots&filter[stage_id]='.$this->state->get('filter.stage_id') )
			->buttonClass('btn btn-danger')
			->icon('fas fa-calendar-day');

		// Set sidebar action
		Sidebar::setAction('index.php?option=com_stages&view=stagiaires');
	}
	
	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => Text::_('JGRID_HEADING_ID'),
			'a.`state`' => Text::_('JSTATUS'),
			'a.`ordering`' => Text::_('JGRID_HEADING_ORDERING'),
			'a.`date`' => Text::_('COM_STAGES_STAGIAIRES_DATE'),
			'a.`horaire`' => Text::_('COM_STAGES_STAGIAIRES_HORAIRE'),
			'a.`nom`' => Text::_('COM_STAGES_STAGIAIRES_NOM'),
			'a.`prenom`' => Text::_('COM_STAGES_STAGIAIRES_PRENOM'),
			'a.`licence`' => Text::_('COM_STAGES_STAGIAIRES_LICENCE'),
			'a.`email`' => Text::_('COM_STAGES_STAGIAIRES_EMAIL'),
			'a.`telephone`' => Text::_('COM_STAGES_STAGIAIRES_TELEPHONE'),
			'a.`ddn`' => Text::_('COM_STAGES_STAGIAIRES_DDN'),
			'a.`pointure`' => Text::_('COM_STAGES_STAGIAIRES_POINTURE'),
			'a.`reglement`' => Text::_('COM_STAGES_STAGIAIRES_REGLEMENT'),
			'a.`presence`' => Text::_('COM_STAGES_STAGIAIRES_ABSENCE'),
		);
	}

	/**
	 * Check if state is set
	 *
	 * @param   mixed  $state  State
	 *
	 * @return bool
	 */
	public function getState($state)
	{
		return isset($this->state->{$state}) ? $this->state->{$state} : false;
	}
}
