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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
$priority = BeestoWorkflowHelper::getPriority();
$class 		= array(0=>'verylow',1=>'low',2=>'medium',3=>'high',4=>'veryhigh');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'project.cancel' || document.formvalidator.isValid(document.id('project-form'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=projects&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">

	<div class="row-fluid">
		<div class="span12">
			<fieldset>
				<legend><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_DETAILS') ; ?></legend>
				<?php $fields = array('id', 'name', 'status', 'start', 'due', 'manager', 'client') ; ?>
				<?php foreach ( $fields as $field ) {?> 
				<div class="control-group">
					<label class="control-label"> <?php echo $this->form->getLabel( $field ); ?> </label>
					<div class="controls">
						<?php echo $this->form->getInput( $field ); ?>
					</div>
				</div>
				<?php } ?>
			</fieldset>
		</div>
	</div>

	

	<legend><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_TASKS') ; ?></legend>
	<table class="table table-hover">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_TASK_NAME');?></th>
				<th><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_TASK_PRIORITY');?></th>
				<th><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_DATE_START');?></th>
				<th><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_DATE_DUE');?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->tasks as $task) { ?>
			<tr>
				<td><a href="index.php?option=com_beestoworkflow&task=task.edit&id=<?php echo $task->id;?>"><?php echo $task->name;?></a></td>
				<td><span class="<?php echo $class[$task->priority];?>"><?php echo $priority[$task->priority];?></span></td>
				<td><?php echo $task->start;?></td>
				<td><?php echo $task->due;?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>

	

	<legend><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_MESSAGES') ; ?></legend>
	<table class="table table-hover">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_MESSAGES_CONTENT');?></th>
				<th><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_MESSAGES_ADDED');?></th>
				<th><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_MESSAGES_OWNER');?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->comments as $i => $comment) { ?>
			<tr>
				<td><?php echo $comment->comment;?></a></td>
				<td><?php echo $comment->added ;?></td>
				<td><?php echo JFactory::getUser($comment->owner)->get('name');?></td>
				<td><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','message.delete')"><?php echo JHtml::image('administrator/components/com_beestoworkflow/assets/images/icon-16-delete.png','') ;?></a></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>


	<legend><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_ATTACHMENTS') ; ?></legend>
	<table class="table table-hover">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_ATTACHMENTS_NAME');?></th>
				<th><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_ATTACHMENTS_TYPE');?></th>
				<th><?php echo JText::_('COM_BEESTOWORKFLOW_PROJECTS_PROJECT_ATTACHMENTS_OWNER');?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->attachments as $i => $attachment) { ?>
			<tr>
				<td><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','files.download')"><?php echo $attachment->name;?></a></td>
				<td><?php echo $attachment->filetype;?></td>
				<td><?php echo JFactory::getUser($attachment->owner)->get('name');?></td>
				<td><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','files.delete')"><?php echo JHtml::image('administrator/components/com_beestoworkflow/assets/images/icon-16-delete.png','') ;?></a></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>

	<div class="row-fluid">
		<div class="span12">
			<p>&nbsp;</p>
		</div>
	</div>
	
	<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_beestoworkflow&view=project&layout=edit&id=' . JRequest::getVar('id','','','int') ) ; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
