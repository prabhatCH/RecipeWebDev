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
		if (task == 'user.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			Joomla.submitform(task, document.getElementById('user-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=user&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="user-form" class="form-validate form-horizontal">
	
	<div class="row-fluid">
		<div class="span12">
			<div class="span6 pull-left">
				<legend><?php echo JText::_('COM_BEESTOWORKFLOW_USERS_CONTACT_DETAILS') ; ?></legend>
				<?php $fields = array('id', 'name', 'type', 'department', 'email', 'phone' ) ; ?>
				<?php foreach ( $fields as $field ) {?> 
				<div class="control-group">
					<label class="control-label"> <?php echo $this->form->getLabel( $field ); ?> </label>
					<div class="controls">
						<?php echo $this->form->getInput( $field ); ?>
					</div>
				</div>
				<?php } ?>
			</div>
			
			
			<div class="span6">
				<legend><?php echo JText::_('COM_BEESTOWORKFLOW_USERS_COMPANY_DETAILS') ; ?></legend>
				<?php $fields = array('company_name', 'company_address', 'company_city', 'company_zip', 'company_country', 'company_email', 'company_phone', 'company_fax', 'company_website') ; ?>
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
	</div>
		
			
	<div class="row-fluid">
		<div class="span12">	
			<legend><?php echo JText::_('COM_BEESTOWORKFLOW_USERS_PERMISSIONS') ; ?></legend>
			<?php $type = $this->form->getValue('type');
			if ($type) { ?>
				<?php echo $this->form->getInput('paramsclients'); ?>
			<?php } else {?>
				<?php echo $this->form->getInput('paramsstaff'); ?>
			<?php } ?>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
