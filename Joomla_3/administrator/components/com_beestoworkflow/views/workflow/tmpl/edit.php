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
		if (task == 'workflow.cancel' || document.formvalidator.isValid(document.id('workflow-form'))) {
			Joomla.submitform(task, document.getElementById('workflow-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=workflows&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="workflow-form" class="form-horizontal form-validate">
	
	<div class="row-fluid">
		<div class="span12">
			<fieldset>
				<legend><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_WORKFLOW_DETAILS') ; ?></legend>
					
				<?php $fields = array('id', 'title', 'type') ; ?>
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
	
	<div class="clr"></div>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
