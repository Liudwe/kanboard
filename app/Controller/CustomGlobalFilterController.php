<?php

namespace Kanboard\Controller;

use Kanboard\Core\Controller\AccessForbiddenException;
use Kanboard\Core\Security\Role;

/**
 * Custom Global Filter Controller
 *
 * @package Kanboard\Controller
 * @author  Timo Litzbarski
 * @author  Frederic Guillot
 */
class CustomGlobalFilterController extends BaseController
{

    /**
     * Show creation form for custom filters
     *
     * @access public
     * @param array $values
     * @param array $errors
     */
    public function create(array $values = array(), array $errors = array())
    {
        $this->response->html($this->template->render('custom_global_filter/create', array(
            'values' => $values,
            'errors' => $errors,
        )));
    }


    public function save()
    {
        $values = $this->request->getValues();

        $values['user_id'] = $this->userSession->getId();
        $currentUserId = $this->userSession->getId();
    
        // Extract selected user IDs from the $values array
        $selectedUsernamesId = isset($values['users']) ? $values['users'] : [];
        if (isset($values['users'])) {
            unset($values['users']);
        }

        // Extract selected groups IDs from the $values array
        $selectedGroupsId = isset($values['groups']) ? $values['groups'] : [];
        if (isset($values['groups'])) {
            unset($values['groups']);
        }
    

        $selectedGroupsId = array_filter($selectedGroupsId);

        // Remove null values from selectedUsernames array
        $selectedUsernamesId = array_filter($selectedUsernamesId);
    
        // Validate the creation of the filter for the current user
        list($valid, $errors) = $this->customGlobalFilterValidator->validateCreation($values);
    
        if ($valid) {
            $createSuccess = $this->customGlobalFilterModel->create($values) !== false;
    
            if ($createSuccess) {
                // Loop through each selected user ID and create the filter for each
                foreach ($selectedUsernamesId as $selectedUserId) {
                    if ($selectedUserId != $currentUserId) {
                        $values['user_id'] = $selectedUserId;
                        $this->customGlobalFilterModel->create($values);
                    }
                }

                foreach ($selectedGroupsId as $selectedGroupId) {
                    $groupMembers = $this->groupMemberModel->getMembers($selectedGroupId);
                    foreach ($groupMembers as $member) {
                        $memberUserId = $member['id'];
                        if ($memberUserId != $currentUserId) {
                            $values['user_id'] = $memberUserId;
                            $this->customGlobalFilterModel->create($values);
                        }
                    }
                }
    
                $this->flash->success(t('Your custom filter has been created successfully.'));
                $this->response->redirect($this->helper->url->to('CustomGlobalFilterController', 'index'), true);
                return;
            } else {
                $this->flash->failure(t('Unable to create your custom filter.'));
            }
        }
    
        $this->create($values, $errors);
    }

    /**
     * Confirmation dialog before removing a custom filter
     *
     * @access public
     */
    public function confirm()
    {
        $filter_id = $this->request->getIntegerParam('filter_id');
        $filter = $this->customGlobalFilterModel->getById($filter_id);
        $this->response->html($this->template->render('custom_global_filter/remove', array(
            'filter' => $filter,
            'title' => t('Remove a custom filter')
        )));
    }

    /**
     * Remove a custom filter
     *
     * @access public
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $filter_id = $this->request->getIntegerParam('filter_id');

        // Retrieve the custom filter from the model
        $filter = $this->customGlobalFilterModel->getById($filter_id);

        if (empty($filter)) {
            $this->response->json(array('message' => 'Filter not found'), 404);
        }

        // Perform any additional permission checks here if necessary

        // Remove the custom filter
        if ($this->customGlobalFilterModel->remove($filter_id)) {
            $this->flash->success(t('Custom filter removed successfully'));
        } else {
            $this->flash->failure(t('Failed to remove custom filter'));
        }

        // Redirect to the DashboardController
        $this->response->redirect($this->helper->url->to('DashboardController', 'show'));
    }

    /**
     * Edit a custom filter (display the form)
     *
     * @access public
     * @param  array $values
     * @param  array $errors
     * @throws AccessForbiddenException
     * @throws \Kanboard\Core\Controller\PageNotFoundException
     */
    public function edit(array $values = array(), array $errors = array())
    {
        $filter = $this->customGlobalFilterModel->getById($this->request->getIntegerParam('filter_id'));

        $this->response->html($this->template->render('custom_global_filter/edit', array(
            'values' => empty($values) ? $filter : $values,
            'errors' => $errors,
            'filter' => $filter,
            'title' => t('Edit custom filter')
        )));
    }

    /**
     * Edit a custom filter (validate the form and update the database)
     *
     * @access public
     */
    public function update()
    {
        $filterId = $this->request->getIntegerParam('filter_id');
        $filter = $this->customGlobalFilterModel->getById($filterId);

        $values = $this->request->getValues();
        $values['id'] = $filterId;

        if (! isset($values['is_shared'])) {
            $values += array('is_shared' => 0);
        }

        if (! isset($values['append'])) {
            $values += array('append' => 0);
        }

        // Retrieve selected user IDs from the form values
        $selectedUsernamesId = isset($values['users']) ? $values['users'] : [];
        if (isset($values['users'])) {
            unset($values['users']);
        }

        // Retrieve selected group IDs from the form values
        $selectedGroupsId = isset($values['groups']) ? $values['groups'] : [];
        if (isset($values['groups'])) {
            unset($values['groups']);
        }

        list($valid, $errors) = $this->customGlobalFilterValidator->validateModification($values);

        if ($valid) {
            if ($this->customGlobalFilterModel->update($values)) {
                // Save the filter for selected users
                $this->saveForSelectedUsersAndGroups($values, $selectedUsernamesId, $selectedGroupsId);

                $this->flash->success(t('Your custom filter has been updated successfully.'));
                $this->response->redirect($this->helper->url->to('CustomGlobalFilterController', 'index'), true);
                return;
            } else {
                $this->flash->failure(t('Unable to update custom filter.'));
            }
        }

        $this->edit($values, $errors);
    }

    /**
     * Save the custom filter for all selected users and groups
     *
     * @access private
     * @param array $values            Form values (including filter details)
     * @param array $selectedUserIds   Array of selected user IDs
     * @param array $selectedGroupIds  Array of selected group IDs
     * @return void
     */
    private function saveForSelectedUsersAndGroups(array $values, array $selectedUserIds, array $selectedGroupIds)
    {
        $currentUserId = $this->userSession->getId();
        
        $selectedUserIds = array_filter($selectedUserIds);
        $selectedGroupIds = array_filter($selectedGroupIds);
        
        // Retrieve users for each selected group
        foreach ($selectedGroupIds as $groupId) {
            $groupMembers = $this->groupMemberModel->getMembers($groupId);
            foreach ($groupMembers as $member) {
                $selectedUserIds[] = $member['id'];
            }
        }

        // Ensure unique user IDs
        $selectedUserIds = array_unique($selectedUserIds);

        // Unset values that shouldn't be duplicated
        unset($values['id']);
        unset($values['is_shared']);
        unset($values['append']);

        // Create filter for each user
        foreach ($selectedUserIds as $selectedUserId) {
            if ($selectedUserId != $currentUserId) {
                $userValues = $values; // Create a copy of $values for each user
                $userValues['user_id'] = $selectedUserId;
                $this->customGlobalFilterModel->create($userValues);
            }
        }
    }


}
