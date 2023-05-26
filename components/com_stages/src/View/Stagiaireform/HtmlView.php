<?php

/**
 * @version    CVS: 0.0.5
 * @package    com_stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Site\View\Stagiaireform;
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

use Zianstages\Component\Stages\Administrator\Helper\SlotsHelper;

/**
 * View class for a list of Stages.
 *
 * @since  0.0.5
 */
class HtmlView extends BaseHtmlView
{
	protected $state;

	protected $item;

	protected $form;

	protected $params;

	protected $canSave;

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
		$app  = Factory::getApplication();
		$user = Factory::getUser();

		$this->state   = $this->get('State');
		$this->params  = $app->getParams('com_stages');
		$this->canSave = $this->get('CanSave');
		$this->item = $this->get('item');
		$this->form		= $this->get('Form');
		// $this->form->reset();

		$this->stageSelected = $this->getModel('slot')->getItem( $this->slot_id );

		$this->form->setValue( 'horaire', null, $this->stageSelected->slot_periode );
		$this->form->setValue( 'stage_id', null, $this->stageSelected->stage_id );
		$this->form->setValue( 'slot_id', null, $this->stageSelected->id );
		$this->form->setValue( 'date', null, $this->stageSelected->slot_date );
        

		if ( preg_match('/nonlicence/', $this->stageSelected->slot_public) ) {
			$this->form->setValue( 'licence', null, 0 );
		}
		else {
			$this->form->setValue( 'licence', null, 1 );
		}
        
        $dt = new \DateTime(); $dt->sub(\DateInterval::createFromDateString('5 years'));
        $this->ddnMax = $dt->format('Y-m-d'); 
        $dt = new \DateTime(); $dt->sub(\DateInterval::createFromDateString('18 years'));
        $this->ddnMin = $dt->format('Y-m-d'); 
        
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors));
		}

		$this->_prepareDocument();

		parent::display($tpl);
		
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = Factory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', Text::_('COM_STAGES_STAGIAIRES_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		
	}
}
