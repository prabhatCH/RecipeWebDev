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

$state		= array('active'=>JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_ACTIVE'),'completed'=>JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_COMPLETED'));
$type		= array('manager'=>JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_CREATOR'),'assignee'=>JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_ASSIGNEE'));
$priority	= BeestoWorkflowHelper::getPriority ();
?>




<div id="beestoworfklow">
	
	<?php echo $this->menu;?>
	
		<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=clienttasks', false); ?>" method="post" name="adminForm" id="adminForm">


		<legend><?php echo JText::_('COM_BEESTOWORKFLOW_MENU_TASKS');?></legend>
		
		<div class="row-fluid">
			<div class="span12 page-header">
				<div class="btn-group">
					<input type="text" class="input-medium pull-left" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" />
					<button type="submit" class="btn btn-default"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					<button type="button" class="btn btn-default" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
				</div>
			</div>	
		</div>
	

		<table class="table table-hover">
			<thead>
				<tr>
				
					<th>
						<input type="checkbox" name="toggle" value="" onclick="checkAll(this)" />
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_TITLE');?>	
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_DUE');?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_COMPLETED'); ?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_PRIORITY');?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_PROJECT'); ?>
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
						<?php if(BeestoWorkflowHelper::getStage($item->due) == 'overdue' && $item->completed != 'completed') { ?>
							<i class="icon icon-bell"></i>
						<?php } elseif ( $item->completed == 'completed' ) { ?>
							<i class="icon icon-ok"></i>
						<?php }	?>
						<a href="index.php?option=com_beestoworkflow&task=clienttask.edit&id=<?php echo $item->id;?>"><?php echo $this->escape($item->name);?></a>
					</td>
					
					<td>
						<?php echo $this->escape($item->due);?>
					</td>
					
					<td>
						<?php echo $item->completed == 'completed' ? JText::_('JYES') : JText::_('JNO');?>
					</td>
					
					<td>
						<span class="level<?php echo $item->priority;?>"><?php echo $priority[$item->priority];?></span>
					</td>
					
					<td>
						<?php echo $item->project ? $this->escape($item->project) : JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_GENERAL');?>
					</td>
					
					<td>
						<?php echo $item->id;?>
					</td>
				</tr>

			<?php } ?>
			</tbody>
						
			<tfoot>
				<tr>
					<td colspan="8">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>


		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
