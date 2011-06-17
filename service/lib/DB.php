<?php
require_once dirname(__FILE__)."/../config/conf.php";

class DB
{
	private $db_connection = null;
    private static $instance;
    
    private function __construct($host, $user, $pass, $db)
    {
		$this->db_connection = mysql_connect($host, $user, $pass);
		if(!$this->db_connection)
		{
			throw new Exception('Could not connect: ' . mysql_error($this->db_connection));
		}
		$this->selectDb($db);
		mysql_set_charset('utf8', $this->db_connection);
    }

	public function selectDb($db)
	{
		mysql_select_db($db, $this->db_connection);
	}

	public function startTransaction()
	{
		mysql_query("SET AUTOCOMMIT=0", $this->db_connection);
		mysql_query("START TRANSACTION", $this->db_connection);
	}

	public function rollback()
	{
		mysql_query("ROLLBACK", $this->db_connection);
	}

	public function commit()
	{
		mysql_query("COMMIT", $this->db_connection);
	}

	public function getLastId()
	{
		return mysql_insert_id($this->db_connection);
	}

	public function getRowsAssocArray($query)
	{
		$rows = array();
		$result = mysql_query($query, $this->db_connection);
		if (!$result)
		{
			throw new Exception('SQL error: ' . mysql_error($this->db_connection));
		}
		if (mysql_num_rows($result) != 0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$rows[] = $row;
			}
		}
		mysql_free_result($result);
		return $rows;
	}
	public function getNumArray($query)
	{
		$rows = array();
		$result = mysql_query($query, $this->db_connection);
		if (!$result)
		{
			throw new Exception('SQL error: ' . mysql_error($this->db_connection));
		}
		if (mysql_num_rows($result) != 0)
		{
			while ($row = mysql_fetch_row($result))
			{
				$rows[] = $row;
			}
		}
		mysql_free_result($result);
		return $rows;
	}

	public function getValue($query)
	{
		$res = null;
		$result = mysql_query($query, $this->db_connection);
		if (!$result)
		{
			throw new Exception('SQL error: ' . mysql_error($this->db_connection));
		}
		if (mysql_num_rows($result) != 0)
		{
			$row = mysql_fetch_row($result);
			$res = $row[0];
		}
		mysql_free_result($result);
		return $res;
	}
	public function getAffectedRows($query)
	{
		$res = 0;
		$result = mysql_query($query, $this->db_connection);
		if (!$result)
		{
			throw new Exception('SQL error: ' . mysql_error($this->db_connection));
		}
		$res = mysql_affected_rows($this->db_connection);
		return $res;
	}

    public static function &singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
        }
        return self::$instance;
    }
    
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
}
?>
