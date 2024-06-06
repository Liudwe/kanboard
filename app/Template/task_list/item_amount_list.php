<?php
// Save the current URL
$currentUrl = $_SERVER['REQUEST_URI'];
?>

<div class="dropdown">
    <!-- Items per page dropdown -->
    <a href="#" class="dropdown-menu dropdown-menu-link-icon">
        <strong><?= t('Items per page') ?> <i class="fa fa-caret-down"></i></strong>
    </a>
    <ul>
        <li><?= $this->url->link('5', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 5, 'url' => $currentUrl)) ?></li>
        <li><?= $this->url->link('10', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 10, 'url' => $currentUrl)) ?></li>
        <li><?= $this->url->link('15', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 15, 'url' => $currentUrl)) ?></li>
        <li><?= $this->url->link('20', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 20, 'url' => $currentUrl)) ?></li>
        <li><?= $this->url->link('30', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 30, 'url' => $currentUrl)) ?></li>
        <li><?= $this->url->link('40', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 40, 'url' => $currentUrl)) ?></li>
        <li><?= $this->url->link('50', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 50, 'url' => $currentUrl)) ?></li>
        <!-- Add more options as needed -->
    </ul>
</div>