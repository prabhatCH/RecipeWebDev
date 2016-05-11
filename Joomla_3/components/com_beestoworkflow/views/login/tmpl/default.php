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
$return = base64_encode(JRoute::_('index.php?option=com_beestoworkflow',false));
$loggedin = JFactory::getUser()->get('id');
?>

<?php if (!$loggedin) { ?>
<div id="beestoworfklow">
	
	<form id="beestoworfklow-login-form" method="post" action="<?php echo JRoute::_('index.php?option=com_beestoworkflow',false);?>" class="form-horizontal well well-small">
		<h3 class="text-center"><i class="icon icon-lock"></i> <?php echo JText::_('COM_BEESTOWORKFLOW_LOGIN_BEESTOWORKFLOW');?></h3>
	
		<div class="control-group offset2">
			<label class="control-label" for="inputUser"><?php echo JText::_('COM_BEESTOWORKFLOW_LOGIN_USERNAME');?></label>
			<div class="controls">
				<input type="text" class="input-medium" name="username" id="inputUser" />
			</div>
		</div>
		
		<div class="control-group offset2">
			<label class="control-label" for="inputPass"><?php echo JText::_('COM_BEESTOWORKFLOW_LOGIN_PASSWORD');?></label>
			<div class="controls">
				<input type="password" class="input-medium" name="password" id="inputPass" />
			</div>
		</div>
		
		<div class="control-group offset2">
			<div class="controls">
				<input type="submit" value="<?php echo JText::_('COM_BEESTOWORKFLOW_LOGIN_LOGIN');?>" class="btn btn-primary" name="Submit" />
			</div>
		</div>
			
			
		<input type="hidden" value="com_users" name="option">
		<input type="hidden" value="user.login" name="task">
		<input type="hidden" value="<?php echo $return;?>" name="return">
		<?php echo JHtml::_('form.token'); ?>	
	
	</form>
	
</div>
<?php } ?>


