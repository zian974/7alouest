<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_Stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;

/**
 * Display Component Controller
 *
 * @since  0.1.0
 */
class DisplayController extends \Joomla\CMS\MVC\Controller\BaseController
{
	/**
	 * The default view.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $default_view = 'agenda';

	/**
	 * Constructor.
	 *
	 * @param  array                $config   An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 * @param  MVCFactoryInterface  $factory  The factory.
	 * @param  CMSApplication       $app      The JApplication for the dispatcher
	 * @param  Input              $input    Input
	 *
	 * @since  0.1.0
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config, $factory, $app, $input);
	}

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   boolean  $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link InputFilter::clean()}.
	 *
	 * @return  \Joomla\CMS\MVC\Controller\BaseController  This object to support chaining.
	 *
	 * @since   0.1.0
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$app = Factory::getApplication();
		$document = $this->app->getDocument();
		$viewType = $document->getType();
		$viewName = $this->input->get('view', $this->default_view);
		$viewLayout = $this->input->get('layout', 'default', 'string');

		$view = $this->getView($viewName, $viewType, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));

		
		if ( $this->input->getCmd('view', 'stages') == "stagiaireform" 
				&& $this->input->get('layout') == "edit" ) {
			
			$slotId = $app->input->getInt('slot_id', 0);
			$view->slot_id = $slotId;

			if ($model = $this->getModel('slot', '', array('base_path' => $this->basePath))) 
			{
					// Push the model into the view (as default)
					$view->setModel($model, false);
			}
		}

		if ( $this->input->getCmd('view', 'stages') == "agenda" ) {
			$app->setUserState('com_stages.edit.stagiaire.data', null);
			if ($model = $this->getModel('stage', '', array('base_path' => $this->basePath)))
			{
				$view->setModel($model, true);
			}
			if ($model = $this->getModel('slot', '', array('base_path' => $this->basePath)))
			{
				// Push the model into the view (as default)
				$view->setModel($model, false);
			}
		}
		else {
			// Get/Create the model
			if ($model = $this->getModel($viewName, '', array('base_path' => $this->basePath)))
			{
				// Push the model into the view (as default)
				$view->setModel($model, true);
			}
		}

		// $slotId     = $app->input->getInt('slot_id', 0);
		
		// if ( !empty($slotId) && 
		// 	 $this->input->getCmd('view', 'stages') == "stagiaireform"  && 
		// 	 ($this->input->get('layout') == "edit" || $this->input->get('layout') == "modal") ) {

		// 	$articleId     = $app->input->get('articleId', null);
			
		// 	if ( $articleId ) {
		// 		$app->setUserState('com_stages.articleId', $articleId);
		// 	}

		// 	$view->slot_id = $slotId;	
			
		// 	if ($model = $this->getModel('slot', '', array('base_path' => $this->basePath)))
		// 	{
		// 		// Push the model into the view (as default)
		// 		$view->setModel($model, false);
		// 	}
		// }

		$view->document = $document;

		// Display the view
		if ($cachable && $viewType !== 'feed' && Factory::getApplication()->get('caching') >= 1)
		{
			$option = $this->input->get('option');

			if (\is_array($urlparams))
			{
				$this->app = Factory::getApplication();

				if (!empty($this->app->registeredurlparams))
				{
					$registeredurlparams = $this->app->registeredurlparams;
				}
				else
				{
					$registeredurlparams = new \stdClass;
				}

				foreach ($urlparams as $key => $value)
				{
					// Add your safe URL parameters with variable type as value {@see InputFilter::clean()}.
					$registeredurlparams->$key = $value;
				}

				$this->app->registeredurlparams = $registeredurlparams;
			}

			try
			{
				/** @var \Joomla\CMS\Cache\Controller\ViewController $cache */
				$cache = Factory::getCache($option, 'view');
				$cache->get($view, 'display');
			}
			catch (CacheExceptionInterface $exception)
			{
				$view->display();
			}
		}
		else
		{
			$view->display();
		}
		return $this;
	}
}
