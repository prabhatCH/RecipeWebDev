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

JSubMenuHelper::addEntry(
                        JText::_( 'COM_BEESTOWORKFLOW_USERS' ),
                        'index.php?option=com_beestoworkflow&view=users', JRequest::getVar('view','') == 'users' );                     
                        
JSubMenuHelper::addEntry(
                        JText::_( 'COM_BEESTOWORKFLOW_WORFKFLOWS' ),
                        'index.php?option=com_beestoworkflow&view=workflows', JRequest::getVar('view','') == 'workflows' ||  JRequest::getVar('view','') == 'steps' );     
   
JSubMenuHelper::addEntry(
                        JText::_( 'COM_BEESTOWORKFLOW_PROJECTS' ),
                        'index.php?option=com_beestoworkflow&view=projects', JRequest::getVar('view','') == 'projects' ); 
                        
JSubMenuHelper::addEntry(
                        JText::_( 'COM_BEESTOWORKFLOW_TASKS' ),
                        'index.php?option=com_beestoworkflow&view=tasks', JRequest::getVar('view','') == 'tasks' ); 
                        
JSubMenuHelper::addEntry(
                        JText::_( 'COM_BEESTOWORKFLOW_ATTACHMENTS' ),
                        'index.php?option=com_beestoworkflow&view=files', JRequest::getVar('view','') == 'files' );                                                                 

JSubMenuHelper::addEntry(
                        JText::_( 'COM_BEESTOWORKFLOW_ABOUT' ),
                        'index.php?option=com_beestoworkflow&view=about', JRequest::getVar('view','') == 'about');               
?>
