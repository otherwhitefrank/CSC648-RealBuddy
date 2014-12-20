<?php

class DBQueryHandler
{

    protected $dbManager;

    function __construct()
    {
        $this->dbManager = DatabaseManager::getInstance();
    }

    protected function queryAndFree($query)
    {
      $sqlresult = $this->dbManager->query($query);
      if ($sqlresult != FALSE) 
      {
        $this->free($sqlresult);
        return TRUE;
      }
      return FALSE;
    }

    protected function query($query)
    {
        return $sqlresult = $this->dbManager->query($query);
    }

    protected function queryFirstAndFree($query)
    {
        $sqlresult = $this->dbManager->query($query);
        $ret = $this->getFirstObjectOfResult($sqlresult);
        $this->free($sqlresult);
        return $ret;
    }

    protected function free($obj)
    {
        if (is_a($obj, "mysqli_result")) {
            mysqli_free_result($obj);
        }
    }

    /**
     * This method will check if the query result
     * has a valid user. If so it'll return the first user.
     * @param type $queryResult
     * @return type
     */
    private function getFirstObjectOfResult($queryResult)
    {
        if ($queryResult != null && $queryResult->num_rows > 0) {
            return $queryResult->fetch_object();
        } else {
            return null;
        }
    }

    protected function getLastId($id, $table)
    {
        $query = "Select max(" . $id . ") from " . $table;
        error_log($query);
        $res = $this->query($query);
        $row = mysqli_fetch_array($res);
        $index = "max(" . $id .")";
        $return = $row[$index];
        $this->free($res);
        return $return;
    }

}
