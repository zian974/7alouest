<?php
namespace Zianstages\Component\Stages\Api\View\Agenda;

use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\Event\OnGetApiFields;
use Tobscure\JsonApi\Collection;
use Joomla\CMS\Uri\Uri;

use Zianstages\Component\Stages\Administrator\Helper\SlotsHelper;
use Zianstages\Component\Stages\Administrator\Helper\AgendaWeeks;

class JsonApiView extends BaseApiView
{
    protected $fieldsToRenderItem = [
        'id',
        'slot_date' ,
		'slot_periode',
		'slot_type',
		'slot_place',
		'slot_public',
		'slot_message',
    ];

    protected $fieldsToRenderList = [
        'dayIndex',
        'dayTitle' ,
        'daySubTitle',
        'dayActive',
        'timeSlots',
		'year',
		'week',
		'data',
        'ct_restant'
    ];

    
    public function displayList(array $items = null)
    {
        $app = Factory::getApplication('com_stages');
        $params = \Joomla\CMS\Component\ComponentHelper::getParams( 'com_stages' );
        
        /** @var \Joomla\CMS\MVC\Model\ListModel $model */
		$model = $this->getModel();
        
        $filters = $app->input->get('filter');
        $stage_id = $filters['stage_id'];
       
        if (empty($stage_id) ) 
        {
            throw new Exception('Aucun identifiant de stage');
        }
        
        
        $model->setState("filter.stage_id", $stage_id);
        $model->setState("filter.countMax", $params->get('max_stagiaire'));
        $model->setState('list.limit', 0);

        if ( $items === null ) 
        {
            $items = $model->getItems();
        }
        
        if (\count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		if ($this->type === null)
		{
			throw new \RuntimeException('Content type missing');
		}

        $weeks = new AgendaWeeks($model->getItems());
		
		$items = $weeks->weeks;
		$weeksSelect = $weeks->weeksList;
		
		$this->document->addMeta('weeksSelect', $weeksSelect);
		
        
		$eventData = ['type' => OnGetApiFields::LIST, 'fields' => $this->fieldsToRenderList, 'context' => $this->type];
		$event     = new OnGetApiFields('onApiGetFields', $eventData);

		/** @var OnGetApiFields $eventResult */
		$eventResult = Factory::getApplication()->getDispatcher()->dispatch('onApiGetFields', $event);
        
		$collection = (new Collection($items, $this->serializer))
			->fields([$this->type => $eventResult->getAllPropertiesToRender()]);
        
		if (!empty($this->relationship))
		{
			$collection->with($this->relationship);
		}
		
		// Set the data into the document and render it
		$this->document->setData($collection);
        
		return $this->document->render();       
    }
    
    
    protected function prepareItem($item): object {
        return $item;
    }
}
