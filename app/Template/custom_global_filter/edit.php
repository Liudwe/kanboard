<div class="page-header">
    <h2><?= t('Edit custom filter') ?></h2>
</div>

<form method="post" action="<?= $this->url->href('CustomGlobalFilterController', 'update', array('filter_id' => $filter['id'])) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('user_id', $values) ?>

    <?= $this->form->label(t('Name'), 'name') ?>
    <?= $this->form->text('name', $values, $errors, array('autofocus', 'required')) ?>

    <?= $this->form->label(t('Filter'), 'filter') ?>
    <?= $this->form->text('filter', $values, $errors, array('required')) ?>

    <?= $this->form->checkbox('append', t('Append filter (instead of replacement)'), 1, $values['append'] == 1) ?>

    <?= $this->modal->submitButtons() ?>
</form>
