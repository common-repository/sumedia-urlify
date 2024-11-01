<?php

namespace Sumedia\Urlify\Base;

abstract class Repository
{
    /**
     * @var int
     */
    protected $row_count;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var array
     */
    protected $orders = [];

    /**
     * @return string
     */
    abstract public function get_table_name();

    /**
     * @param array $data
     * @return mixed
     */
    abstract public function is_valid_data($data);

    /**
     * @param int $row_count
     * @param int $offset
     */
    public function set_limit($row_count, $offset)
    {
        if (!is_numeric($row_count)) {
            throw new \RuntimeException(__('"row_count" is not numeric', 'sumedia-base'));
        }

        if (!is_numeric($offset)) {
            throw new \RuntimeException(__('"offset" is not numeric', 'sumedia-base'));
        }

        $this->row_count = $row_count;
        $this->offset = $offset;
    }

    /**
     * @param array $orders
     */
    public function set_order(array $orders)
    {
        $this->orders = $orders;
    }

    /**
     * @param string $id
     * @return string
     */
    protected function quote_identifier($id)
    {
        return '`' . esc_sql($id) . '`';
    }

    /**
     * @param string $table_name
     * @param string $fields
     * @return string
     */
    protected function get_select($table_name, $fields = '*')
    {
        return "SELECT * FROM " . $this->quote_identifier($table_name);
    }

    /**
     * @param string $query
     * @param string $field
     * @return string
     */
    protected function get_where($query, $field)
    {
        $query .= " WHERE " . $this->quote_identifier($field) . " = '%s'";
        return $query;
    }

    /**
     * @param string $query
     * @param string $field
     * @return string
     */
    protected function get_like($query, $field)
    {
        $query .= " WHERE " . $this->quote_identifier($field) . " LIKE '%s'";
        return $query;
    }

    /**
     * @param string $query
     * @return false|string
     */
    protected function get_order_by($query)
    {
        if (!empty($this->orders)) {
            $query .= " ORDER BY ";
            foreach ($this->orders as $field => $direction)
            {
                $query .= $this->quote_identifier($field) . ' ' . esc_sql($direction) . ", ";
            }
            $query = substr($query, -2);
        }
        return $query;
    }

    /**
     * @param string $query
     * @return string
     */
    protected function get_limit($query)
    {
        if ($this->row_count) {
            $query .= " LIMIT " . $this->row_count;
            if ($this->offset) {
                $query .= " OFFSET " . $this->offset;
            }
        }
        return $query;
    }

    /**
     * @param string $field
     * @param string $value
     * @param string $search
     * @return array
     */
    public function find($field, $value, $like = false)
    {
        if (!is_string($value)) {
            throw new RuntimeException(__('Given value is not a string', 'sumedia-base'));
        }

        if (!is_string($field)) {
            throw new RuntimeException(__('Given field is not a string', 'sumedia-base'));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . $this->get_table_name();

        $query = $this->get_select($table_name);
        if ($like) {
            $query = $this->get_like($query, $field);
        } else {
            $query = $this->get_where($query, $field);
        }
        $query = $this->get_order_by($query);
        $query = $this->get_limit($query);
        if ($like) {
            $prepare = $wpdb->prepare($query, '%' . $wpdb->esc_like($value) . '%');
        } else {
            $prepare = $wpdb->prepare($query, $value);
        }

        return $wpdb->get_results($prepare, ARRAY_A);
    }

    /**
     * @param string $field
     * @param string $value
     * @param bool $like
     * @return array
     */
    public function findOne($field, $value, $like = false)
    {
        $results = $this->find($field, $value, $like);
        if (empty($results)) {
            return;
        }
        if (count($results) > 1) {
            throw new RuntimeException(__('There is more than one result for findOne', 'sumedia-base'));
        }
        return current($results);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        if (!is_numeric($id)) {
            throw new RuntimeException(__('Given id is not in correct type', 'sumedia-base'));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . $this->get_table_name();
        $query = "DELETE FROM " . $this->quote_identifier($table_name) . " WHERE `id` = %d";
        $prepare = $wpdb->prepare($query, $id);
        $wpdb->query($prepare);
    }

    /**
     * @param array $data
     */
    public function update(array $data)
    {
        $id = array_shift($data);
        if (!is_numeric($id)) {
            throw new RuntimeException(__('Given id is not in correct type', 'sumedia-base'));
        }

        if (!$this->is_valid_data($data)) {
            throw new RuntimeException(__('Got invalid data', 'sumedia-base'));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . $this->get_table_name();
        $query = "UPDATE " . $this->quote_identifier($table_name). " SET ";
        foreach ($data as $key => $value) {
            $query .= $this->quote_identifier($key) . ' = "%s",';
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE `id` = %s";
        $prepare = $wpdb->prepare($query, ...array_values(array_merge($data, [$id])));
        $wpdb->query($prepare);
    }

    /**
     * @param array $data
     */
    public function create(array $data)
    {
        if (!$this->is_valid_data($data)) {
            throw new RuntimeException(__('Got invalid data', 'sumedia-base'));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . $this->get_table_name();
        $query = "INSERT INTO " . $this->quote_identifier($table_name). " SET ";
        foreach ($data as $key => $value) {
            $query .= $this->quote_identifier($key) . ' = "%s",';
        }
        $query = substr($query, 0, -1);

        $prepare = $wpdb->prepare($query, ...array_values($data));
        $wpdb->query($prepare);
    }
}
