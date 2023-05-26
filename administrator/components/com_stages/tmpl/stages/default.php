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
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Session\Session;
use \Joomla\CMS\Date\Date;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/src/Helper/');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');

// Import CSS
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('com_stages.admin')
    ->useScript('com_stages.admin');

$user      = Factory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_stages');
$saveOrder = $listOrder == 'a.ordering';

$canMonitor = $user->authorise('core.edit.moniteur', 'com_stages');

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_stages&task=stages.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}

?>

<form action="<?php echo Route::_('index.php?option=com_stages&view=stages'); ?>" method="post"
	  name="adminForm" id="adminForm" >
<!--	<div class="row">
		<div class="col-md-12">-->
			<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
			<div id="j-main-container" class="j-main-container overflow-auto">

				
				<table class="table table-striped" id="stageList">
					<thead>
					<tr>
						<th width="1%" class='d-none d-md-table-cell'>
							<input type="checkbox" name="checkall-toggle" value=""
								   title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
						</th>
						<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone d-none d-md-table-cell">
							<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
						<?php endif; ?>
						
						<th class='left d-none d-md-table-cell'>
							<?php echo Text::_('JGLOBAL_FIELD_ID_LABEL'); ?>
						</th>
						<th width="5%" class="nowrap center d-none d-md-table-cell">
							<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo Text::_('COM_STAGES_STAGES_LABEL'); ?>
						</th>
						<th class='left d-none d-md-table-cell'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_STAGES_STAGES_DATE_START', 'a.date_start', $listDirn, $listOrder); ?>
						</th>
						<th class='left d-none d-md-table-cell'>
							<?php echo Text::_('COM_STAGES_STAGES_DATE_END'); ?>
						</th>
					</tr>
					</thead>
					<tfoot>
					<tr>
						<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
					</tfoot>
					<tbody <?php if ($saveOrder) :?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" <?php endif; ?>>
					<?php foreach ($this->items as $i => $item) :
						$ordering   = ($listOrder == 'a.ordering');
						$canCreate  = $user->authorise('core.create', 'com_stages');
						$canEdit    = $user->authorise('core.edit', 'com_stages');
						$canCheckin = $user->authorise('core.manage', 'com_stages');
						$canChange  = $user->authorise('core.edit.state', 'com_stages');
						?>
						<tr class="row<?php echo $i % 2; ?>" data-draggable-group='1' data-transition>
							<td class="d-none d-md-table-cell">
								<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
							</td>

							<?php if (isset($this->items[0]->ordering)) : ?>
							<td class="order nowrap center hidden-phone d-none d-md-table-cell">
							<?php
								$iconClass = '';
								if (!$canChange)
								{
									$iconClass = ' inactive';
								}
								elseif (!$saveOrder)
								{
									$iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
								}
							?>
							<span class="sortable-handler<?php echo $iconClass ?>">
								<span class="icon-ellipsis-v" aria-hidden="true"></span>
							</span>
							<?php if ($canChange && $saveOrder) : ?>
								<input type="text" name="order[]" size="5" 
                                       value="<?php echo $item->ordering; ?>" class="width-20 text-area-order hidden">
							<?php endif; ?>
							</td>
							<?php endif; ?>
							
							<td class="d-none d-md-table-cell">
								<?php echo $item->id; ?>
							</td>
							<td class="d-none d-md-table-cell">
								<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'stages.', $canChange, 'cb'); ?>
							</td>
							<td class="">
								<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
									<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'stages.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit || $canMonitor) : ?>
									<a href="<?php echo Route::_('index.php?option=com_stages&view=stagiaires&filter[stage_id]='.(int) $item->id); ?>">
										<?php echo $this->escape($item->label); ?>
									</a>
								<?php else : ?>
									<?php echo $this->escape($item->label); ?>
								<?php endif; ?>
							</td>
							<td class="d-none d-md-table-cell">
								<?php 
								$dt_start = new Date( $item->date_start );
								echo $dt_start->format('l').' '.$dt_start->format('d').' '.$dt_start->format('F').' '.$dt_start->format('Y'); 
								?>
							</td>
							<td class="d-none d-md-table-cell">
							<?php 
								$dt_end = new Date( $item->date_end );
								echo $dt_end->format('l').' '.$dt_end->format('d').' '.$dt_end->format('F').' '.$dt_end->format('Y'); 
								?>
							</td>

						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

				<input type="hidden" name="task" value=""/>
				<input type="hidden" name="boxchecked" value="0"/>
				<input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
				<?php echo HTMLHelper::_('form.token'); ?>
			</div>
<!--		</div>
	</div>-->
</form>