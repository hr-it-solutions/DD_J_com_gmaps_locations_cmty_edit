<?php
/**
 * @package    DD_GMaps_Locations_CMTY_Edit
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

/**
 * Profile_Edit view
 *
 * @since  1.0.0.0
 */
class DD_GMaps_Locations_CMTY_EditViewProfile_Edit extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.0.0.0
	 */
	public function display($tpl = null)
	{
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();

		// Get model data.
		$this->state       = $this->get('State');
		$this->item        = $this->get('Item');
		$this->form        = $this->get('Form');

		/* todo: implement check via controller, insted of the checks below this comment!
		$this->input = $app->input;
		$view        = $this->input->get('view', 'profile_edit');
		$layout      = $this->input->get('layout', 'default');
		$id          = $this->input->getInt('id');

		// Check for edit form

		if ($view == 'profile_edit' && $layout == 'default' && !$this->checkEditId('com_dd_gmaps_locations.edit.location', $id))
		{
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return false;
		}
		*/

		if (empty($this->item->id))
		{
			$authorised = $user->authorise('core.create', 'com_dd_gmaps_locations')
				|| count($user->getAuthorisedCategories('com_dd_gmaps_locations', 'core.create'));
		}
		else
		{
			$authorised = $user->authorise('core.edit', 'com_dd_gmaps_locations') || ($user->authorise('core.edit.own', 'com_dd_gmaps_locations') && $this->item->created_by === $user->id);
		}

		if ($authorised !== true)
		{
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return false;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}

		JFactory::getLanguage()->load('com_dd_gmaps_locations', JPATH_ADMINISTRATOR, 'en-GB', true);
		JFactory::getLanguage()->load('com_dd_gmaps_locations', JPATH_ADMINISTRATOR, JFactory::getLanguage()->getTag(), true);

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void.
	 */
	protected function _prepareDocument()
	{
		// todo:
	}
}
