<div class="page-header">
    <h2><?= t('Priority names') ?></h2>
    <ul>
        <li>
            <?= $this->modal->medium('plus', t('Add new priority name'), 'PriorityNameController', 'create') ?>
        </li>
    </ul>
</div>

<?php if (empty($priorities)): ?>
    <p class="alert"><?= t('There is no priority names at the moment.') ?></p>
<?php else: ?>
    <table class="table-striped table-scrolling">
        <tr>
            <th class="column-60"><?= t('Priority name') ?></th>
            <th class="column-20"><?= t('Priority') ?></th>
            <th><?= t('Action') ?></th>
        </tr>
        <?php foreach ($priorities as $priority): ?>
            <tr>
                <td><?= $this->text->e($priority['name']) ?></td>
                <td><?= $this->text->e($priority['priority_number'] ?? '') ?></td>
                <td>
                    <?= $this->modal->medium('edit', t('Edit'), 'PriorityNameController', 'edit', array('priority_id' => $priority['id'])) ?>
                    <?= $this->modal->confirm('trash-o', t('Remove'), 'PriorityNameController', 'confirm', array('priority_id' => $priority['id'])) ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
<?php endif ?>
