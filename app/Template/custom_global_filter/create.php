<div class="page-header">
    <h2><?= t('Add a new filter') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('CustomGlobalFilterController', 'save') ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <?= $this->form->label(t('Name'), 'name') ?>
    <?= $this->form->text('name', $values, $errors, array('autofocus', 'required')) ?>

    <?= $this->form->label(t('Filter'), 'filter') ?>
    <?= $this->form->text('filter', $values, $errors, array('required')) ?>

    <?= $this->form->checkbox('is_shared', t('Share with all project members'), 1) ?>
    <?= $this->form->checkbox('append', t('Append filter (instead of replacement)'), 1) ?>

    <?= $this->modal->submitButtons() ?>
</form>