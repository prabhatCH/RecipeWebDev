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
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'step.cancel' || document.formvalidator.isValid(document.id('step-form'))) {
			Joomla.submitform(task, document.getElementById('step-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=step&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="step-form" class="form-validate">
	<div class="row-fluid">
		<div class="span12">
			<div class="span7 pull-left">
				<legend><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_DETAILS') ; ?></legend>
				<?php $fields = array('id', 'project_workflow', 'title', 'due', 'priority', 'description' ) ; ?>
				<?php foreach ( $fields as $field ) {?> 
				<div class="control-group">
					<label class="control-label"> <?php echo $this->form->getLabel( $field ); ?> </label>
					<div class="controls">
						<?php echo $this->form->getInput( $field ); ?>
					</div>
				</div>
				<?php } ?>
			</div>
			
			<div class="span5">
				<legend><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_ASSIGNEE') ; ?></legend>
				<?php echo $this->form->getInput('assigned_to'); ?>
			</div>
		</div>
	</div>
	
	
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
