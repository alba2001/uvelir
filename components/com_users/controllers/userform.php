<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/registration.php'; 
/**
 * Registration controller class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class UsersControllerUserform extends UsersControllerRegistration
{

	/**
	 * Method to register a user.
	 *
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function register()
	{
		// Check for request forgeries.
		JSession::checkToken('GET') or jexit(JText::_('JINVALID_TOKEN'));

		// If registration is disabled - Redirect to login page.
		if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
//			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
                    jexit(JText::_('DO_NOT_ALLAW_USER_REGISTRATION'));
		}

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Userform', 'UsersModel');

		// Get the user data.
		$requestData = JRequest::getVar('jform', array(), 'post', 'array');

		// Validate the posted data.
		$form	= $model->getForm();
		if (!$form) {
			jexit($model->getError());
		}
		$data	= $model->validate($form, $requestData);

		// Check for validation errors.
		if ($data === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                                        $msgs[] = $errors[$i]->getMessage();
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
                                        $msgs[] = $errors[$i];
				}
			}

			// Save the data in the session.
			$app->setUserState('com_users.registration.data', $requestData);

			// Redirect back to the registration screen.
//			$this->setRedirect(JRoute::_('index.php?option=com_users&view=registration', false));
                        $_result[0] = 0;
                        $_result[1] = implode('<br/>', $msgs);
                        jexit(json_encode($_result));
			
		}

		// Attempt to save the data.
		$return	= $model->register($data);

		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_users.registration.data', $data);

			// Redirect back to the edit screen.
                        $msg = JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $model->getError());
			$this->setMessage($msg, 'warning');
                        $_result = array(0,$msg);
                        jexit(json_encode($_result));
		}

		// Flush the data from the session.
		$app->setUserState('com_users.registration.data', null);
                $msg = '';
		// Redirect to the profile screen.
		if ($return[0] === 'adminactivate'){
			$msg = JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY');
		} elseif ($return[0] === 'useractivate') {
			$msg = JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE');
		} else {
			$msg = JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS');
		}
                jexit(json_encode(array(1,$msg,$return[1])));
	}
}
