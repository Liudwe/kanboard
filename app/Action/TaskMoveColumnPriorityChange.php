<?php

namespace Kanboard\Action;

use Kanboard\Model\TaskModel;

/**
 * Move a task to another column when the priority is changed
 *
 * @package Kanboard\Action
 * @author  Francois Ferrand
 */
class TaskMoveColumnPriorityChange extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Move the task to another column when the priority is changed');
    }

    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(
            TaskModel::EVENT_UPDATE,
        );
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array(
            'priority' => t('When priority is changed to:'),
            'src_column_id' => t('Source column'),
            'dest_column_id' => t('To destination column'),
        );
    }

    /**
     * Get the required parameters for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return array(
            'task_id',
            'task' => array(
                'project_id',
                'column_id',
                'category_id',
                'position',
                'swimlane_id',
                'priority',
            )
        );
    }

    /**
     * Execute the action (move the task to another column)
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $priority = $this->getParam('priority');
        $originalColumnId = $this->getParam('src_column_id');
        $destColumnId = $this->getParam('dest_column_id');
        
        if ($data['task']['priority'] == $priority && $data['task']['column_id'] == $originalColumnId) {
            return $this->taskPositionModel->movePosition(
                $data['task']['project_id'],
                $data['task_id'],
                $destColumnId,
                $data['task']['position'],
                $data['task']['swimlane_id'],
                false
            );
        }
        return false;
    }

    /**
     * Check if the event data meet the action condition
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        $priority = $this->getParam('priority');
        $originalColumnId = $this->getParam('src_column_id');
        return $data['task']['priority'] == $priority && $data['task']['column_id'] == $originalColumnId;
    }
}
