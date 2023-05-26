<?php
namespace Zianstages\Component\Stages\Api\View\Stagiaires;
use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

class JsonApiView extends BaseApiView
{
    protected $fieldsToRenderItem = [
        'id',
        
    ];

    protected $fieldsToRenderList = [
        'id',
    ];

    public function displayList(array $items = null)
    {
        // echo '<pre>';
        // print_r($items);
        // echo '</pre>';
    }
}