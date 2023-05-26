<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Zianstages\Component\Stages\Administrator\Rule;

\defined('JPATH_PLATFORM') or die;

use \Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormRule;
use Joomla\Registry\Registry;

/**
 * Description of StagiaireDdnRule
 *
 * @author zian
 */
class StagiaireddnRule extends FormRule {
    public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
        
        if ( empty($value) ) return true;
        
        $db = Factory::getDbo();
		$query = $db->getQuery(true);
        $query
			->select('id, slot_public' )
			->from($db->quoteName('#__stages_slots', 'a'))
			->where( 'id = ' . (int)$input->get('slot_id') );

		$db->setQuery($query);

        $slot = $db->loadObject();
        

        if ( preg_match('#69#', $slot->slot_public) ) {
            
            $dtMin = (new \DateTime())->sub(\DateInterval::createFromDateString('10 years'));
            $dtMax = (new \DateTime())->sub(\DateInterval::createFromDateString('5 years'));
            $dt = (new \DateTime($value));
                       
            if ( $dtMin > new \DateTime($value) || $dtMax < new \DateTime($value) ) {
                return false;
            }
        }
        else if ( preg_match('#711#', $slot->slot_public) ) {
           
            $dtMin = (new \DateTime())->sub(\DateInterval::createFromDateString('12 years'));
            $dtMax = (new \DateTime())->sub(\DateInterval::createFromDateString('6 years'));
            
            if ( $dtMin > new \DateTime($value) || $dtMax < new \DateTime($value) ) {
                return false;
            }
        }
        else if ( preg_match('#1014#', $slot->slot_public) ) {
            $dtMin = (new \DateTime())->sub(\DateInterval::createFromDateString('15 years'));
            $dtMax = (new \DateTime())->sub(\DateInterval::createFromDateString('9 years'));
            
            if ( $dtMin > new \DateTime($value) || $dtMax < new \DateTime($value) ) {
                return false;
            }
        }
        else if ( preg_match('#1217#', $slot->slot_public) ) {
            $dtMin = (new \DateTime())->sub(\DateInterval::createFromDateString('18 years'));
            $dtMax = (new \DateTime())->sub(\DateInterval::createFromDateString('11 years'));
            
            if ( $dtMin > new \DateTime($value) || $dtMax < new \DateTime($value) ) {
                return false;
            }
        }
        else {
            throw new \Exception('Erreur fatale - validation de la date de naissance');
        }

        return true;
    }
}
