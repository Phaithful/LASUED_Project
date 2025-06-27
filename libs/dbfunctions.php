<?php
@session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("dbcnx.inc.php");
require_once("validation.php");

class dbobject extends validation
{
	/** @var mysqli */
    public mysqli $myconn;

    public function __construct()
    {
        $cnx = new dbcnx();
        $this->myconn = $cnx->connect(); // ✅ Now this is a mysqli object
    }

    public function db_query($sql, $object = true)
    {
        $result = mysqli_query($this->myconn, $sql);

        if (!$result) {
            error_log("SQL ERROR: " . mysqli_error($this->myconn));
            return false;
        }

        if ($object) {
            $data = array();
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } else {
            return mysqli_affected_rows($this->myconn);
        }
    }

    public function doInsert($table, $arr, $exclude = [])
    {
        $fields = "";
        $values = "";

        foreach ($arr as $key => $value) {
            if (!in_array($key, $exclude)) {
                $fields .= "`$key`,";
                $values .= "'" . mysqli_real_escape_string($this->myconn, $value) . "',";
            }
        }

        $fields = rtrim($fields, ',');
        $values = rtrim($values, ',');

        $sql = "INSERT INTO `$table` ($fields) VALUES ($values)";
        file_put_contents('m_query.txt', $sql); // for debugging

        return $this->db_query($sql, false);
    }
}
