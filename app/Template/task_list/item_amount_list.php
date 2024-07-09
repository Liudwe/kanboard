<div class="dropdown">
    <!-- Items per page dropdown -->
    <a href="#" class="dropdown-menu dropdown-menu-link-icon">
        <strong><?= t('Items per page') ?> <i class="fa fa-caret-down"></i></strong>
    </a>
    <ul>
        <li><?= $this->url->link('5', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 5,)) ?></li>
        <li><?= $this->url->link('10', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 10)) ?></li>
        <li><?= $this->url->link('15', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 15)) ?></li>
        <li><?= $this->url->link('20', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 20)) ?></li>
        <li><?= $this->url->link('30', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 30)) ?></li>
        <li><?= $this->url->link('40', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 40)) ?></li>
        <li><?= $this->url->link('50', 'ItemsPerPageController', 'setItemsPerPage', array('max' => 50)) ?></li>
        <!-- Add more options as needed -->
    </ul>
</div>