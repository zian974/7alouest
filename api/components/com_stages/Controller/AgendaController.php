<?php
namespace Zianstages\Component\Stages\Api\Controller;

use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

class AgendaController extends ApiController 
{
    protected $contentType = 'agendas';

    protected $default_view = 'agenda';
    
    public function displayList(array $items = null)
    {
        $viewType   = $this->app->getDocument()->getType();
		$viewName   = $this->input->get('view', $this->default_view);
		$viewLayout = $this->input->get('layout', 'default', 'string');

		try
		{
			/** @var JsonApiView $view */
			$view = $this->getView(
				$viewName,
				$viewType,
				'',
				['base_path' => $this->basePath, 'layout' => $viewLayout, 'contentType' => $this->contentType]
			);
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException($e->getMessage());
		}

        $view->set( 'stage_id', $this->input->getInt('stage_id') );
        
        return parent::displayList();
    }
}