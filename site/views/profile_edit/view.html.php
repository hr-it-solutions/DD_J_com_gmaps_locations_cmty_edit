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
		$app    = JFactory::getApplication();
		$params = $app->getParams();

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		parent::display($tpl);
	}
}
