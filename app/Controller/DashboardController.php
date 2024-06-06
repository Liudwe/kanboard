<?php

namespace Kanboard\Controller;

/**
 * Dashboard Controller
 *
 * @package  Kanboard\Controller
 * @author   Frederic Guillot
 */
class DashboardController extends BaseController
{
    /**
     * Dashboard overview
     *
     * @access public
     */
    public function show()
    {
        $user = $this->getUser();
        $limit = $this->userSession->getDashboardMaxItemsPerPage();


        $this->response->html($this->helper->layout->dashboard('dashboard/overview', array(
            'title'              => t('Dashboard for %s', $this->helper->user->getFullname($user)),
            'user'               => $user,
            'overview_paginator' => $this->dashboardPagination->getOverview($user['id'], $limit),
            'project_paginator'  => $this->projectPagination->getDashboardPaginator($user['id'], 'show', $limit),
            'custom_global_filters' => $this->customGlobalFilterModel->getAll($user['id']),
        )));
    }

    /**
     * My tasks
     *
     * @access public
     */
    public function tasks()
    {
        $user = $this->getUser();
        $limit = $this->userSession->getDashboardMaxItemsPerPage();
        $this->response->html($this->helper->layout->dashboard('dashboard/tasks', array(
            'title' => t('Tasks overview for %s', $this->helper->user->getFullname($user)),
            'paginator' => $this->taskPagination->getDashboardPaginator($user['id'], 'tasks', $limit),
            'user' => $user,
        )));
    }

    /**
     * My subtasks
     *
     * @access public
     */
    public function subtasks()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/subtasks', array(
            'title' => t('Subtasks overview for %s', $this->helper->user->getFullname($user)),
            'paginator' => $this->subtaskPagination->getDashboardPaginator($user['id']),
            'user' => $user,
            'nb_subtasks' => $this->subtaskModel->countByAssigneeAndTaskStatus($user['id']),
        )));
    }

    /**
     * My projects
     *
     * @access public
     */
    public function projects()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/projects', array(
            'title' => t('Projects overview for %s', $this->helper->user->getFullname($user)),
            'paginator' => $this->projectPagination->getDashboardPaginator($user['id'], 'projects', $limit),
            'user' => $user,
        )));
    }
}
