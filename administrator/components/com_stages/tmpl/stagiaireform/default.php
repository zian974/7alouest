<?php
/**
 * @version    CVS: 0.0.5
 * @package    com_stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Zianstages\Component\Stages\Site\Helper\StagesStagiairesHelper;
use Zianstages\Component\Stages\Administrator\Helper\AgendaSlotGroup;
use Zianstages\Component\Stages\Administrator\Helper\AgendaSlot;

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

$wa->useStyle('com_stages.scheduler');

HTMLHelper::_('bootstrap.tooltip');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_stages', JPATH_SITE);

$user    = Factory::getUser();
$canEdit = StagesStagiairesHelper::canUserEdit($this->item, $user);
?>

<div class="stagiaire-edit front-end-edit">
	
	<?php if (!empty($this->item->id)): ?>
		<?php throw new \Exception(Text::_('COM_STAGES_STAGIAIRES_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
	<?php else: ?>
		<h3><?php echo Text::_('COM_STAGES_STAGIAIRE_INSCRIPTION'); ?></h3>
	<?php endif; ?>

    <p>
        Si vous rencontrez des problèmes lors de l'inscription de votre enfant ou pour toute autre information, vous pouvez contacter
        le moniteur par mail (<strong> 7aw.moniteur@orange.fr </strong> ) ou par téléphone au <strong> 06 92 56 09 32 </strong>.
    </p>
        
	<form id="form-stagiaire"
		  action="<?php echo Route::_('index.php?option=com_stages&task=stagiaireform.save'); ?>"
		  method="post" class="form-validate form-vertical form-front" enctype="multipart/form-data">
			
		<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />
		<input type="hidden" name="jform[state]" value="<?php echo isset($this->item->state) ? $this->item->state : ''; ?>" />
		<input type="hidden" name="jform[ordering]" value="<?php echo isset($this->item->ordering) ? $this->item->ordering : ''; ?>" />
		<input type="hidden" name="jform[checked_out]" value="<?php echo isset($this->item->checked_out) ? $this->item->checked_out : ''; ?>" />
		<input type="hidden" name="jform[checked_out_time]" value="<?php echo isset($this->item->checked_out_time) ? $this->item->checked_out_time : ''; ?>">

		<?php echo $this->form->renderField('stage_id'); ?>
		<?php echo $this->form->renderField('slot_id'); ?>
		<?php echo $this->form->renderField('date'); ?>
		<?php echo $this->form->renderField('horaire'); ?>
		<?php echo $this->form->renderField('licence'); ?>
		
		<div class="d-flex flex-column flex-md-row flex-grow-0 justify-content-between align-content-center gap-3" >
			<div class="d-flex fxFill znInfo title">
			<?php 
				$dt = new DateTime($this->form->getField('date')->value);
				echo "<h3>". 
                        ucfirst( HTMLHelper::_('date', $this->form->getField('date')->value, Text::_('l') ) ).
						HTMLHelper::_('date', $this->form->getField('date')->value, Text::_('d F') ).
					"</h3>";
			?>
			</div>
			<div class="d-flex fxFill znInfo title"><i class="material-icons">schedule</i><strong>
			<?php echo AgendaSlot::translateSchedule($this->stageSelected->slot_periode)?>
			</strong></div>
		</div>
		
		<div class="d-flex flex-column flex-md-row justify-content-between gap-1 gap-sm-2">
			<div class="d-flex fxFill znInfo climb-type">
                <strong><?php echo strtoupper(AgendaSlot::translateType($this->stageSelected->slot_type)); ?></strong>
			</div>
			<div class="d-flex fxFill znInfo groupe <?php echo AgendaSlotGroup::translateColor($this->stageSelected->slot_public)?>">
				<span><?php echo AgendaSlotGroup::translateLabel($this->stageSelected->slot_public)?> </span>
				<span><?php echo AgendaSlotGroup::translateSubLabel($this->stageSelected->slot_public)?></span>
			</div>
		</div>

		<?php echo $this->form->renderField('nom'); ?>

		<?php echo $this->form->renderField('prenom'); ?>

		<fieldset id="fieldset-metadata" class="options-form">
			<legend><?php echo Text::_('COM_STAGES_STAGIAIRE_LEGEND_CONTACT'); ?></legend>
			<div>
				<?php echo $this->form->renderField('email'); ?>
				<?php echo $this->form->renderField('telephone'); ?>
			</div>
		</fieldset>

		<?php if ( preg_match('/nonlicence/', $this->stageSelected->slot_public) ): ?>
		<fieldset id="fieldset-metadata" class="options-form">
			<legend><?php echo Text::_('COM_STAGES_STAGIAIRE_LEGEND_NON_LICENCE'); ?></legend>
            
			<?php 
				echo $this->form->renderField('ddn', null, null, ['min'=>$this->ddnMin, 'max'=>$this->ddnMax]); 
				echo $this->form->renderField('pointure');
			?>
		</fieldset> 
		<?php endif; ?>

		<div class="control-group">
			<div class="controls">

				<?php if ($this->canSave): ?>
                <button type="submit" class="validate btn btn-primary">
						<i class="material-icons">save</i>
						<?php echo Text::_('JSUBMIT'); ?>
                </button>
				<?php endif; ?>
                
				<a class="btn btn-danger"
					href="<?php echo Route::_('index.php?option=com_stages&view=agenda&id='.$this->stageSelected->stage_id .'=&Itemid='); ?>"
					title="<?php echo Text::_('JCANCEL'); ?>">
					<i class="material-icons">cancel</i>
					<?php echo Text::_('JCANCEL'); ?>
				</a>
			</div>
		</div>

		<input type="hidden" name="option" value="com_stages"/>
		<input type="hidden" name="task" value="stagiaireform.save"/>
        
		<?php echo HTMLHelper::_('form.token'); ?>

	</form>
</div>

<script type="text/javascript">
</script>
