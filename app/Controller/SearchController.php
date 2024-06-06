<?php

namespace Kanboard\Controller;

use Kanboard\Filter\TaskProjectsFilter;
use Kanboard\Model\TaskModel;

/**
 * Search Controller
 *
 * @package  Kanboard\Controller
 * @author   Frederic Guillot
 */
class SearchController extends BaseController
{
    public function index()
    {
        $projects = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());
        $search = urldecode($this->request->getStringParam('search'));
        $nb_tasks = 0;
        $limit = $this->userSession->getDashboardMaxItemsPerPage();
        $user = $this->getUser();

        $paginator = $this->paginator
                ->setUrl('SearchController', 'index', array('search' => $search))
                ->setMax($limit)
                ->setOrder(TaskModel::TABLE.'.id')
                ->setDirection('DESC');

        if ($search !== '' && ! empty($projects)) {
            $paginator
                ->setFormatter($this->taskListFormatter)
                ->setQuery($this->taskLexer
                    ->build($search)
                    ->withFilter(new TaskProjectsFilter(array_keys($projects)))
                    ->getQuery()
                )
                ->calculate();

            $nb_tasks = $paginator->getTotal();
        }

        $this->response->html($this->helper->layout->app('search/index', array(
            'values' => array(
                'search' => $search,
                'controller' => 'SearchController',
                'action' => 'index',
                'custom_global_filters' => $this->customGlobalFilterModel->getAll($user['id']),
            ),
            'paginator' => $paginator,
            'title' => t('Search tasks').($nb_tasks > 0 ? ' ('.$nb_tasks.')' : ''),
            'custom_global_filters' => $this->customGlobalFilterModel->getAll($user['id']),
        )));
    }

    public function activity()
    {
        $search = urldecode($this->request->getStringParam('search'));
        $events = $this->helper->projectActivity->searchEvents($search);
        $nb_events = count($events);

        $this->response->html($this->helper->layout->app('search/activity', array(
            'values' => array(
                'search' => $search,
                'controller' => 'SearchController',
                'action' => 'activity',
            ),
            'title' => t('Search in activity stream').($nb_events > 0 ? ' ('.$nb_events.')' : ''),
            'nb_events' => $nb_events,
            'events' => $events,
        )));
    }
}
