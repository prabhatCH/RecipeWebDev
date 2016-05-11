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
$isManager 	= BeestoWorkflowHelper::isManager($this->item->id);
$status		= BeestoWorkflowHelper::getProjectStatuses ();
$priority	= BeestoWorkflowHelper::getPriority ();
$return		= base64_encode('index.php?option=com_beestoworkflow&view=staffproject&layout=edit&id=' .(int) $this->item->id);
?>

<div id="beestoworfklow">

	<?php echo $this->menu;?>
	
	<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffproject&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	
		<div class="form-actions">
			<?php if ($isManager && $this->item->status != 2) {?>
				<input type="button" class="btn btn-primary" onclick="javascript:Joomla.submitbutton('staffproject.complete');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_MARK_COMPLETE');?>" />
			<?php } ?>
			
			<?php if($isManager && $this->item->status != 0) {?>
				<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffproject&layout=edit&isowner=1&id=' . $this->item->id, false);?>">
					<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_EDIT_PROJECT_DETAILS');?>
				</a>
			<?php } ?>
			<?php if(BeestoWorkflowHelper::canDo('manageProjectRequests') && $this->item->status== 0) {?>
				<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffproject&layout=edit&isowner=1&id=' . $this->item->id, false);?>">
					<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_EDIT_PROJECT_DETAILS');?>
				</a>
			<?php } ?>
					
		</div>

		<legend><i class="icon icon-briefcase"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_PROJECT_DETAILS');?></legend>
		
		<h3><?php echo $this->item->name ;?></h3>
	
		<div class="row-fluid">
			<div class="span12">
				<dl class="dl-horizontal">
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_ID');?></dt>
					<dd><?php echo $this->item->id; ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_MANAGER');?></dt>
					<dd><?php echo JFactory::getUser($this->item->manager)->get('name'); ?></dd>			
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_CLIENT');?></dt>
					<dd><?php echo $this->item->client == 0 ? JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_INTERNAL_CLIENT') : JFactory::getUser($this->item->client)->get('name'); ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_CLIENT_VIEW');?></dt>
					<dd><?php echo $this->item->client_view == 0 ? JText::_('JNO') : JText::_('JYES') ; ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_WORKFLOW');?></dt>
					<dd><?php echo $this->form->getInput('project_template'); ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_STATUS');?></dt>
					<dd><?php echo $status[$this->item->status]; ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DATE_START');?></dt>
					<dd><?php echo $this->item->start; ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DATE_DUE');?></dt>
					<dd><?php echo $this->item->due; ?></dd>
				</dl>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span12">
				<h4><i class="icon icon-info"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DESCRIPTION');?></h4>
				<div class="well well-small">	
					<?php echo $this->item->description ? $this->item->description : '<p>&nbsp;</p>';?>
				</div>
			</div>
		</div>

		<h4><i class="icon icon-list"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_TASKS');?></h4>
		
		<div class="form-actions">
			<?php if(BeestoWorkflowHelper::canDo('editActiveProjects') || $isManager) {?>
				<a class="btn btn-default" href="javascript:Joomla.submitbutton('stafftask.add');document.adminForm.task.value=null;"><?php echo JText::_('COM_BEESTOWORKFLOW_ADD');?></a>
			<?php } ?>
		</div>
		
		<table class="table table-hover">
			<thead>
				<tr>
					<th></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_TASK_NAME');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_TASK_START');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_TASK_DUE');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_TASK_PRIORITY');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_STATUS');?></th>
				</tr>
			</thead>
			<tbody>
				<?php if($this->tasks) { foreach ($this->tasks as $i=>$task) {?>
					<tr>
						<td><?php echo JHtml::_('grid.id', $i, $task->id); ?></td>
						<td>
						<?php if(BeestoWorkflowHelper::getStage($task->due) == 'overdue') {
							echo JHTML::_('image', 'components/com_beestoworkflow/assets/images/icon-16-alert.png', JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_OVERDUE'), array('title'=> JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_OVERDUE')) );	}?>
							<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','stafftask.edit')"><?php echo $this->escape($task->name);?></a>
						</td>
						<td><?php echo $this->escape($task->start);?></td>
						<td><?php echo $this->escape($task->due);?></td>
						<td><?php echo $priority[$task->priority];?></td>
						<td> <?php if ($task->completed != 'completed') { 
									if(BeestoWorkflowHelper::getStage($task->due) == 'active') {
										echo JText::_('COM_BEESTOWORKFLOW_ACTIVE');
									} elseif (BeestoWorkflowHelper::getStage($task->due) == 'overdue') {
										echo '<span class="level4">' . JText::_('COM_BEESTOWORKFLOW_OVERDUE') . '</span>';
									}
								} else { 
									echo JText::_('COM_BEESTOWORKFLOW_COMPLETED'); 
								} ?>
						</td>
					</tr>
				<?php } } ?>
			</tbody>
		</table>

		<div class="row-fluid">
			<div class="span12">
				<p>&nbsp;</p>
			</div>
		</div>
		
		<h4><i class="icon icon-comments"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_MESSAGES');?></h4>
			
		<?php if((BeestoWorkflowHelper::canDo('editActiveProjects') || $isManager) && $this->item->status != 2) {?>
		<div class="row-fluid">
			<div class="span12">
				<div class="span6">
					<textarea name="beestoworkflow-comment" class="span12"></textarea>
				</div>
				<div class="span6">
					<p><input type="checkbox" value="1" name="messagealert" />&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_SEND_ALERT_ALSO_PARTICIPANTS');?></p>
					<input type="button" class="button" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ADD_COMMENT');?>" onclick="Joomla.submitbutton('staffproject.addcomment');document.adminForm.task.value=null;" />
				</div>
			</div>
		</div>
		<?php } ?>
	
	
		<div class="row-fluid">
			<div class="span12">
				<p>&nbsp;</p>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span12">
			<?php if($this->comments) { $i=0; foreach ($this->comments as $i=>$comment) {?>
					
				<legend class="small">
					<input type="checkbox" title="" onclick="Joomla.isChecked(this.checked);" value="<?php echo $comment->id;?>" name="mid[]" id="cm<?php echo $i;?>">		
					<a href="<?php echo JRoute::_('index.php?option=com_beestoworkflow&task=staffprofileuser.edit&id='.$comment->id.'&return=' . $return);?>"><?php echo JFactory::getUser($comment->owner)->get('name'); ?></a>
					<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_POSTED_ON') . ' ' . $comment->added;?>
					<?php if ($comment->owner == JFactory::getUser()->get('id') || $isManager) {?>
						<a class="beestosmall margin-15-left" href="javascript:void(0);" onclick="return listItemTask('cm<?php echo $i;?>','staffproject.deletecomment')"><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DELETE_COMMENT');?></a>
					<?php } ?>
				</legend>					

				<div class="well well-small">
					<?php echo $comment->comment; ?>
				</div>
			<?php } } ?>
			</div>
		</div>

		
		<div class="row-fluid">
			<div class="span12">
				<p>&nbsp;</p>
			</div>
		</div>
		
		<h4><i class="icon icon-file"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_FILES');?></h4>
				
		<?php if((BeestoWorkflowHelper::canDo('editActiveProjects') || $isManager) && $this->params->get('allow_attachments') == 1 && $this->item->status != 2) {?>
		<div class="form-actions">	
				<p><input type="checkbox" value="1" name="filealert" />&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_SEND_ALERT_ALSO_PARTICIPANTS');?></p>
				<input type="file" name="fileadd" />
				<input type="button" class="btn btn-default" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ADD_FILE');?>" onclick="Joomla.submitbutton('staffproject.addfile');document.adminForm.task.value=null;" />
		</div>
		<?php } ?>
			

		<table class="table table-hover">
			<thead>
				<tr>
					<th></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_FILENAME');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_FILETYPE');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_FILEADDED');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_FILEOWNER');?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if($this->attachments) { $i=0; foreach ($this->attachments as $i=>$attachment) {?>
					<tr>
						<td>
							<input type="checkbox" title="" onclick="Joomla.isChecked(this.checked);" value="<?php echo $attachment->id;?>" name="fid[]" id="cf<?php echo $i;?>">
						</td>
						<td><?php echo $this->escape($attachment->name);?></td>
						<td><?php echo $this->escape($attachment->filetype);?></td>
						<td><?php echo $this->escape($attachment->added);?></td>
						<td><?php echo JFactory::getUser($attachment->owner)->get('name');?></td>
						<td>
							<?php if(BeestoWorkflowHelper::canDo('viewFiles') || $isManager) {?>
								<a href="javascript:void(0);" onclick="listItemTask('cf<?php echo $i;?>','stafffile.download');uncheckThis('cf<?php echo $i;?>');">
									<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_FILEDOWNLOAD');?>
								</a>
							<?php } ?>
							
							<?php if(BeestoWorkflowHelper::canDo('deleteFiles') || $isManager) {?>
								<a class="margin-15-left" href="javascript:void(0);" onclick="return listItemTask('cf<?php echo $i;?>','stafffile.delete')">
									<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_FILEDELETE');?>
								</a>
							<?php } ?>
						</td>
					</tr>
				<?php } } ?>
			</tbody>
		</table>


		<input type="hidden" name="return-url" value="<?php echo $return ; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="project" value="<?php echo $this->item->id;?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
