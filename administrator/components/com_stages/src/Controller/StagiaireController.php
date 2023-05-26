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

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Zianstages\Component\Stages\Administrator\Helper\SlotsHelper;
use Joomla\CMS\Response\JsonResponse;

/**
 * Stagiaire controller class.
 *
 * @since  0.1.0
 */
class StagiaireController extends FormController
{
	protected $view_list = 'stagiaires';


	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   1.6
	 */
	public function save($key = null, $urlVar = null)
	{

        // Check for request forgeries.
		$this->checkToken();
		$dispatcher = Factory::getApplication()->getDispatcher();
		
		$dispatcher->addListener('onContentNormaliseRequestData', [$this, 'onContentNormaliseRequestData']);
		
		return parent::save();
	}


	public function onContentNormaliseRequestData( $event ) {
        
		$app   = Factory::getApplication();
		$data = $event->getArgument(1);
		
		if ( empty($data->date) || !isset($data->horaire) || empty($data->stage_id) ) {
			return $event;
		}
		$data->slot_id = SlotsHelper::getIdByDateAndPeriode( $data->date, $data->horaire, $data->stage_id )->id;
		
		if ( empty($data->slot_id) ) {
			$context = "$this->option.edit.$this->context";
			$app->setUserState($context . '.data', $validData);
			$app->setUserState('com_stages.edit.stagiaire.data', $data);
			$this->setMessage("Aucun créneau", 'error');
			$this->setRedirect(Route::_('index.php?option=com_stages&view=stagiaire&layout=edit', false));
			$this->redirect();
		}
        
		return $event;
	}
    
    
	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   1.6
	 */
	public function saveX($key = null, $urlVar = null)
	{
		$this->checkToken('json');

		$app   = Factory::getApplication();
        $model = $this->getModel();
        $id  = $this->input->json->get('jform[id]', 0, 'int');
        $licence_num  = $this->input->json->get('jform[licence_num]', 0, 'text');
        
        $valid = true;
        
        if ( is_null($id) )
        {
            $this->setError(Text::_('COM_STAGES_STAGIAIRE_ID_NOT_FOUND'));
            $valid = false;
        }
        
        $table = $model->getTable();
        
        if ($valid && !$table->load($id))
        {
            $app->enqueueMessage( $table->getError(), "error");  
            $valid = false;
        }
        
        try {
            
            if ( $valid ) {
                $db    = Factory::getDbo();
                
                $query = $db
                    ->getQuery(true)
                    ->update( $db->quoteName('#__stages_stagiaires') )
                    ->set($db->quoteName("licence_num") . ' = ' . $db->quote($licence_num))
                    ->where( 'id ='. (int)$id );

                $db->setQuery($query);
                $db->execute();            
            }
            else throw new Exception( "Invalid" );

            echo new JsonResponse(true, 'Enregistrement effectuée');
        }
        catch( \Exception $e ) {
            echo new JsonResponse(false, 'Erreur(s) lors de la sauvegarde', true);
        }
     
	}
}
