<?php
namespace App\Plugin;


trait ModelHelper
{

    function columns() {

        $app = \Slim\Slim::getInstance();

        $columns = [];

        $result = $app->db->select("SHOW COLUMNS FROM $this->table");

        foreach ($result as $field) {

            $columns[] = $field['Field'];
        }

        return $columns;
    }

    function bind(array $data) {

        foreach ($this->columns() as $col) {

            if (isset($data[$col]))
                $this->{$col} = $data[$col];
        }
    }
}