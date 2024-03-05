<div class="page-header">
    <h2><?= t('Remove a priority name') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to remove this priority name: "%s"?', $priority['name']) ?>
    </p>

    <?= $this->modal->confirmButtons(
        'PriorityNameController',
        'remove',
        array('priority_id' => $priority['id'])
    ) ?>
</div>
