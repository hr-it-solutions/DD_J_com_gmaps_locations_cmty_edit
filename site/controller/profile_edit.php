<?php
/**
 * @package    DD_GMaps_Locations_CMTY_Edit
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Content article class.
 *
 * @since  1.6.0
 */
class DD_GMaps_Locations_CMTY_EditControllerProfile_Edit extends JControllerForm
{
	/**
	 * Method to add a new record.
	 *
	 * @return  mixed  True if the record can be added, an error object if not.
	 *
	 * @since   1.6
	 */
	public function add()
	{

		$this->option = 'com_dd_gmaps_locations';
		$this->context = 'profile_edit';

		$context = "$this->option.edit.$this->context";

		// Access check.
		if (!$this->allowAdd())
		{
			// Set the internal error and also the redirect error.
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			$return = false;
		}

		// Clear the record edit information from the session.
		JFactory::getApplication()->setUserState($context . '.data', null);

		// Redirect to the edit screen.
		$this->setRedirect(
			JRoute::_(
				'index.php?option=com_dd_gmaps_locations_cmty_edit&view=profile_edit&layout=edit&id=0', false
			)
		);

		$return = true;

		if (!$return)
		{
			// Redirect to the return page.
			$this->setRedirect($this->getReturnPage());
		}
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key
	 * (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 * @since   1.6
	 */
	public function edit($key = null, $urlVar = 'id')
	{
		$result = parent::edit($key, $urlVar);

		if (!$result)
		{
			$this->setRedirect(JRoute::_($this->getReturnPage()));
		}

		return $result;
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since    Version 1.1.0.1
	 */
	protected function allowAdd($data = array())
	{
		$user       = JFactory::getUser();
		$categoryId = ArrayHelper::getValue($data, 'catid', $this->input->getInt('catid'), 'int');
		$allow      = null;

		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', 'com_dd_gmaps_locations.category.' . $categoryId);
		}

		if ($allow === null)
		{
			$this->option = 'com_dd_gmaps_locations';

			// In the absense of better information, revert to the component permissions.
			return $user->authorise('core.create', $this->option) || count($user->getAuthorisedCategories($this->option, 'core.create'));
		}
		else
		{
			return $allow;
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since    Version 1.1.0.1
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();

		// Zero record (id:0), return component edit permission by calling parent controller method
		if (!$recordId)
		{
			return parent::allowEdit($data, $key);
		}

		// Check edit on the record asset (explicit or inherited)
		if ($user->authorise('core.edit', 'com_dd_gmaps_locations.location.' . $recordId))
		{
			return true;
		}

		// Check edit own on the record asset (explicit or inherited)
		if ($user->authorise('core.edit.own', 'com_dd_gmaps_locations.location.' . $recordId))
		{
			// Existing record already has an owner, get it
			$record = $this->getModel()->getItem($recordId);

			if (empty($record))
			{
				return false;
			}

			// Grant if current user is owner of the record
			return $user->id == $record->created_by;
		}

		return false;
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   1.6
	 */
	public function cancel($key = 'a_id')
	{
		parent::cancel($key);

		// Redirect to the return page.
		$this->setRedirect(JUri::current(), 'Profile edit cancelled', 'note');
	}


	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string	The return URL.
	 *
	 * @since   1.6
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return JUri::base();
		}
		else
		{
			return base64_decode($return);
		}
	}

	/**
	 * Method to save a record.
	 * // Adapted from Joomla!
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   1.6
	 */
	public function save($key = null, $urlVar = null)
	{
		$result    = parent::save($key, $urlVar);
		$app       = JFactory::getApplication();
		$profileId = $app->input->getInt('a_id');

		// Load the parameters.
		$params   = $app->getParams();
		$menuitem = (int) $params->get('redirect_menuitem');

		// Check for redirection after submission when creating a new article only
		if ($menuitem > 0 && $profileId == 0)
		{
			$lang = '';

			if (false && /* todo: >> */ JLanguageMultilang::isEnabled())
			{
				$item = $app->getMenu()->getItem($menuitem);
				$lang =  !is_null($item) && $item->language != '*' ? '&lang=' . $item->language : '';
			}

			$return = base64_encode('index.php?Itemid=' . $menuitem . $lang);

			// If ok, redirect to the return page.
			if ($result)
			{
				$this->setRedirect(JRoute::_(base64_decode($return)));
			}
		}
		else
		{
			// If ok, redirect to the return page.
			if ($result)
			{
				$this->setRedirect(JRoute::_($this->getReturnPage()));
			}
		}
	}
}