<?php

namespace Kanboard\Controller;

/**
 * Dashboard Controller
 */
class ItemsPerPageController extends BaseController
{
    public function setItemsPerPage()
    {
        $limit = $this->request->getIntegerParam('max', 15);
        $this->userSession->setDashboardMaxItemsPerPage($limit);
        
        // Get the URL parameter
        $url = $this->request->getStringParam('url', '');
        
        // Redirect to the saved URL
        $this->response->redirect($url);
    }
}