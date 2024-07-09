<?php

namespace Kanboard\Controller;

/**
 * Dashboard Controller
 */
class ItemsPerPageController extends BaseController
{
    public function setItemsPerPage()
    {
        $task = $this->getTask();
        $limit = $this->request->getIntegerParam('max', 15);
        $this->userSession->setDashboardMaxItemsPerPage($limit);
                
        $this->redirectAfterItemsPerPageChange($task);
    }

    protected function redirectAfterItemsPerPageChange(array $task)
    {
        switch ($this->request->getStringParam('redirect')) {
            case 'list':
                $this->response->redirect($this->helper->url->to('TaskListController', 'show', ['project_id' => $task['project_id']]));
                break;
            case 'dashboard':
                $this->response->redirect($this->helper->url->to('DashboardController', 'show', [], 'project-tasks-'.$task['project_id']));
                break;
            case 'dashboard-tasks':
                $this->response->redirect($this->helper->url->to('DashboardController', 'tasks', ['user_id' => $this->userSession->getId()]));
                break;
        }
    }
}