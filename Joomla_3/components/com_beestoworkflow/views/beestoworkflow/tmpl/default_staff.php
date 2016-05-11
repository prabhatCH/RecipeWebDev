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
$return = base64_encode(JRoute::_('index.php?option=com_beestoworkflow',false));
$priority = BeestoWorkflowHelper::getPriority();
?>

<div id="beestoworfklow">
	
	<?php echo $this->menu;?>

	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow', false); ?>" method="post" name="adminForm" id="adminForm">

		<div class="row-fluid">
			<div class="span12 page-header">
				<h2><span class="badge badge-info"><?php echo BeestoWorkflowHelper::getTime ('Y, M d') ;?></span> <?php echo JText::_('COM_BEESTOWORKFLOW_MENU_SUMMARY');?></h2>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span12 page-header">
				<h3><i class="icon icon-briefcase"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_ALL_PROJECTS');?></h3>
			</div>
		</div>
		
		<div class="form-actions">
			<a class="btn btn-default" href="javascript:Joomla.submitbutton('staffproject.add');document.adminForm.task.value=null;">
				<i class="icon icon-plus"></i>
				<?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_NEW_PROJECT');?>
			</a>&nbsp;&nbsp;&nbsp;  
			<?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_VIEW');?>: 
			<a href="javascript:Joomla.submitbutton('staffprojects.getactive');document.adminForm.task.value=null;"><?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_ACTIVE_PROJECTS');?></a>
			<?php if(BeestoWorkflowHelper::canDo('manageProjectRequests')) {?>
				| <a href="javascript:Joomla.submitbutton('staffprojects.getpending');document.adminForm.task.value=null;"><?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_PROJECTS_REQUESTS');?></a> (<span class="level4"><?php echo $this->requests;?></span>)
			<?php } ?>
		</div>

		<div class="row-fluid">
			<div class="span12 page-header">
				<h3><i class="icon icon-list"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_ALL_TASKS');?></h3>
			</div>
		</div>
		
		<div class="form-actions">
				<a class="btn btn-default" href="javascript:Joomla.submitbutton('stafftask.add');document.adminForm.task.value=null;">
					<i class="icon icon-plus"></i>
					<?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_NEW_TASK');?>
				</a>&nbsp;&nbsp;&nbsp;  
					<?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_VIEW');?>: 
				<a href="javascript:Joomla.submitbutton('stafftasks.getactive');document.adminForm.task.value=null;"><?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_ACTIVE_TASKS');?></a>
		</div>
	
		<h3 class="upcoming">
			<i class="icon icon-calendar"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_UPCOMING_ITEMS');?>
			(<?php echo $this->interval->start;?> - <?php echo $this->interval->end;?>)
		</h3>
		
		<table class="table">
			<thead>
				<tr>
					<th></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_TASK_NAME');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_TASK_PROJECT');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_TASK_STATUS');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_TASK_PRIORITY');?></th>
				</tr>
			</thead>
			
			<tbody>
				<?php if($this->days) {
					$j = 0;
					foreach ($this->days as $day) {						
						 if(isset($this->upcoming[$day])) { ?>
						 
							<tr>
								<td colspan="5"><?php echo $day;?></td>
							</tr>
							
							<?php if (isset($this->upcoming[$day]['start'])) { foreach ($this->upcoming[$day]['start'] as $i=>$up) { ?>
							<tr>
								<td>
									<input id="cb<?php echo $j;?>" type="checkbox" title="" onclick="Joomla.isChecked(this.checked);" value="<?php echo $up->id;?>" name="cid[]">
								</td>
								<td><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $j;?>','stafftask.edit')"><?php echo $this->escape($up->name);?></a></td>
								<td><?php echo $up->project ? $this->escape($up->project) : JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_GENERAL');?></td>
								<td><?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_START_TASK');?></td>
								<td><?php echo $priority[$up->priority];?></td>
							</tr>
							<?php $j++; } } ?>
							
							<?php if (isset($this->upcoming[$day]['due'])) { foreach ($this->upcoming[$day]['due'] as $i=>$up) { ?>
							<tr>
								<td>
									<input id="cb<?php echo $j;?>" type="checkbox" title="" onclick="Joomla.isChecked(this.checked);" value="<?php echo $up->id;?>" name="cid[]">
								</td>
								<td><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $j;?>','stafftask.edit')"><?php echo $this->escape($up->name);?></a></td>
								<td><?php echo $up->project ? $this->escape($up->project) : JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_GENERAL');?></td>
								<td><?php echo JText::_('COM_BEESTOWORKFLOW_SUMMARY_DUE_TASK');?></td>
								<td><?php echo $priority[$up->priority];?></td>
							</tr>
							<?php $j++;	} } ?>
							
						<?php  $j++; } ?> 
				<?php }  } ?>
			</tbody>
		</table>
		<!-- /upcoming events -->
		
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
