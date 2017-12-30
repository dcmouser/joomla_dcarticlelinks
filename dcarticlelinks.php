<?php
/**
 * @version		$Id: dcfunctions.php 1.- 11/02/16 mouser@donationcoder.com $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2016 by mouser@donationcoder.com. All rights reserved.
 * @license		TBD
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// based on code from https://docs.joomla.org/J3.x:Creating_a_content_plugin


class PlgContentDcarticlelinks extends JPlugin {
	

	// to add custom menuitem field for html description
	// see form directory and https://docs.joomla.org/Editor_form_field_type
    function onContentPrepareForm2($form, $data) {

        $app = JFactory::getApplication();
        $option = $app->input->get('option');
        $view = $app->input->get('view');

        switch($option) {

                case 'com_menus': {
                    if ($app->isAdmin() && $view == 'item') {
                    //if ($app->isAdmin()) {
                            JForm::addFormPath(__DIR__ . '/forms');
                            $form->loadFile('articlelinks_form', false);
                    }
                    return true;
                }

        }
        return true;

    }  



	// to add custom menuitem field for html description
	// see form directory and https://docs.joomla.org/Editor_form_field_type
    function onContentPrepareForm($form, $data) {

        $app = JFactory::getApplication();
        $option = $app->input->get('option');
        $view = $app->input->get('view');
		$name = $form->getName();

		if (!($form instanceof JForm)) {
			$this->_subject->setError('JERROR_NOT_A_FORM');
				return false;
		}

		// Check we are manipulating a valid form.
		if (!in_array($name, array('com_content.article'))) {
			return true;
		}
		if (!in_array($view, array('article'))) {
			return true;
		}
		if (!$app->isAdmin()) {
			return true;
		}

		if (true) {
			JForm::addFieldPath(__DIR__ . '/forms');
			JForm::addFormPath(__DIR__ . '/forms');
			$form->loadFile('articlelinks_form', false);
			}
        return true;
}


}

