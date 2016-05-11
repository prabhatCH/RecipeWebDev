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

<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffproject&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
	
	<div class="form-actions">
		<?php if ($this->form->getValue('status')!= 0 || $this->form->getValue('id')== 0) {?>
		<input type="button" class="btn btn-primary" onclick="javascript:Joomla.submitbutton('staffproject.apply');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_SAVE_PROJECT');?>" />
		<?php } elseif ($this->form->getValue('status')== 0) {?>
		<input type="button" class="btn btn-primary" onclick="javascript:Joomla.submitbutton('staffproject.start');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_START_PROJECT');?>" />
		<?php } ?>
		<input type="button" class="btn btn-danger" onclick="javascript:Joomla.submitbutton('staffproject.cancel');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_CANCEL');?>" />
	</div>
	
	<legend><i class="icon-briefcase"></i>&nbsp;&nbsp;&nbsp;<?php if($this->item->id) { echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_EDIT_PROJECT_DETAILS');} else {echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_NEW');} ?></legend>
		
	<div class="row-fluid">
		<div class="span12">
		<?php $fields = array( 'name', 'client', 'client_view', 'project_template', 'status', 'start', 'due', 'description' ) ; ?>
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
	
	<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_beestoworkflow&view=staffproject&layout=edit&id=' .(int) $this->item->id) ; ?>" />
	<input type="hidden" name="project" value="<?php echo $this->item->id;?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

</div>	
	

