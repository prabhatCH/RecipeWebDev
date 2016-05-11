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
$type = array ('0'=>JText::_('COM_BEESTOWORKFLOW_USERS_STAFF'), '1'=>JText::_('COM_BEESTOWORKFLOW_USERS_CLIENT'));

?>

<script type="text/javascript">
	function redirect () {
		var user 	= document.getElementById('user').options[document.getElementById('user').selectedIndex].value;
		var type 	= document.getElementById('type').options[document.getElementById('type').selectedIndex].value;
		var url 	= 'index.php?option=com_beestoworkflow&task=users.donew&user=' + user + '&type=' + type;
		window.parent.location.href = url;
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&task=users.addnew');?>"
	method="post"
	name="adminForm"
	id="redirect-form">
	
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_BEESTOWORKFLOW_USERS_ADD_USER');?></legend>
			
			<label title="" ><?php echo JText::_('COM_BEESTOWORKFLOW_USERS_SELECT_USER_JOOMLA');?></label>	
			
			<select name="user" class="inputbox" id="user" >
				<option value=""> - <?php echo JText::_('COM_BEESTOWORKFLOW_USERS_SELECT_USER');?> - </option>
				<?php echo JHtml::_('select.options', BeestoWorkflowHelper::getUsers() , 'value', 'text' ) ;?>
			</select>	
			
			<div class="clr"></div>
			
			<label title="" ><?php echo JText::_('COM_BEESTOWORKFLOW_USERS_SELECT_TYPE');?></label>	
			
			<select name="type" class="inputbox" id="type" >
				<option value=""> - <?php echo JText::_('COM_BEESTOWORKFLOW_USERS_TYPE');?> - </option>
				<?php echo JHtml::_('select.options', $type , 'value', 'text' ) ;?>
			</select>					
	
			<div class="clr"></div>
			<button onclick="redirect ();window.top.setTimeout('window.parent.SqueezeBox.close()', 700);" type="button"><?php echo JText::_('COM_BEESTOWORKFLOW_USERS_ADD');?></button>
			<button onclick="window.parent.SqueezeBox.close();" type="button"><?php echo JText::_('COM_BEESTOWORKFLOW_USERS_CANCEL');?></button>
	</fieldset>
	
</form>


