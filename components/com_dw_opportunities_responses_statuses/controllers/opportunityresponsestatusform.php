<?php

/**
 * @version     1.0.4
 * @package     com_dw_opportunities_responses_statuses
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Opportunityresponsestatus controller class.
 */
class Dw_opportunities_responses_statusesControllerOpportunityresponsestatusForm extends Dw_opportunities_responses_statusesController {

    /**
     * Method to check out an item for editing and redirect to the edit form.
     *
     * @since	1.6
     */
    public function edit() {
        $app = JFactory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.id');
        $editId = JFactory::getApplication()->input->getInt('id', null, 'array');

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.id', $editId);

        // Get the model.
        $model = $this->getModel('OpportunityresponsestatusForm', 'Dw_opportunities_responses_statusesModel');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_dw_opportunities_responses_statuses&view=opportunityresponsestatusform&layout=edit', false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return	void
     * @since	1.6
     */
    public function save() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('OpportunityresponsestatusForm', 'Dw_opportunities_responses_statusesModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $input = $app->input;
            $jform = $input->get('jform', array(), 'ARRAY');

            // Save the data in the session.
            $app->setUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.data', $jform, array());

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.id');
            $this->setRedirect(JRoute::_('index.php?option=com_dw_opportunities_responses_statuses&view=opportunityresponsestatusform&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.id');
            $this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_dw_opportunities_responses_statuses&view=opportunityresponsestatusform&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_DW_OPPORTUNITIES_RESPONSES_STATUSES_ITEM_SAVED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_dw_opportunities_responses_statuses&view=opportunitiesresponsesstatuses' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.data', null);
    }

    function cancel() {
        
        $app = JFactory::getApplication();

        // Get the current edit id.
        $editId = (int) $app->getUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.id');

        // Get the model.
        $model = $this->getModel('OpportunityresponsestatusForm', 'Dw_opportunities_responses_statusesModel');

        // Check in the item
        if ($editId) {
            $model->checkin($editId);
        }
        
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_dw_opportunities_responses_statuses&view=opportunitiesresponsesstatuses' : $item->link);
        $this->setRedirect(JRoute::_($url, false));
    }

    public function remove() {

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('OpportunityresponsestatusForm', 'Dw_opportunities_responses_statusesModel');

        // Get the user data.
        $data = array();
        $data['id'] = $app->input->getInt('id');

        // Check for errors.
        if (empty($data['id'])) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.id');
            $this->setRedirect(JRoute::_('index.php?option=com_dw_opportunities_responses_statuses&view=opportunityresponsestatus&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->delete($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.id');
            $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_dw_opportunities_responses_statuses&view=opportunityresponsestatus&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_DW_OPPORTUNITIES_RESPONSES_STATUSES_ITEM_DELETED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_dw_opportunities_responses_statuses&view=opportunitiesresponsesstatuses' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_dw_opportunities_responses_statuses.edit.opportunityresponsestatus.data', null);
    }

}
