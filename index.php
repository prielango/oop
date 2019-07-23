<?php

class FileDB
{
    private $fileName;
    private $data;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return object
     */
    public function load()
    {
        $this->data = null;
        if (file_exists($this->fileName)) {
            $data = json_decode(file_get_contents($this->fileName), true);
            if ($data !== false) {
                $this->data = $data;
            }
        }
        return $this;
    }

    /**
     * @return integer
     */
    public function save()
    {
        return file_put_contents($this->fileName, json_encode($this->data));
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data ?? $this->load()->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * 
     * @param string $table_name
     * @return boolean
     */
    public function tableExists($table_name)
    {
        return isset($this->data[$table_name]);
    }

    /**
     * @param string $table_name
     * @param string $row_id
     * @return boolean
     */
    public function rowExists($table_name, $row_id)
    {
        return isset($this->data[$table_name][$row_id]);
    }

    /**
     * @param string $table_name
     * @param string $row_id
     * @param string $row
     * @return boolean
     */
    public function rowInsertIfNotExists($table_name, $row_id, $row) {
        if ($this->rowExists($table_name, $row_id)) {
            return false;
        }
        return $this->insertRow($table_name, $row_id, $row);
    }

    /**
     * @param string $table_name
     * @param string $row_id
     * @param string|null $row
     * @return boolean
     */
    public function insertRow($table_name, $row_id = null, $row)
    {
        if ($this->tableExists($table_name)) {
            if ($row_id) {
                $this->data[$table_name][$row_id] = $row;
            } else {
                $this->data[$table_name][] = $row;
            }

            return true;
        }

        return false;
    }
}
