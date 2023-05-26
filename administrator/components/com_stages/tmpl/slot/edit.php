<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;


use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;


HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');
?>

<div>
    <h2 class="display-6"><?php echo $this->stage->label ?></h2>
    <h3>Du <?php echo $this->stage->date_start ?> au <?php echo $this->stage->date_end ?></h3>
</div>

<form
	action="<?php echo Route::_('index.php?option=com_stages&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="slot-form" class="form-validate form-horizontal">

    <h4><?php echo Text::_('COM_STAGES_SLOT_FORM_LEGEND'); ?></h4>
    
	<div class="row adminform ">
        <div class="col-lg-3">
            <?php echo $this->form->renderField('stage_id'); ?>
        </div>
        <div class="col-lg-9">
            <?php echo $this->form->renderField('slot_date', null, null, ['min'=>$this->stage->date_start, 'max'=>$this->stage->date_end]); ?>
            <?php echo $this->form->renderField('slot_periode'); ?>
            <?php echo $this->form->renderField('slot_type'); ?>
            <?php echo $this->form->renderField('slot_place'); ?>
            <?php echo $this->form->renderField('slot_public'); ?>
            <?php echo $this->form->renderField('slot_message'); ?>
            <?php echo $this->form->renderField('color_stage'); ?>
            <?php if ($this->state->params->get('save_history', 1)) : ?>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
                </div>
            <?php endif; ?>
        </div>
	</div>
    
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
    
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
    
	<input type="hidden" name="task" value=""/>
    
	<?php echo HTMLHelper::_('form.token'); ?>

</form>
