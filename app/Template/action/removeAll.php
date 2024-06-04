<div class="page-header">
    <h2><?= t('Remove all automatic actions') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to remove all automatic actions from this project?') ?>
    </p>

    <?= $this->modal->confirmButtons(
        'ActionController',
        'removeAll',
        array('project_id' => $project['id'])
    ) ?>
</div>