<?php

namespace Kanboard\Model;

use Kanboard\Core\Base;

/**
 * Class PriorityModel
 *
 * @package Kanboard\Model
 * @author  Your Name
 */
class PriorityModel extends Base
{
    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'priority';

    /**
     * Get all priorities
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        return $this->db->table(self::TABLE)->asc('priority_number')->findAll();
    }

    /**
     * Get one priority
     *
     * @access public
     * @param  integer $priority_id
     * @return array|null
     */
    public function getById($priority_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $priority_id)->findOne();
    }

    /**
 * Get one priority by priority number
 *
 * @access public
 * @param  integer $priorityNumber
 * @return array|null
 */
public function getByPriorityNumber($priority_number)
{
    return $this->db->table(self::TABLE)->eq('priority_number', $priority_number)->findOne();
}

    /**
     * Return true if the priority exists
     *
     * @access public
     * @param  string  $name
     * @param  integer $priority_id
     * @return boolean
     */
    public function exists($name, $priority_id = 0)
    {
        return $this->db
            ->table(self::TABLE)
            ->neq('id', $priority_id)
            ->ilike('name', $name)
            ->asc('priority')
            ->exists();
    }

/**
 * Create a new priority
 *
 * @access public
 * @param  string  $name
 * @param  integer $priority_number
 * @return bool|int
 */
public function create($name, $priority_number)
{
    return $this->db->table(self::TABLE)->persist(array(
        'name' => $name,
        'priority_number' => $priority_number,
    ));
}

    /**
     * Update a priority
     *
     * @access public
     * @param  integer $priority_id
     * @param  string  $name
     * @param  integer $priority_number
     * @return bool
     */
    public function update($priority_id, $name, $priority_number)
    {
        return $this->db->table(self::TABLE)->eq('id', $priority_id)->update(array(
            'name' => $name,
            'priority_number' => $priority_number,
        ));
    }

    /**
     * Remove a priority
     *
     * @access public
     * @param  integer $priority_id
     * @return bool
     */
    public function remove($priority_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $priority_id)->remove();
    }
}
