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

class BeestoWorkflowHelper
{
	public static function getActions() {
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_beestoworkflow';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	
	
	/*
	 * Method to determine if the user/visitor is allowed to access the area
	 * 
	 * @param
	 * @return		mixed, false if failure / object with user details if success
	 */
	public static function authorise () {
		
		$user 	= JFactory::getUser()->get('id');

		if (!$user) {
			return false;
		}
			
		$table 	= JTable::getInstance('BWuser', 'BeestoWorkflowTable');
			
		$table->load($user);
		
		if (!$table->published) {
			return false;
		}
			
		return $table;	
	}
	
	
	public static function getTime ($format = null) {
	
		jimport('joomla.utilities.date');
				
		$app		= JFactory::getApplication();
		
		$tz			= new DateTimeZone($app->getCfg('offset'));
		$jdate 		= new JDate();
		$jdate->setTimezone($tz);
		
		if ($format) {
			$date = $jdate->format($format,true);	
		} else {
			$date = $jdate->toSql(true);	
		}
	
		return $date;
	}
	
	
	public static function getPriority () {
		
		$priority	= array(0=>JText::_('COM_BEESTOWORKFLOW_USUAL_VERY_LOW'),1=>JText::_('COM_BEESTOWORKFLOW_USUAL_LOW'),2=>JText::_('COM_BEESTOWORKFLOW_USUAL_MEDIUM'),3=>JText::_('COM_BEESTOWORKFLOW_USUAL_HIGH'),4=>JText::_('COM_BEESTOWORKFLOW_USUAL_VERY_HIGH'));

		return $priority;
	}

	public static function getProjectStatuses () {
		
		$state = array(0=>JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_PENDING'),1=>JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ACTIVE'),2=>JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_COMPLETED'));
		
		return $state;
	}
	
	
	
	/*
	 * Method to return a user property
	 * 
	 * @param		$property  - the property we need 
	 * @return		mixed, false if the property doesn't exist / property if success
	 */	
	public static function getUserProperty ( $property, $id = null ) {

		//get user params
		if (!$id) {
			$id = JFactory::getUser()->get('id');
		}
		
		if (!$id) {return false;}
		$table = JTable::getInstance('BWuser','BeestoWorkflowTable');
		$table->load($id);
		
		if(isset($table->$property)) {
			return $table->$property;
		} else {
			$registry = new JRegistry();
			$registry->loadString($table->params);
			$settings = $registry->get('params');
			if (!$settings) {return false;}
			if(isset($settings->$property)) {
				return $settings->$property;
			}
			return false;
		}
	}
	
	
	
	/*
	 * Method to set user params
	 * 
	 * @param		$key 	 	- the property we want to set
					$value		- the value of that property
					$id			- user ID, if none specified will set for current user
	 * @return		
	 */	
	public static function setUserParams ( $key, $value, $id=null ) {

		//get user params
		if (!$id) {
			$id = JFactory::getUser()->get('id');
		}
		
		if (!$id) {return false;}
		$table = JTable::getInstance('BWuser','BeestoWorkflowTable');
		$table->load($id);

		$registry = new JRegistry();
		$registry->loadString($table->params);
		
		$params = $registry->get('params');
			
		if(isset($params->$key)) {
			if(empty($value)) {
				$params->$key = array();
				$registry->set('params',$params);
				$table->params = (string) $registry;
				$table->store();
			
			} elseif(!in_array($value,$params->$key)) {
				array_push($params->$key,$value);
				$registry->set('params',$params);
				$table->params = (string) $registry;
				$table->store();
			}
		} else {
			$params->$key = array($value);
			$registry->set('params',$params);
			$table->params = (string) $registry;
			$table->store();
		}
	}
	
	
	
	/*
	 * Method to check if the user is allowed/denied for a specific setting
	 * 
	 * @param		$setting, the setting we want to check
	 * @return		boolean, true if he is allowed, false if he isn't allowed
	 */
	public static function canDo ( $setting ) {
		//get user params
		$personal 				= BeestoWorkflowHelper::getUserProperty ($setting);
		$params					= JComponentHelper::getParams('com_beestoworkflow');

		// first we check if the user has his own settings, if no take the settings from component
		if ($personal) {
			switch($personal) {
					case('inherited'):
						if($params->get($setting) == 1) {
							return true;
						} else {;
							return false;
						}
					break;
					
					case('allowed'):
						return true;
					break;
			
					case('denied'):
						return false;
					break;
			}
		} else {
			if($params->get($setting) == 1) {
				return true;
			} else {
				return false;
			}
		}
	}

	
	
	/*
	 * Method to check if the current user is the manager of one project
	 * 
	 * @param		$id - the project ID to be checked
	 * @return		boolean, true if success, false on failure
	 */
	public static function isManager($id) {
		
		$user = JFactory::getUser()->get('id');
		if (!$user) {return false;}
		$table = JTable::getInstance('Project','BeestoWorkflowTable');
		$table->load($id);
		
		if ($table->manager == $user) {
			return true;
		}
		return false;
	}
	
	
	public static function getStage ($due) {
		
		$now = self::getTime();
		if ($now > $due) {
			return 'overdue';
		} elseif ($now <= $due) {
			return 'active';
		}
	}
	

	/*
	 * Method to get all participants at a specific workflow
	 * 
	 * @param		$worfklow 		the workflow for which we want to get all the users involved
	 * @return		
	 */
	public static function getWorkflowStaff ( $workflow ) {
	
		$db		= JFactory::getDbo();
		$table	= JTable::getInstance('BWuser','BeestoWorkflowTable');
		$staff	= array();
		
		$query = ' SELECT a.assigned_to FROM #__beestowf_project_workflow_steps AS a WHERE a.project_workflow = ' . (int) $workflow;
		$db->setQuery($query);
		$json_emails = $db->loadColumn();
		if ($json_emails) {
			foreach ($json_emails as $json_email) {
				$ids = json_decode($json_email);
				foreach ($ids as $id) {
					$table->load($id);
					$recipient = new stdClass();
					$recipient->email 	= $table->email;
					$recipient->type 	= $table->type;
					$staff[] = $recipient;
				}
			}
		}
		
		return $staff;
	}
	
	
	
	/*
	 * Method to get all the projects he is allowed to see
	 *
	 * @param
	 * @return		mixed, 'all' if he is allowed to see all projects / array with projects he is allowed
	 */
	public static function getOtherProjects () {
	
		//get user params
		$viewActiveProjects 	= self::getUserProperty ('viewActiveProjects');
		$params					= JComponentHelper::getParams('com_beestoworkflow');

		// first we check if the user has his own settings, if no take the settings from component
		if ($viewActiveProjects) {
			switch($viewActiveProjects) {
					
					case('inherited'):
						if($params->get('viewActiveProjects') == 1) {
							return 'all';
						} else {
							$allowed = self::getAllowed ();
							return $allowed;
						}
					break;
					
					case('allowed'):
						return 'all';
					break;
			
					case('denied'):
						$allowed = self::getAllowed ();
						return $allowed;
					break;
			}
		} else {
			if($params->get('viewActiveProjects') == 1) {
				return 'all';
			} else {
				$allowed = self::getAllowed ();
				return $allowed;
			}
		}
	}
	
	
	/*
	 * Method to return an array with project ID allowed to be access by current user
	 * 
	 * @param		
	 * @return		array
	 */
	protected static function getAllowed () {
		
		$db = JFactory::getDbo();
		$userID = JFactory::getUser()->get('id');
		
		// if he can manage project requests add also pending projects
		if (self::canDo('manageProjectRequests')) {
			$manage = ' OR a.status = 0 ';
		} else {
			$manage = '';
		}
		
		$query = ' SELECT DISTINCT(a.id) FROM #__beestowf_projects AS a  WHERE a.manager = ' . $userID . $manage . ' UNION SELECT DISTINCT(b.project_id) FROM #__beestowf_tasks AS b WHERE b.assigned_to REGEXP \'\"' . $userID . '\"\' OR b.created_by = ' . $userID;
		$db->setQuery($query); 
		$access = $db->loadColumn();
	
		return $access;
	}
	
	
	
	/*
	 * Method to send alerts when a new project is created or when the project was completed
	 * 
	 * @param		$stage: new, completed
	 * @return
	 */
	public static function sendAlert ( $stage, $extra = array() ) {
		
		$params		= JComponentHelper::getParams('com_beestoworkflow');
		$data		= JRequest::getVar('jform',array(),'','array');
		$from 		= JFactory::getApplication('administrator')->getCfg('mailfrom');
		$fromname	= JFactory::getApplication('administrator')->getCfg('fromname');
		$table		= JTable::getInstance('BWuser','BeestoWorkflowTable');
		$db			= JFactory::getDbo();
		
		
		// if it's alert for new project		
		if ($stage == 'newproject') {
			
			if ($toalert = $params->get('new_project')) {

				$subject 	= $params->get('new_project_alert_subj');
				$body		= $params->get('new_project_alert_body'); 
				$to 	 	= array();
				$urlstaff	= JURI::root(false) . 'index.php?option=com_beestoworkflow&view=staffprojects';
				$urlclient	= JURI::root(false) . 'index.php?option=com_beestoworkflow&view=clientprojects';

				switch ($toalert) {
					// if we send only to creator
					case ('1') :
						$recipient = new stdClass();
						$recipient->email 	= self::getUserProperty('email') ?  self::getUserProperty('email') : JFactory::getUser()->get('email');
						$recipient->type 	= 0;
						$to[] 	=  $recipient;
					break;
					
					// if we send only to assignee, if case
					case ('2') :
						// if uses a workflow get the assignee
						if ($data['project_template']) {
							$to = self::getWorkflowStaff( $data['project_template'] );
						}
					break;
					
					// if we send to both
					case ('3') :
						$recipient = new stdClass();
						$recipient->email 	= self::getUserProperty('email') ?  self::getUserProperty('email') : JFactory::getUser()->get('email');
						$recipient->type 	= 0;
						array_push($to,$recipient);

						if ($data['project_template']) {
							$to = self::getWorkflowStaff( $data['project_template'] );
							array_push($to,$recipient);
						} 

						if ( $data['client'] ) {
							$recipient = new stdclass;
							$recipient->email = JFactory::getUser( $data['client'] )->get('email');
							$recipient->type = 1; 
							array_push($to,$recipient);
						}
					break;	
				}
	
				// if we have to whom to send we'll do it!
				if ($to) {
					
					// get unique recipients for staff ($bcc) and clients ($ccc)
					$destinatar_staff = null;
					$destinatar_client = null;
					$bcc = array();
					$ccc = array();
					
					foreach ($to as $recipient) {
						if (!in_array($recipient->email,$bcc)) {
							if ($recipient->type == 0) {
								if (!$destinatar_staff) {
									$destinatar_staff = $recipient->email;
								} else {
									if ($destinatar_staff != $recipient->email) {
										$bcc[] = $recipient->email;
									}
								}
							} elseif ($recipient->type == 1) {
								if (!$destinatar_client) {
									$destinatar_client = $recipient->email;
								} else {
									if ($destinatar_client != $recipient->email) {
										$ccc[] = $recipient->email;
									}
								}
							}
						}
					}

					// get the links to see more details
					$urlstaff  = JText::sprintf(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_END'), $urlstaff);
					$urlclient = JText::sprintf(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_END'), $urlclient);

					// send email for staff and for clients
					$mailer		= JFactory::getMailer();
					if ($destinatar_staff) { $mailer->sendMail( $from, $fromname, $destinatar_staff, $subject, $body . $urlstaff ,false, null,$bcc); }
					if ($destinatar_client) { $mailer->sendMail( $from, $fromname, $destinatar_client, $subject, $body . $urlclient,false, null,$ccc); }
					
				}
			}
			
		} elseif ($stage == 'projectcomplete') {
			if ($toalert = $params->get('project_completed')) {

				$subject 	= $params->get('project_completed_alert_subj');
				$body		= $params->get('project_completed_alert_body');
				$to 	 	= array();
				$urlstaff	= JURI::root(false) . 'index.php?option=com_beestoworkflow&view=staffprojects';
				$urlclient	= JURI::root(false) . 'index.php?option=com_beestoworkflow&view=clientprojects';
				
				$project = JRequest::getVar('project','','','int');
				$query = ' SELECT a.project_template, a.name, a.start, a.due FROM #__beestowf_projects AS a WHERE a.id = ' . $project . ' LIMIT 1';
				$db->setQuery($query);
				$proj = $db->loadObject();
						

				switch ($toalert) {
					// if we send only to creator
					case ('1') :
						$recipient = new stdClass();
						$recipient->email 	= self::getUserProperty('email') ?  self::getUserProperty('email') : JFactory::getUser()->get('email');
						$recipient->type 	= 0;
						$to[] 	=  $recipient;
					break;
					
					// if we send only to assignee, if case
					case ('2') :
						
						// if uses a workflow get the assignee
						if ($proj->project_template) {
							$to = self::getWorkflowStaff( $proj->project_template );
						}
						
						// if any extra tasks let those people also know
						$query = ' SELECT a.assigned_to FROM #__beestowf_tasks AS a WHERE a.project_id = ' . $project;
						$db->setQuery($query);
						$json_emails = $db->loadColumn();
						if ($json_emails) {
							foreach ($json_emails as $json_email) {
								$ids = json_decode($json_email);
								foreach ($ids as $id) {
									$table->load($id);
									$recipient = new stdClass();
									$recipient->email 	= $table->email ? $table->email : JFactory::getUser($id)->get('email');
									$recipient->type 	= $table->type;
									array_push($to,$recipient);
								}
							}
						}
							
					break;
					
					// if we send to both MANAGER + ASSIGNEE
					case ('3') :
						
						$recipient = new stdClass();
						$recipient->email 	= self::getUserProperty('email') ?  self::getUserProperty('email') : JFactory::getUser()->get('email');
						$recipient->type 	= 0;
						
						// if uses a workflow get the assignee
						if ($proj->project_template) {
							$to = self::getWorkflowStaff( $proj->project_template );
							array_push($to,$recipient);
						} else {
							$to[] = $recipient;
						}
						
						// if any extra tasks let those people also know
						$query = ' SELECT a.assigned_to FROM #__beestowf_tasks AS a WHERE a.project_id = ' . $project;
						$db->setQuery($query);
						$json_emails = $db->loadColumn();
						
						if ($json_emails) {
							foreach ($json_emails as $json_email) {
								$ids = json_decode($json_email);
								foreach ($ids as $id) {
									$table->load($id);
									$recipient = new stdClass();
									$recipient->email 	= $table->email ? $table->email : JFactory::getUser($id)->get('email');
									$recipient->type 	= $table->type;
									array_push($to,$recipient);
								}
							}
						}
					
					break;	
				}
				
				// if we have to whom to send we'll do it!
				if ($to) {
					
					// get unique recipients for staff ($bcc) and clients ($ccc)
					$destinatar_staff = null;
					$destinatar_client = null;
					$bcc = array();
					$ccc = array();
					
					foreach ($to as $recipient) {
						if (!in_array($recipient->email,$bcc)) {
							if ($recipient->type == 0) {
								if (!$destinatar_staff) {
									$destinatar_staff = $recipient->email;
								} else {
									if ($destinatar_staff != $recipient->email) {
										$bcc[] = $recipient->email;
									}
								}
							} elseif ($recipient->type == 1) {
								if (!$destinatar_client) {
									$destinatar_client = $recipient->email;
								} else {
									if ($destinatar_client != $recipient->email) {
										$ccc[] = $recipient->email;
									}
								}
							}
						}
					}
					
					// get the links to see more details
					$urlstaff  = JText::sprintf(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_END'), $urlstaff);
					$urlclient = JText::sprintf(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_END'), $urlclient);

					// send email for staff and for clients
					$mailer		= JFactory::getMailer();
					if ($destinatar_staff) { $mailer->sendMail( $from, $fromname, $destinatar_staff, $subject, $body . $urlstaff ,false, null,$bcc); }
					if ($destinatar_client) { $mailer->sendMail( $from, $fromname, $destinatar_client, $subject, $body . $urlclient,false, null,$ccc); }
				}
			}
			
		} elseif ($stage == 'newtask') {
			if ($params->get('new_task') == 1) {
				
				// get all the details we need
				$id 			= $extra['id'];
				$assigned_to	= $extra['assigned_to'];
				$task_name		= $extra['name'];
				
				// get the names of the users assigned
				$assigned_to_names = array();
				if ($assigned_to) {
					$assignedto = json_decode($assigned_to);
					foreach($assignedto as $value) {
						$assigned_to_names[] = JFactory::getUser($value)->get('name'); 
					}
				}
				
				
				$subject 	= $params->get('new_task_alert_subj');
				$body		= $params->get('new_task_alert_body');
				$to 	 	= array();
				$urlstaff	= JURI::root(false) . 'index.php?option=com_beestoworkflow&task=stafftask.edit&id=' . $id;
				$urlclient	= JURI::root(false) . 'index.php?option=com_beestoworkflow&task=clienttask.edit&id=' . $id;
				
				
				// get the users assigned
				if ($assigned_to) {
					
					$assignedto = json_decode($assigned_to);
					
					foreach ($assignedto as $assigned) {
						$table->load($assigned);
						$recipient = new stdClass();
						$recipient->email 	= $table->email ? $table->email : JFactory::getUser($assigned)->get('email');
						$recipient->type 	= $table->type;
						array_push($to,$recipient);
					}
				}
			
				
				// if we have to whom to send we'll do it!
				if ($to) {
				
					// get unique recipients for staff ($bcc) and clients ($ccc)
					$destinatar_staff = null;
					$destinatar_client = null;
					$bcc = array();
					$ccc = array();
					
					foreach ($to as $recipient) {
						if (!in_array($recipient->email,$bcc)) {
							if ($recipient->type == 0) {
								if (!$destinatar_staff) {
									$destinatar_staff = $recipient->email;
								} else {
									if ($destinatar_staff != $recipient->email) {
										$bcc[] = $recipient->email;
									}
								}
							} elseif ($recipient->type == 1) {
								if (!$destinatar_client) {
									$destinatar_client = $recipient->email;
								} else {
									if ($destinatar_client != $recipient->email) {
										$ccc[] = $recipient->email;
									}
								}
							}
						}
					}
			
					// get the links to see more details
					$urlstaff  = JText::sprintf(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_END'), $urlstaff);
					$urlclient = JText::sprintf(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_END'), $urlclient);

					// send email for staff and for clients
					$mailer		= JFactory::getMailer();
					if ($destinatar_staff) { $mailer->sendMail( $from, $fromname, $destinatar_staff, $subject, $body . $urlstaff ,false, null,$bcc); }
					if ($destinatar_client) { $mailer->sendMail( $from, $fromname, $destinatar_client, $subject, $body . $urlclient,false, null,$ccc); }		
				}
			}
		} elseif ( $stage == 'newprojmessage' ) {
			
			$assignees = array();

			// set message, if it's not set by user into params, we'll just assign the default subject
			$subject = $params->get('proj_mess_alert');
			if (!$subject) {
				$subject = JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_PROJECT_MESSAGE_ALERT');
			}
		
			// get all users who are assigned for this project
			$project_id = $extra['id'];
			$query 		= 'SELECT a.assigned_to FROM #__beestowf_tasks AS a WHERE a.project_id = ' . (int) $project_id;
			$db->setQuery($query);
			$results = $db->loadColumn();

			if ( $results ) {
				foreach ($results as $result) {
					$users = json_decode($result);
					foreach ($users as $user) {
						if(!in_array($user, $assignees)) {
							$assignees[] = $user;
						}
					}
				}
			}
			
			
			// get project name
			$projectTable = JTable::getInstance('Project','BeestoWorkflowTable');
			$projectTable->load($project_id);
			$project_name = $projectTable->name;
			
			// create message
			$body	   	= JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_HELLO') . "\n\n";
			$body	   .= JText::sprintf(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_INTRO4'),$project_name,JFactory::getUser()->get('name')) . "\n\n";
			$body	   .= $extra['comment'];
			
			// send emails
			foreach ($assignees as $destinatar) {
				$mailer		= JFactory::getMailer();
				$destinatar_email = self::getUserProperty('email', $destinatar) ? self::getUserProperty('email', $destinatar) : JFactory::getUser($destinatar)->get('email');
				$mailer->sendMail( $from, $fromname, $destinatar_email, $subject, $body );
			}
		} elseif ( $stage == 'newprojfile' ) {
			
			// set message, if it's not set by user into params, we'll just assign the default subject
			$subject = $params->get('proj_file_alert');
			if (!$subject) {
				$subject = JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_PROJECT_FILE_ALERT');
			}
			
			// get all users who are assigned for this project
			$project_id = $extra['project'];
			$query = 'SELECT a.assigned_to FROM #__beestowf_tasks AS a WHERE a.project_id = ' . (int) $project_id;
			$db->setQuery($query);
			$results = $db->loadColumn();
			if (!$results) {
				return;
			}
			$assignees = array();
			foreach ($results as $result) {
				$users = json_decode($result);
				foreach ($users as $user) {
					if(!in_array($user, $assignees)) {
						$assignees[] = $user;
					}
				}
			}
			
			// get project name
			$projectTable = JTable::getInstance('Project','BeestoWorkflowTable');
			$projectTable->load($project_id);
			$project_name = $projectTable->name;

			// get file name
			$filename = JFile::makeSafe($extra['name']);
			
			// create message
			$body	   	= JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_HELLO') . "\n\n";
			$body	   .= JText::sprintf(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_INTRO5'),$project_name,JFactory::getUser()->get('name'),$filename) . "\n\n";
			
			// send emails
			foreach ($assignees as $destinatar) {
				$mailer		= JFactory::getMailer();
				$destinatar_email = self::getUserProperty('email',$destinatar) ? self::getUserProperty('email', $destinatar) : JFactory::getUser($destinatar)->get('email');
				$mailer->sendMail( $from, $fromname, $destinatar_email, $subject, $body );
			}
		
		} elseif ( $stage == 'taskcompleted' ) {
		
			if ($params->get('task_completed') == 1) {

				// get extra details
				$task_id 	= $extra['task_id'];
				$task_name 	= $extra['task_name']; 
				$created_by = $extra['created_by'];
				$project_id = $extra['project_id'];
				
				// set message, if it's not set by user into params, we'll just assign the default subject
				$subject 	= $params->get('task_completed_alert_subj', JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_COMPLETED') );
				$body		= $params->get('task_completed_alert_body');
		
				// get project name
				if ($project_id) {
					$projectTable = JTable::getInstance('Project','BeestoWorkflowTable');
					$projectTable->load($project_id);
					$project_name = $projectTable->name;
				} else {
					$project_name = JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_GENERAL');
				}
				
				
				// get owner email
				$destinatar = self::getUserProperty('email',$created_by) ? self::getUserProperty('email',$created_by) : JFactory::getUser($created_by)->get('email');
	
				// get task link
				$link = JURI::root(false) . 'index.php?option=com_beestoworkflow&task=stafftask.edit&id=' . $task_id;
				$mailer		= JFactory::getMailer();
				$mailer->sendMail( $from, $fromname, $destinatar, $subject, $body );
			
			}
		} elseif ( $stage == 'newtaskmessage' ) {
				
			// set message, if it's not set by user into params, we'll just assign the default subject
			$subject = $params->get('task_mess_alert');
			if (!$subject) {
				$subject = JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_MESSAGE_ALERT');
			}
		
			// get all users who are assigned for this project
			$task_id = $extra['id'];
			$query = 'SELECT a.assigned_to,a.created_by, a.project_id, a.name FROM #__beestowf_tasks AS a WHERE a.id = ' . (int) $task_id . ' LIMIT 1';
			$db->setQuery($query);
			$results = $db->loadObject();

			if (!$results) {
				return;
			}
			
			$assignees 	= json_decode($results->assigned_to);
			array_push($assignees, $results->created_by);
		
			// get current user
			$currentUser = new stdClass();
			$currentUser->name = JFactory::getUser()->get('name');
			$currentUser->id = JFactory::getUser()->get('id');
			
			// create message
			$body	   	= JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_HELLO') . "\n\n";
			$body	   .= JText::sprintf(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_INTRO7'),$results->name,$currentUser->name) . "\n\n";
			$body	   .= $extra['comment'];
			
			// send emails but exclude the person who comments
			foreach ($assignees as $destinatar) {
				if ($destinatar != $currentUser->id) {
					$mailer		= JFactory::getMailer();
					$destinatar_email = self::getUserProperty('email',$destinatar) ? self::getUserProperty('email', $destinatar) : JFactory::getUser($destinatar)->get('email');
					$mailer->sendMail( $from, $fromname, $destinatar_email, $subject, $body );
				}
			} 
		}  elseif ( $stage == 'newtaskfile' ) {
				
			// set message, if it's not set by user into params, we'll just assign the default subject
			$subject = $params->get('task_file_alert');
			if (!$subject) {
				$subject = JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_FILE_ALERT');
			}
		
			// get all users who are assigned for this project
			$task_id = $extra['task_id'];
			$query = 'SELECT a.assigned_to,a.created_by, a.project_id, a.name FROM #__beestowf_tasks AS a WHERE a.id = ' . (int) $task_id . ' LIMIT 1';
			$db->setQuery($query);
			$results = $db->loadObject();

			if (!$results) {
				return;
			}
			
			$assignees 	= json_decode($results->assigned_to);
			array_push($assignees, $results->created_by);
		
			// get current user
			$currentUser = new stdClass();
			$currentUser->name = JFactory::getUser()->get('name');
			$currentUser->id = JFactory::getUser()->get('id');
			
			// create message
			$body	   	= JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_HELLO') . "\n\n";
			$body	   .= JText::sprintf(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_BODY_INTRO8'),$results->name,$currentUser->name,$extra['name']) . "\n\n";
			
			// send emails but exclude the person who add the file
			foreach ($assignees as $destinatar) {
				if ($destinatar != $currentUser->id) {
					$mailer		= JFactory::getMailer();
					$destinatar_email = self::getUserProperty('email',$destinatar) ? self::getUserProperty('email', $destinatar) : JFactory::getUser($destinatar)->get('email');
					$mailer->sendMail( $from, $fromname, $destinatar_email, $subject, $body );
				}
			} 
		}
		
		
	}
	


	
	
}
