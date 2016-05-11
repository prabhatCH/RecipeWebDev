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

$return		= base64_encode(JRoute::_('index.php?option=com_beestoworkflow&view=staffprofile&layout=edit&id=' .(int) $this->item->id,false));	
$user		= JFactory::getUser();

?>

<div id="beestoworfklow">
	
	<?php echo $this->menu;?>

	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffprofile&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
		
		<div class="form-actions">
			<input type="button" class="btn btn-default" onclick="javascript:Joomla.submitbutton('staffprofile.apply');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_SAVE');?>" />
		</div>
		
		
		<div class="row-fluid">
			<div class="span12 page-header">
				<h1><i class="icon icon-user"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_MENU_PERSONAL');?></h1>
			</div>
		</div>	

		<div class="row-fluid">
			<div class="span12">
				<?php $fields = array( 'department', 'email', 'phone', 'company_name', 'company_address', 'company_city', 'company_zip', 'company_email', 'company_phone', 'company_fax', 'company_website' ) ; ?>
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
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
	
</div>
