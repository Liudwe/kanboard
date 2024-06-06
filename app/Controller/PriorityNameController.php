<?php

namespace Kanboard\Controller;

use Kanboard\Core\Controller\AccessForbiddenException;

/**
 * Class PriorityNameController
 *
 * @package Kanboard\Controller
 * @author  Frederic Guillot
 */
class PriorityNameController extends BaseController
{
    public function index()
    {
        $this->response->html($this->helper->layout->config('priority/index', array(
            'priorities' => $this->priorityModel->getAll(),
            'title' => t('Settings').' &gt; '.t('Global priority management'),
        )));
    }

    public function create(array $values = array(), array $errors = array())
    {
        $this->response->html($this->template->render('priority/create', array(
            'values' => $values,
            'errors' => $errors,
        )));
    }

    public function save()
    {
        $values = $this->request->getValues();
    
        // Basic validation
        if (empty($values['name']) || !is_numeric($values['priority_number'])) {
            $this->flash->failure(t('Invalid data provided.'));
            $this->response->redirect($this->helper->url->to('PriorityNameController', 'create'));
            return;
        }
    
        // Perform the save operation
        if ($this->priorityModel->create($values['name'], $values['priority_number']) > 0) {
            $this->flash->success(t('Priority name created successfully.'));
        } else {
            $this->flash->failure(t('Unable to create this priority name.'));
        }
    
        $this->response->redirect($this->helper->url->to('PriorityNameController', 'index'));
    }

    public function edit(array $values = array(), array $errors = array())
    {
        $priority_id = $this->request->getIntegerParam('priority_id');
        $priority = $this->priorityModel->getById($priority_id);

        if (empty($values)) {
            $values = $priority;
        }

        $this->response->html($this->template->render('priority/edit', array(
            'priority' => $priority,
            'values' => $values,
            'errors' => $errors,
        )));
    }

    public function update()
    {
        $priority_id = $this->request->getIntegerParam('priority_id');
        $priority = $this->priorityModel->getById($priority_id);
        $values = $this->request->getValues();
    
        // Basic validation
        if (empty($values['name']) || !ctype_digit($values['priority_number'])) {
            $this->flash->failure(t('Invalid data provided.'));
            $this->response->redirect($this->helper->url->to('PriorityNameController', 'edit', array('priority_id' => $priority_id)));
            return;
        }
        if ($this->priorityModel->update($priority_id, $values['name'], $values['priority_number'])) {
            $this->flash->success(t('Priority updated successfully.'));
        } else {
            $this->flash->failure(t('Unable to update this priority.'));
        }
    
        $this->response->redirect($this->helper->url->to('PriorityNameController', 'index'));
    }

    public function confirm()
    {
        $priority_id = $this->request->getIntegerParam('priority_id');
        $priority = $this->priorityModel->getById($priority_id);

        $this->response->html($this->template->render('priority/remove', array(
            'priority' => $priority,
        )));
    }

    public function remove()
    {
        $this->checkCSRFParam();
        $priority_id = $this->request->getIntegerParam('priority_id');
        $priority = $this->priorityModel->getById($priority_id);

        if ($this->priorityModel->remove($priority_id)) {
            $this->flash->success(t('Priority removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this priority.'));
        }

        $this->response->redirect($this->helper->url->to('PriorityNameController', 'index'));
    }
}
