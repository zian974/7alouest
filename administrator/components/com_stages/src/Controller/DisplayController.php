<?php

/**
 * @version    CVS: 0.1.0
 * @package    Com_Stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;

/**
 * Stages master display controller.
 *
 * @since  0.1.0
 */
class DisplayController extends BaseController
{
	/**
	 * The default view.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $default_view = 'stages';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link InputFilter::clean()}.
	 *
	 * @return  BaseController|boolean  This object to support chaining.
	 *
	 * @since   0.1.0
	 */
	public function display($cachable = false, $urlparams = array())
	{
		
		$app = Factory::getApplication();
		$filters = $app->input->getInt('filter', 0);
		$stage_id = 0;
	
		if ( $this->input->getCmd('view', 'stages') == "stagiaires" ) {
			$stage_id = $this->_getStageId();
			if ( empty($stage_id) ){
				return $this->redirectNoStageId();
			}
			$app->setUserState('com_stages.agenda.stage_id', $stage_id	);
		}
		
		else if ( $this->input->getCmd('view', 'stages') == "stagiaire" 
				&& $this->input->get('layout') == "edit" ) {

			if( !$this->input->getInt('id', 0) ) {
				$stage_id = $this->_getStageId();
				if ( empty($stage_id) ){
					return $this->redirectNoStageId();
				}
				$app->input->set('stage_id', $stage_id );
			}
		}

		else if ( $this->input->getCmd('view', 'stages') == "slots" ) {
			$stage_id = $this->_getStageId();
			if ( empty($stage_id) ){
				return $this->redirectNoStageId();
			}
			$app->input->set('stage_id', $stage_id );
		} 

		else if ( $this->input->getCmd('view', 'stages') == "slot" 
				&& $this->input->get('layout') == "edit" ) {
			if( !$this->input->getInt('id', 0) ) {

				$stage_id = $this->_getStageId();
				if ( empty($stage_id) ){
					return $this->redirectNoStageId();
				}
				$app->input->set('stage_id', $stage_id );
			}
		}
		return parent::display();
	}


	private function _getStageId() {
		
		$app = Factory::getApplication();
		$stageId = $this->input->getInt('id', 0);
		$filters = $app->input->get('filter', []);

		if ( empty($stage_id) && is_array($filters) && !empty($filters['stage_id']) ) {
			$stage_id = $filters['stage_id'];
		}

		if ( empty($stage_id) ) {
			$stage_id = $app->getUserState('com_stages.agenda.stage_id', 0);
		}

		return $stage_id;
	}

	private function redirectNoStageId() {
		$this->setMessage(Text::_('COM_STAGES_NO_STAGE_ID'));
		$this->setRedirect('index.php?option=com_stages&view=stages');
		return false;
	}
}
