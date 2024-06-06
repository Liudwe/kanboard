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

    /**
     * Save a new custom filter
     *
     * @access public
     */
    public function save()
    {
        $values = $this->request->getValues();
        $values['user_id'] = $this->userSession->getId();

        list($valid, $errors) = $this->customGlobalFilterValidator->validateCreation($values);

        if ($valid) {
            if ($this->customGlobalFilterModel->create($values) !== false) {
                $this->flash->success(t('Your custom filter has been created successfully.'));
                $this->response->redirect($this->helper->url->to('DashboardController', 'show'));
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
        $filter = $this->customGlobalFilterModel->getById($this->request->getIntegerParam('filter_id'));

        $values = $this->request->getValues();
        $values['id'] = $filter['id'];

        if (! isset($values['is_shared'])) {
            $values += array('is_shared' => 0);
        }

        if (! isset($values['append'])) {
            $values += array('append' => 0);
        }

        list($valid, $errors) = $this->customGlobalFilterValidator->validateModification($values);

        if ($valid) {
            if ($this->customGlobalFilterModel->update($values)) {
                $this->flash->success(t('Your custom filter has been updated successfully.'));
                $this->response->redirect($this->helper->url->to('CustomGlobalFilterController', 'index'), true);
                return;
            } else {
                $this->flash->failure(t('Unable to update custom filter.'));
            }
        }

        $this->edit($values, $errors);
    }
}
