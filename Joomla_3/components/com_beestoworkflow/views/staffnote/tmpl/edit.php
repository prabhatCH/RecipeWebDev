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

$return		= base64_encode(JRoute::_('index.php?option=com_beestoworkflow&view=staffnote&layout=edit&id=' .(int) $this->item->id,false));	
$user		= JFactory::getUser();

?>
	
<div id="beestoworfklow">

	<?php echo $this->menu;?>

	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffnote&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
		
		<div class="form-actions">
			<?php if (!$this->item->id) {?>
			<input type="button" class="btn btn-primary" onclick="javascript:Joomla.submitbutton('staffnote.apply');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_SAVE_NOTE');?>" />
			<?php }?>
			<input type="button" class="btn btn-danger" onclick="javascript:Joomla.submitbutton('staffnote.cancel');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_CANCEL');?>" />
		</div>

		<legend><?php if ( $this->item->id) { echo JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_VIEW_NOTE'); } else { echo JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_ADD_NOTE'); } ?></legend>


		<?php if ( $this->item->id) {?>
			<div class="row-fluid">
				<div class="span12">
					<h3><?php echo $this->escape($this->item->title) ;?></h3>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12 well well-small">
					<?php echo $this->item->comment;?>
				</div>
			</div>
			
		<?php } else {?>
			
			<div class="row-fluid">
				<div class="span12">
				<?php $fields = array('title', 'comment', 'category' ) ; ?>
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
		<?php } ?>	

		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>	
