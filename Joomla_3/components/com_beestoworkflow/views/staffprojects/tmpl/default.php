<?php
/*
 * @component BeestoWorkflow
 * @version 1.4 "Erdos"
 * @website : http://www.ionutlupu.me
 * @copyright Ionut Lupu. All rights reserved.
 * @license : http://www.gnu.org/copyleft/gpl.html GNU/GPL , see license.txt
 */
 
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$return 	= base64_encode(JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects',false));
$priority 	= BeestoWorkflowHelper::getPriority();
$state		= BeestoWorkflowHelper::getProjectStatuses();

?>

<div id="beestoworfklow">

	<?php echo $this->menu;?>

	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects', false); ?>" method="post" name="adminForm" id="adminForm">

		<div class="form-actions">
			<button class="btn btn-default" onclick="javascript:Joomla.submitbutton('staffproject.add')">
				<i class="icon icon-plus"></i>
				<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_NEW');?>
			</button>
			<a class="btn btn-default" href="javascript:Joomla.submitbutton('staffprojects.delete');"><i class="icon icon-delete"></i> <?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DELETE_SELECTED');?></a>
			<div class="pull-right">
				<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_EXPORT_AS');?> <a href="javascript:Joomla.submitbutton('staffprojects.exportXLS');document.adminForm.task.value=null;"><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_XLS');?></a>&nbsp;|&nbsp;<a href="javascript:Joomla.submitbutton('staffprojects.exportPDF');document.adminForm.task.value=null;" onclick=""><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_PDF');?></a>
			</div>
		</div>
			
		<div class="row-fluid">
			<div class="span12 page-header">
				<h1><i class="icon icon-briefcase"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_MENU_PROJECTS');?></h1>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span12 page-header">
				<div class="btn-group">
					<input type="text" class="input-medium pull-left" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" />
					<button type="submit" class="btn btn-group"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					<button type="button" class="btn btn-group" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
				</div>
			
				
				<div class="btn-group">
					<select name="filter_status" class="input-medium" onchange="this.form.submit()">
						<option value="3">- <?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_SELECT_STATE');?> -</option>
						<?php echo JHtml::_('select.options', $state  , 'value', 'text', $this->state->get('filter.status'));?>
					</select>
				</div>
			</div>
		</div>	

		<table class="table table-hover">
			<thead>
				<tr>
				
					<th>
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_NAME');?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DATE_START'); ?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DATE_DUE'); ?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_STATUS'); ?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_MANAGER'); ?>
					</th>	
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_ID');?>
					</th>
					
				</tr>
			</thead>

			<tbody>
			<?php foreach ($this->items as $i => $item) {
			?>
			<tr class="row<?php echo $i % 2; ?>">

				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				
				<td>
					<a href="index.php?option=com_beestoworkflow&task=staffproject.edit&id=<?php echo $item->id;?>"><?php echo $this->escape($item->name);?></a>
				</td>
				
				<td>
					<?php echo $this->escape($item->start);?>
				</td>
				
				<td>
					<?php echo $item->due;?>
				</td>
				
				<td>
					<span class="status<?php echo $item->status;?>"><?php echo $state[$item->status];?></span>
				</td>
				
				<td>
					<?php echo $item->manager ? $this->escape($item->manager) : JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_CLIENT_REQUEST');?>
				</td>
				
				<td>
					<?php echo $item->id; ?>
				</td>
				
			</tr>

			<?php } ?>
			</tbody>
						
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>

		
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<input type="hidden" name="return" value="<?php echo $return;?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
