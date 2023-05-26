<?php
namespace Zianstages\Component\Stages\Api\Controller;

use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

class StagiairesController extends ApiController 
{
    protected $contentType = 'stagiaires';

    protected $default_view = 'stagiaires';
    
    public function displayList(array $items = null)
    {
        foreach (FieldsHelper::getFields('com_stages.stagiaires') as $field)
        {
            $this->fieldsToRenderList[] = $field->name;
        }

        return parent::displayList();
    }
}