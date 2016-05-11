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

	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffprofileuser&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
	
		<div class="form-actions">
			<input type="button" class="btn btn-default" onclick="javascript:Joomla.submitbutton('staffprofileuser.cancel');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_CLOSE');?>" />
		</div>
		
		<legend><?php echo JText::_('COM_BEESTOWORKFLOW_MENU_PERSONAL');?></legend>
		
		<div class="row-fluid">
			<div class="span12">
				<h3><?php echo JFactory::getUser($this->item->id)->get('name'); ?></h3>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span12">
				<dl class="dl-horizontal">
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_DEPARTMENT');?></dt>
					<dd><?php echo $this->item->department;?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_EMAIL');?></dt>
					<dd><a href="mailto:<?php echo $this->item->email;?>"><?php echo $this->item->email;?></a></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_PHONE');?></dt>
					<dd><?php echo $this->item->phone;?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_COMPANY');?></dt>
					<dd><?php echo $this->item->company_name;?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_COMPANY_ADDRESS');?></dt>
					<dd><?php echo $this->item->company_address;?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_COMPANY_CITY');?></dt>
					<dd><?php echo $this->item->company_city;?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_COMPANY_ZIPCODE');?></dt>
					<dd><?php echo $this->item->company_zip;?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_COMPANY_EMAIL');?></dt>
					<dd><a href="mailto:<?php echo $this->item->company_email;?>"><?php echo $this->item->company_email;?></a></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_COMPANY_PHONE');?></dt>
					<dd><?php echo $this->item->company_phone;?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_COMPANY_FAX');?></dt>
					<dd><?php echo $this->item->company_fax;?></dd>
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_PROFILE_COMPANY_WEBSITE');?></dt>
					<dd><?php echo $this->item->company_website;?></dd>
				</dl>
			</div>
		</div>
	
		<input type="hidden" name="jform[id]" value="<?php echo $this->item->id;?>" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
