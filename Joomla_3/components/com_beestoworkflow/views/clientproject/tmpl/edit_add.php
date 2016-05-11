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

$user 		= JFactory::getUser()->get('id');
if ( $user != $this->item->client && !empty($this->item->id) ) { return ; }
?>


	
	
<div id="beestoworfklow">

	<?php echo $this->menu; ?>
	
	<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=clientproject&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
	
		<div class="form-actions">
			<input type="button" class="btn btn-primary" onclick="javascript:Joomla.submitbutton('clientproject.apply');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECT_SAVE');?>" />
			<input type="button" class="btn btn-danger" onclick="javascript:Joomla.submitbutton('clientproject.cancel');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECT_CANCEL');?>" />
		</div>
		
		<legend><?php if($this->item->id) { echo JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECT_EDIT_DETAILS');} else {echo JText::_('COM_BEESTOWORKFLOW_CLIENT_NEW_PROJECT');} ?></legend>

	
		<div class="row-fluid">
			<div class="span12">
			<?php $fields = array( 'name', 'due', 'description' ) ; ?>
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
		
		<?php if ($this->params->get('allow_attachments') == 1) {?>
			<div class="row-fluid">
				<div class="span12">
				<?php $fields = array( 'name', 'due', 'description' ) ; ?>
				<?php foreach ( $fields as $field ) {?> 
				<div class="control-group">
					<label class="control-label"><?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECT_ATTACHMENT');?></label>
					<div class="controls">
						<input type="file" name="fileadd" />
					</div>
				</div>
				<?php } ?>
				</div>
			</div>
		<?php }?>

		<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_beestoworkflow&view=clientproject&layout=edit&id=' .(int) $this->item->id) ; ?>" />
		<input type="hidden" name="project" value="<?php echo $this->item->id;?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
