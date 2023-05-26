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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;

/**
 * Stagiaires list controller class.
 *
 * @since  0.1.0
 */
class StagiairesController extends AdminController
{

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
		return parent::display();
	}

	
	/**
	 * Method to clone existing Stagiaires
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function duplicate()
	{
		// Check for request forgeries
		$this->checkToken();

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new \Exception(Text::_('COM_STAGES_STAGIAIRES_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Text::_('COM_STAGES_STAGIAIRES_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_stages&view=stagiaires');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since   0.1.0
	 */
	public function getModel($name = 'Stagiaire', $prefix = 'Administrator', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}

	

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   0.1.0
	 *
	 * @throws  Exception
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = Factory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		Factory::getApplication()->close();
	}

	/**
	 * Method to toggle fields on a list
	 *
	 * @throws Exception
	 */
	public function toggle()
	{
		// Initialise variables
		$app    = Factory::getApplication();
		$ids    = $app->input->get('cid', array(), '', 'array');
		$field  = $app->input->get('field');

		if (empty($ids))
		{
			$app->enqueueMessage('warning', Text::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else
		{
			// Get the model
			$model = $this->getModel('stagiaire');

			foreach ($ids as $pk)
			{
				// Toggle the items
				if (!$model->toggle($pk, $field))
				{
					throw new \Exception($model->getError(), 500);
				}
			}
		}

	$this->setRedirect(Route::_('index.php?option=' . $app->input->get('option') . '&view=' . $app->input->get('view'), false));
	}
}
