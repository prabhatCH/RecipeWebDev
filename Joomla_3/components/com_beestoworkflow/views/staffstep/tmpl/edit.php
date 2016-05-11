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

?>
<div id="beestoworfklow">
	
	<?php echo $this->menu;?>
	
	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffstep&layout=edit&id='.(int) $this->item->id,false); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
	
	<div class="form-actions">
		<?php if (($this->owner == JFactory::getUser()->get('id')) || empty($this->item->id)) {?>
		<input type="button" class="btn btn-primary" onclick="javascript:Joomla.submitbutton('staffstep.apply');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_SAVE');?>" />
		<?php } ?>
		<input type="button" class="btn btn-danger" onclick="javascript:Joomla.submitbutton('staffstep.cancel');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_CANCEL');?>" />
	</div>
		

	<legend><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_ADD_WORKFLOW_STEP');?></legend>

	<div class="row-fluid">
		<div class="span12">
			<?php $fields = array( 'title', 'priority', 'due', 'description', 'assigned_to' ) ; ?>
			<?php foreach ( $fields as $field ) {?> 
			<div class="control-group">
				<label class="control-label"> <?php echo $this->form->getLabel( $field ); ?> </label>
				<div class="controls">
					<?php echo $this->form->getInput( $field ); ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>

	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id;?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
