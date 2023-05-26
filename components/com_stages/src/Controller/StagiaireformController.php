<?php
/**
 * @version    CVS: 0.0.5
 * @package    com_stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

namespace Zianstages\Component\Stages\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Utilities\ArrayHelper;

use Zianstages\Component\Stages\Site\Helper\StagesStagiairesHelper;

/**
 * Stagiaire class.
 *
 * @since  0.0.5
 */
class StagiaireformController extends FormController
{

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return  void
	 *
	 * @since   0.0.5
	 *
	 * @throws  Exception
	 */
	public function edit($key = NULL, $urlVar = NULL)
	{
		$app = Factory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_stages.stagiaire.edit.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_stages.stagiaire.edit.id', $editId);

		// Get the model.
		$model = $this->getModel('Stagiaireform', 'Site');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(Route::_('index.php?option=com_stages&view=stagiaireform&layout=edit', false));
	}

	/**
	 * Method to save data.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   0.0.5
	 */
	public function save($key = NULL, $urlVar = NULL)
	{
		// Check for request forgeries.
		$this->checkToken();

		// Initialise variables.
		$app   = Factory::getApplication();
		$model = $this->getModel('Stagiaireform', 'Site');

		// Get the user data.
		$data = Factory::getApplication()->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm( $data, false );

		if (!$form)
		{
			throw new \Exception($model->getError(), 500);
		}
		

		// Validate the posted data.
		$data = $model->validate($form, $data);
		
		// Check for errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof \Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$input = $app->input;
			$jform = $input->get('jform', array(), 'ARRAY');

			// Save the data in the session.
			$app->setUserState('com_stages.edit.stagiaire.data', $jform);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_stages.stagiaire.edit.id');
			$this->setRedirect(
                Route::_('index.php?option=com_stages&view=stagiaireform&layout=edit&slot_id='. $jform['slot_id'] .'&id=' . $id, false));

			$this->redirect();
		}

		/**
		 * @todo go to table
		 */
		$data = StagesStagiairesHelper::transformData($data);
		
		// Attempt to save the data.
		$return = $model->save($data);
		
		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_stages.edit.stagiaire.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_stages.stagiaire.edit.id');
			$this->setMessage(Text::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(
                Route::_('index.php?option=com_stages&view=stagiaireform&layout=edit&slot_id='. $jform['slot_id'] .'&id=' . $id, false));
		}

		// Check in the profile.
		if ($return)
		{
			// Redirect to the list screen.
			$this->setMessage(Text::_('COM_STAGES_STAGIAIRES_ITEM_SAVED_SUCCESSFULLY'));
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_stages.stagiaire.edit.id', null);

		$menu = Factory::getApplication()->getMenu();
		$item = $menu->getActive();
		
		$url  = 'index.php?option=com_stages&view=agenda&id='.$data['stage_id'];

		$this->setRedirect(Route::_($url, false));

        
        $this->sendEmailConfirmation( $data );
        
		// Flush the data from the session.
		$app->setUserState('com_stages.edit.stagiaire.data', null);
	}

	/**
	 * Method to abort current operation
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function cancel($key = NULL)
	{
		$app = Factory::getApplication();

		// Get the current edit id.
		$editId = (int) $app->getUserState('com_stages.stagiaire.edit.id');

		// Get the model.
		$model = $this->getModel('Stagiaireform', 'Site');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = Factory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_stages&view=stagiaires' : $item->link);
		$this->setRedirect(Route::_($url, false));
	}

	/**
	 * Method to remove data
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 *
	 * @since   0.0.5
	 */
	public function remove()
	{
		$app   = Factory::getApplication();
		$model = $this->getModel('Stagiaireform', 'Site');
		$pk    = $app->input->getInt('id');

		// Attempt to save the data
		try
		{
			// Check in before delete
			$return = $model->checkin($return);
			// Clear id from the session.
			$app->setUserState('com_stages.stagiaire.edit.id', null);

			$menu = $app->getMenu();
			$item = $menu->getActive();
			$url = (empty($item->link) ? 'index.php?option=com_stages&view=stagiaires' : $item->link);

			if($return)
			{
				$model->delete($pk);
				$this->setMessage(Text::_('COM_STAGES_STAGIAIRES_ITEM_DELETED_SUCCESSFULLY'));
			}
			else
			{
				$this->setMessage(Text::_('COM_STAGES_STAGIAIRES_ITEM_DELETED_UNSUCCESSFULLY'), 'warning');
			}
			

			$this->setRedirect(Route::_($url, false));
			// Flush the data from the session.
			$app->setUserState('com_stages.edit.stagiaire.data', null);
		}
		catch (\Exception $e)
		{
			$errorType = ($e->getCode() == '404') ? 'error' : 'warning';
			$this->setMessage($e->getMessage(), $errorType);
			$this->setRedirect('index.php?option=com_stages&view=stagiaires');
		}
	}
    
    
    
    private function sendEmailConfirmation( $data ) 
    {     
        $db    = Factory::getDbo();
            
        $mailerTemplate = \Joomla\CMS\Mail\MailTemplate::getTemplate('com_stages.notification.inscription', '');
           
        $dt = new \Joomla\CMS\Date\Date( $data['date'] );

        $horaire = \Zianstages\Component\Stages\Administrator\Helper\AgendaSlot::translateSchedule($data['horaire']);
            
        $body = 'Bonjour, 
            <p>Merci, nous avons bien enregistré votre enfant au stage vacance.</p>
            <p style="text-align: left;">Merci de prévoir:</p>
            <ul>
                <li style="text-align: left;">une casquette</li>
                <li style="text-align: left;">une paire de basket</li>
                <li style="text-align: left;">de l\'eau en abondance</li>
                <li style="text-align: left;">de la crême solaire</li>
                <li style="text-align: left;">de l\'anti-moustique</li>
            </ul>
            <p><strong>Le règlement s\'effectuera le jour du stage.</strong></p>
            
        ';

        if ( !$mailerTemplate ) {
            \Joomla\CMS\Mail\MailTemplate::createTemplate(
                'com_stages.notification.inscription', 
                'Inscription au stage du {DATE} de {HORAIRE}', 
                $body, 
                '', $body);
        }
        else {
            \Joomla\CMS\Mail\MailTemplate::updateTemplate(
                'com_stages.notification.inscription', 
                'Inscription au stage du {DATE} de {HORAIRE}', 
                $body, 
                '', $body);
        }
            
        $mailer = new \Joomla\CMS\Mail\MailTemplate('com_stages.notification.inscription', 'fr');
            
        $templateData = [
            "date"=>$dt->format('l').' '.$dt->format('d').' '.$dt->format('F').' '.$dt->format('Y'),
            "horaire"=>$horaire];
        
        $mailer->addTemplateData($templateData);
        
        $mailer->addRecipient($data['email']);

        $send = $mailer->Send();

        if ( $send !== true ) {
            $app->enqueueMessage( "Erreur: mail non envoyé", "error");  
        } 
    }
}
