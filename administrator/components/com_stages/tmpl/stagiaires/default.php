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
use Joomla\CMS\Session\Session;

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

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_stages&task=stagiaires.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}

?>

<div class="d-flex flex-column flex-lg-row justify-content-between mb-2">
    <div>
        <h2 class="display-6"><?php echo $this->stage->label ?></h2>
        <h4>
            <?php echo HTMLHelper::_('date', $this->stage->date_start, Text::_('l d F Y')) ?> 
            au <?php echo HTMLHelper::_('date', $this->stage->date_end, Text::_('l d F Y')) ?></h4>
    </div>
    
    <div class="zn-tiles d-flex flex-row">
        <div class="zntile zntile-single">
            <div>
                <div class="zntile-info">
                    <div class="zntile-icon">
                        <?php echo $this->total ?>
                    </div>
                </div>
                <div class="zntile-name d-flex align-items-center">
                <span class="j-links-link">Sélection</span></div>
            </div>
        </div>
        <div class="zntile zntile-single">

            <div class="success">
                <div class="zntile-info">
                <div class="zntile-icon">
                    <?php echo $this->stage->nb_stagiaires ?>
                </div>
                </div>
                <div class="zntile-name d-flex align-items-center">
                <span class="j-links-link">Stagiaires</span></div>
            </div>
        </div>
        <div class="zntile zntile-single">

            <div class="warning">
                <div class="zntile-info">
                <div class="zntile-icon">
                    <?php echo $this->stage->income ?>
                </div>
                </div>
                <div class="zntile-name d-flex align-items-center">
                <span class="j-links-link">Total &euro;</span></div>
            </div>
        </div>
    </div>

</div>


<form action="<?php echo Route::_('index.php?option=com_stages&view=stagiaires'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<div class="row">
		<div class="col-md-12">
			<div id="j-main-container" class="j-main-container">
			<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

				<div class="clearfix"></div>
				<table class="table table-striped" id="stagiaireList">
					<thead>
					<tr>
						<th width="1%" >
							<input type="checkbox" name="checkall-toggle" value=""
								   title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
						</th>
                        
						<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone d-none d-md-table-cell">
							<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', 
                                            $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
						<?php endif; ?>

						<th class='left'>
                            <span class='fas fa-user-check'></span>
						</th>
                        
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  "<span class='fas fa-euro-sign'></span>", 'a.reglement', 
                                            $listDirn, $listOrder); ?>
						</th>
						
						<th class='left d-none d-md-table-cell'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_STAGES_STAGIAIRES_DATE', 'a.date', $listDirn, $listOrder); ?>
						</th>
						<th class='left d-none d-md-table-cell'>
							<?php echo Text::_('COM_STAGES_STAGIAIRES_HORAIRE'); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_STAGES_STAGIAIRES_NOM', 'a.nom', $listDirn, $listOrder); ?>
						</th>
						<th class='left d-none d-md-table-cell'>
							<?php echo HTMLHelper::_('searchtools.sort',  "<span class='fas fa-id-card'></span>", 'a.licence', $listDirn, $listOrder); ?>
						</th>
						<th class='left d-none d-md-table-cell'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_STAGES_STAGIAIRES_LICENCE_NUM', 'a.licence_num', 
                                            $listDirn, $listOrder); ?>
						</th>
						<th class='left d-md-table-cell'>
							<?php echo Text::_("<span class='fas fa-shoe-prints'></span>"); ?>
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
							<td>
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
                                    <input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" 
                                           class="width-20 text-area-order hidden">
                                <?php endif; ?>
							</td>
							<?php endif; ?>
                            
							<td >
                                <?php echo HTMLHelper::_('listhelper.toggle', $item->presence, 'stagiaires', 'presence', $i); ?>
							</td>
                            
							<td>
								<?php echo HTMLHelper::_('listhelper.toggle', $item->reglement, 'stagiaires', 'reglement', $i); ?>
							</td>
                            
							<td class="d-none d-md-table-cell">
								<?php
									$date = $item->date;
									echo $date > 0 ? HTMLHelper::_('date', $date, Text::_('d/m/Y')) : '-';
								?>
							</td>
							<td class="d-none d-md-table-cell">
								<?php echo $item->horaire; ?>
							</td>
							<td>
								<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
									<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 
                                                'stagiaires.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit) : ?>
									<a href="<?php echo Route::_('index.php?option=com_stages&task=stagiaire.edit&id='.(int) $item->id); ?>">
										<?php echo $this->escape($item->nom.' '.$item->prenom); ?>
									</a>
								<?php else : ?>
									<?php echo $this->escape($item->nom.' '.$item->prenom); ?>
								<?php endif; ?>
							</td>
							<td class="d-none d-md-table-cell">
								<?php echo HTMLHelper::_('listhelper.toggle', $item->licence, 'stagiaires', 'licence', $i); ?>
							</td>
							<td class="d-none d-md-table-cell">
                                <span>
                                    <?php echo $item->licence_num; ?>
                                </span>
                                <?php if (!$item->licence) : ?>
                                <button
                                    class="tbody-icon success" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modal-box" 
                                    data-id="<?php echo $item->id; ?>" 
                                    data-licence-num="<?php echo $item->licence_num; ?>"
                                    onclick="return false;">
                                        <span aria-hidden="true" class="fas fa-pen"></span>
                                </button>
								<?php endif; ?>
							</td>
							<td class="d-md-table-cell">
								<?php echo $item->pointure; ?>
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
		</div>
	</div>
</form>

<?php 
require_once JPATH_COMPONENT . '/layouts/modal_licence.php'; 
?>
