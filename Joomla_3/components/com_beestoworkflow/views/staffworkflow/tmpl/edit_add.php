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

	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffworkflow&layout=edit&id='.(int) $this->item->id,false); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
		
		<div class="control-group">
			<input type="button" class="btn btn-default btn-primary" onclick="javascript:Joomla.submitbutton('staffworkflow.apply');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_SAVE');?>" />
			<input type="button" class="btn btn-default btn-danger" onclick="javascript:Joomla.submitbutton('staffworkflow.cancel');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_CANCEL');?>" />
		</div>

		<legend><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_ADD_WORKFLOW');?></legend>
		

	
		<?php $fields = array( 'title', 'type' ) ; ?>
		<?php foreach ( $fields as $field ) {?> 
		<div class="control-group">
			<label class="control-label"> <?php echo $this->form->getLabel( $field ); ?> </label>
			<div class="controls">
				<?php echo $this->form->getInput( $field ); ?>
			</div>
		</div>
		<?php } ?>
	
	
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>	
