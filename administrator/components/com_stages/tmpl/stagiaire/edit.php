<?php
/**
 * @version    CVS: 0.1.0
 * @package    Com_Stages
 * @author     Yann 'Zian' CUIDET <zian.cuidet@gmail.com>
 * @copyright  2022 Yann 'Zian' CUIDET
 * @license    GNU General Public License version 2 ou version ultérieure ; Voir LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;


HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');
?>

<form
	action="<?php echo Route::_('index.php?option=com_stages&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="adminForm" class="form-validate form-horizontal">

	<div class="main-card p-4">

		<div class="row">
			<div class="col-lg-3">
				<fieldset class="form-vertical">
					<?php echo $this->form->renderField('nom'); ?>
					<?php echo $this->form->renderField('prenom'); ?>
					<?php echo $this->form->renderField('licence'); ?>
					<?php echo $this->form->renderField('licence_num'); ?>
					<?php echo $this->form->renderField('presence'); ?>
					<?php echo $this->form->renderField('reglement'); ?>
				</fieldset>
			</div>
			<div class="col-lg-9 form-vertical">
					<?php echo $this->form->renderField('stage_id'); ?>
					<?php //echo $this->form->renderField('slot_id'); ?>
					<?php echo $this->form->renderField('date'); ?>
					<?php echo $this->form->renderField('horaire'); ?>
					<?php echo $this->form->renderField('email'); ?>
					<?php echo $this->form->renderField('telephone'); ?>
					<?php echo $this->form->renderField('ddn'); ?>
					<?php echo $this->form->renderField('shoesize'); ?>
					<?php echo $this->form->renderField('pointure'); ?>
					<?php //echo $this->form->renderField('pointure1'); ?>
			</div>
		</div>
		
		<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
		<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
		<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
		<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
		<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
		<?php echo $this->form->renderField('created_by'); ?>
		<?php echo $this->form->renderField('modified_by'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
<?php
// $wa->addInlineScript(" -->
// );
?>
<script type="text/javascript">
	( (document, Joomla) => {
		document.addEventListener("readystatechange", function () {
			if (document.readyState === 'complete') {
				Joomla.StagiaireEdit = {
					onRange : () => {

					}
				};
				
				function addObserver(element) {
					const observer = new MutationObserver(function( event ) {
						event.forEach(function(mutation) {
							switch(mutation.type) {
								case "attributes":
									switch(mutation.attributeName) {
										case "class":
											let inputs = mutation.target
												.querySelectorAll(
													'input[type=text],input[type=date],input[type=radio],select'
												)
											inputs.forEach( element => {
												if( mutation.target.classList.contains('hidden')) {
													element.removeAttribute('required');
													element.classList.remove('required');
												}
												else {
													element.setAttribute('required', 'required');
													element.classList.add('required');
												}
											});

											break;
										}
									break;
								break;
							}
						});
					});
					observer.observe(element, {attributes: true,attributeFilter:['class'] });
				}
				const elementsToObserve = document.querySelectorAll('[data-showon]');
				elementsToObserve.forEach( (element) => {
					addObserver(element);
						console.log(elementsToObserve)
					
					if( element.classList.contains('hidden')) {
						element.removeAttribute('required');
						element.classList.remove('required');
					}
					else {
						element.setAttribute('required', 'required');
						element.classList.add('required');
					}
				});


				
          	}
        });
	})(document, Joomla);
</script>
