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

$priority		= BeestoWorkflowHelper::getPriority ();
$return			= base64_encode(JRoute::_('index.php?option=com_beestoworkflow&view=clienttask&layout=edit&id=' .(int) $this->item->id,false));	
$user			= JFactory::getUser();
$isAssigned 	= isset($this->assignees->id) && in_array($user->get('id'),$this->assignees->id) ? true : false;
$hasCompleted 	= in_array($user->get('id'),$this->completedby);

// we return if it's set not to access project tasks 
if (!$isAssigned && !BeestoWorkflowHelper::canDo('view_alltasks') && $this->item->id )  { return ; }
?>

<div id="beestoworfklow">

	<?php echo $this->menu;?>
	
		<?php if ( $this->item->id ) { ?> 
		<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=clienttask&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	
		<div class="form-actions">
			<?php if ($isAssigned && $this->item->completed != 'completed' && !$hasCompleted) {?>
				<input type="button" class="btn" onclick="javascript:Joomla.submitbutton('clienttask.complete');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_MARK_COMPLETE');?>" />
			<?php } else if ($isAssigned) { ?>
			<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_COMPLETED_BY_YOURSELF');?>
			<?php } ?>
		</div>

		<legend><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_VIEW');?></legend>

	
		<h3><?php echo $this->item->name ;?></h3>
		
		<div class="row-fluid">
			<div class="span12">
				<dl class="dl-horizontal">
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_ID');?></dt>
					<dd><?php echo $this->item->id; ?></dd>
				
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_ASSIGNEE');?></dt>
					<dd><?php echo isset($this->assignees->names) ? implode(',',$this->assignees->names) : JText::_('COM_BEESTOWORKFLOW_ACTIVE'); ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_CREATED_BY');?></dt>
					<dd><?php echo JFactory::getUser($this->item->created_by)->get('name'); ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_PROJECT');?></dt>
					<dd><?php echo $this->project; ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_PRIORITY');?></dt>
					<dd><?php echo isset( $this->item->priority ) ? $priority[$this->item->priority] : null; ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_START');?></dt>
					<dd><?php echo $this->item->start; ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_DUE');?></dt>
					<dd><?php echo $this->item->due; ?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_STATUS');?></dt>
					<dd><?php echo $this->status; ?></dd>		
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
			
		<div class="row-fluid">
			<div class="span12">		
				<p>&nbsp;</p>
			</div>
		</div>
		

		<h4><i class="icon icon-comments"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_MESSAGES');?> </h4>
		<div class="form-actions">
			<?php if ($isAssigned && $this->item->completed != 'completed') {?>
					<a class="btn" href="javascript:WorkflowProjectComment();">+ <?php echo JText::_('COM_BEESTOWORKFLOW_ADD');?></a>
			<?php } ?>
		</div>
			
			
		<?php if ($isAssigned && $this->item->completed != 'completed') {?>
		<div class="row-fluid">
			<div class="span12">
				<div class="span6">
					<textarea name="beestoworkflow-comment" class="span12"></textarea>
				</div>
				<div class="span6">
					<p><input type="checkbox" value="1" name="messagealert" />&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_SEND_ALERT_ALSO_PARTICIPANTS');?></p>
					<input type="button" class="btn" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ADD_COMMENT');?>" onclick="Joomla.submitbutton('clienttask.addcomment');document.adminForm.task.value=null;" />
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
				<legend>
					<input type="checkbox" title="" onclick="Joomla.isChecked(this.checked);" value="<?php echo $comment->id;?>" name="mid[]" id="cm<?php echo $i;?>">
					<?php echo JHTML::_('image', 'administrator/components/com_beestoworkflow/assets/images/icon-16-staff.png','user');?>
					<?php echo JFactory::getUser($comment->owner)->get('name'); ?>
					<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_POSTED_ON') . ' ' . $comment->added;?>
					
					<?php if ($comment->owner == JFactory::getUser()->get('id')) {?>
						<a class="btn btn-default" href="javascript:void(0);" onclick="return listItemTask('cm<?php echo $i;?>','clienttask.deletecomment')"><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DELETE_COMMENT');?></a>
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

		<h4><i class="icon icon-file"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_FILES');?></h4> 
		
		<div class="form-actions">
			<?php if ( $isAssigned && $this->item->completed != 'completed' && $this->params->get('allow_attachments') == 1 ) {?>
				<a class="btn" href="javascript:WorkflowProjectFile ();void(0);">+ <?php echo JText::_('COM_BEESTOWORKFLOW_ADD');?></a>
			<?php } ?>
		</div>
		
			
		<?php if ( $isAssigned && $this->item->completed != 'completed' && $this->params->get('allow_attachments') == 1 )  {?>
		<div class="form-actions">
				<p><input type="checkbox" value="1" name="filealert" />&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_SEND_ALERT_ALSO_PARTICIPANTS');?></p>
				<input type="file" name="fileadd" />
				<input type="button" class="btn" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ADD_FILE');?>" onclick="Joomla.submitbutton('clienttask.addfile');document.adminForm.task.value=null;" />
		</div>
		<?php } ?>
			

		<table class="table table-hover">
			<thead>
				<tr>
					<th></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_FILENAME');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_FILETYPE');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_FILEADDEDON');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_FILEADDEDBY');?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if($this->attachments) { $i=0; foreach ($this->attachments as $i=>$attachment) {?>
					<tr class="row<?php echo $i%2;?>">
						<td>
							<input type="checkbox" title="" onclick="Joomla.isChecked(this.checked);" value="<?php echo $attachment->id;?>" name="fid[]" id="cf<?php echo $i;?>">
						</td>
						<td><?php echo $this->escape($attachment->name);?></td>
						<td><?php echo $this->escape($attachment->filetype);?></td>
						<td><?php echo $this->escape($attachment->added);?></td>
						<td><?php echo JFactory::getUser($attachment->owner)->get('name');?></td>
						<td>
								<a href="javascript:void(0);" onclick="listItemTask('cf<?php echo $i;?>','stafffile.download');uncheckThis('cf<?php echo $i;?>');">
									<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_FILEDOWNLOAD');?>
								</a>
							
							<?php if( $attachment->owner == $user->get('id') ) {?>
								<a class="margin-15-left" href="javascript:void(0);" onclick="listItemTask('cf<?php echo $i;?>','stafffile.delete')">
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
		<input type="hidden" name="task_id" value="<?php echo $this->item->id;?>" />
		<input type="hidden" name="project" value="<?php echo $this->item->project_id;?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
	<?php } ?>
</div>
