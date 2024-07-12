<?php

namespace Kanboard\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Model\TaskModel;

/**
 * Filter tasks by leader
 *
 * @package filter
 * @author  Your Name
 */
class TaskLeaderFilter extends BaseFilter implements FilterInterface
{
/**
     * Current user id
     *
     * @access private
     * @var int
     */
    private $currentUserId = 0;

    /**
     * Set current user id
     *
     * @access public
     * @param  integer $userId
     * @return TaskAssigneeFilter
     */
    public function setCurrentUserId($userId)
    {
        $this->currentUserId = $userId;
        return $this;
    }

    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('leader');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return string
     */
    public function apply()
    {
        if (is_int($this->value) || ctype_digit((string) $this->value)) {
            $this->query->eq(TaskModel::TABLE.'.leader_id', $this->value);
        } else {
            switch ($this->value) {
                case 'me':
                    $this->query->eq(TaskModel::TABLE.'.leader_id', $this->currentUserId);
                    break;
                case 'nobody':
                    $this->query->eq(TaskModel::TABLE.'.leader_id', 0);
                    break;
                case 'anybody':
                    $this->query->gt(TaskModel::TABLE.'.leader_id', 0);
                    break;
                default:
                    $this->query->beginOr();
                    $this->query->ilike('ul.username', '%'.$this->value.'%');
                    $this->query->ilike('ul.name', '%'.$this->value.'%');
                    $this->query->closeOr();
            }
        }
    }
}