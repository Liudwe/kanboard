<div class="page-header">
    <h2><?= t('Add new priority name') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('PriorityNameController', 'save') ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <?= $this->form->label(t('Priority name'), 'name') ?>
    <?= $this->form->text('name', $values, $errors, array('autofocus', 'required', 'maxlength="191"')) ?>

    <?= $this->form->label(t('Priority Number'), 'priority_number') ?>
    <?= $this->form->number('priority_number', $values, $errors, array('required', 'min="0"')) ?>

    <?= $this->modal->submitButtons() ?>
</form>
