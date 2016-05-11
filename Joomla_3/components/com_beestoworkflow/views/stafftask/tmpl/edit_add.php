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

$return	= base64_encode(JRoute::_('index.php?option=com_beestoworkflow&view=stafftask&layout=edit&id=' .(int) $this->item->id,false));	
$isEditing = JRequest::getVar('isowner','','','int') == 1 ? true : false;
?>

<div id="beestoworfklow">
	
	<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=stafftask&layout=edit&id='.(int) $this->item->id,false); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
		
		<?php echo $this->menu;?>

		<div class="form-actions">
			<input type="button" class="btn btn-primary" onclick="javascript:Joomla.submitbutton('stafftask.apply');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_SAVE');?>" />
			<input type="button" class="btn btn-danger" onclick="javascript:Joomla.submitbutton('stafftask.cancel');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_CANCEL');?>" />
		</div>

		<legend><i class="icon-list"></i>&nbsp;&nbsp;&nbsp;<?php if($this->item->id) { echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_EDIT');} else {echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_ADD');} ?></legend>

		<div class="row-fluid">
			<div class="span12">
				<?php $fields = array( 'project_id', 'name', 'description', 'due', 'assigned_to', 'priority' ) ; ?>
				<?php foreach ( $fields as $field ) {?> 
				<div class="control-group">
					<label class="control-label"> <?php echo $this->form->getLabel( $field ); ?> </label>
					<div class="controls">
						<?php echo $this->form->getInput( $field ); ?>
					</div>
				</div>
				<?php } ?>
				
				<?php if (!$isEditing && $this->params->get('allow_attachments') == 1) {?>
				<div class="control-group">
					<label class="control-label"><?php echo JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_ATTACHMENT');?></label>
					<div class="controls">
						<input id="jform_attachment" type="file" name="attachment" />
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
			
			
		<input type="hidden" name="return-url" value="<?php echo $return; ?>" />
		<input type="hidden" name="task_id" value="<?php echo $this->item->id;?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
