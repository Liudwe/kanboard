<?php

namespace Kanboard\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Model\ColumnModel;
use Kanboard\Model\TaskModel;

/**
 * Filter tasks by column
 *
 * @package filter
 * @author  Frederic Guillot
 */
class TaskColumnFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('column');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        if (is_int($this->value) || ctype_digit((string) $this->value)) {
            $this->query->eq(TaskModel::TABLE.'.column_id', $this->value);
        } else {
            $trimmedValue = trim($this->value);
            $this->query->ilike(ColumnModel::TABLE.'.title', '%'.$trimmedValue.'%');
        }

        return $this;
    }
}
