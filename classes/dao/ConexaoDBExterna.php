<?php

class ConexaoDBExterna {

	const SERVIDOR = "192.168.0.100";
	const USUARIO  = "";
	const SENHA    = "";
	const DATABASE = "iteia_geral";

	static private $instance;
	private $conexao;
	private $sql_result;
	private $manual_addslashes = 1;

	static public function singleton() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	protected function criaConexao() {
		$this->conexao = mysql_pconnect(self::SERVIDOR, self::USUARIO, self::SENHA);
		mysql_select_db(self::DATABASE, $this->conexao);
	}

	public function executaQuery($sql) {
		if (!$this->conexao) {
			$this->criaConexao();
		}
		if (!$this->conexao) {
			return false;
		}
		$this->sql_result = mysql_query($sql, $this->conexao);
		return $this->sql_result;
	}

	public function fetchArray($qresult = 0) {
		if (!$qresult) {
			$qresult = $this->sql_result;
		}
		if ($qresult) {
			return mysql_fetch_array($qresult);
		} else {
			return false;
		}
	}

	public function fetchObject($qresult = 0) {
		if (!$qresult) {
			$qresult = $this->sql_result;
		}
		if ($qresult) {
			return mysql_fetch_object($qresult);
		} else {
			return false;
		}
	}

	public function numRows($qresult = 0) {
		if (!$qresult) {
	    	$qresult = $this->sql_result;
		}
		if ($qresult) {
	    	return mysql_num_rows($qresult);
		} else {
	    	return false;
		}
    }

	public function insertId() {
		return mysql_insert_id($this->conexao);
	}
	
	public function sql_select($get, $tbl, $where="", $limit="", $order="") {
        $query = "SELECT $get FROM $tbl";
        if ($where != "") {
            $query .= " WHERE ".$where;
        }
        if ($order != "") {
            $query .= " ORDER BY ".$order;
        }
        if ($limit != "") {
            $query .= " LIMIT ".$limit;
        }
        $qresult = $this->executaQuery($query);
        return $qresult;
    }

    public function sql_update($tbl, $arr, $where="") {
        $dba = $this->compile_db_update_string($arr);
        $query = "UPDATE $tbl SET $dba";
        if ($where) {
            $query .= " WHERE ".$where;
        }
        $qresult = $this->executaQuery($query);
        return $qresult;
    }

    public function sql_insert($tbl, $arr) {
        $dba = $this->compile_db_insert_string($arr);
        $query = "INSERT INTO $tbl ({$dba['FIELD_NAMES']}) VALUES({$dba['FIELD_VALUES']})";
        $qresult = $this->executaQuery($query);
        return $qresult;
    }

    public function sql_delete($tbl, $where) {
        $query = "DELETE FROM $tbl";
        if ($where) {
            $query .= " WHERE $where";
        }
        $qresult = $this->executaQuery($query);
        return $qresult;
    }
    
    private function compile_db_insert_string($data) {
        $field_names  = "";
        $field_values = "";
        foreach($data as $k => $v) {
            if (!$this->manual_addslashes) {
                $v = preg_replace( "/'/", "\\'", $v );
            }
            $field_names .= "$k,";
            if (is_numeric($v) and intval($v) == $v) {
                $field_values .= $v.",";
            } else {
                $field_values .= "'$v',";
            }
        }
        $field_names  = preg_replace("/,$/", "", $field_names);
        $field_values = preg_replace("/,$/", "", $field_values);
        return array('FIELD_NAMES'  => $field_names, 'FIELD_VALUES' => $field_values);
    }

    private function compile_db_update_string($data) {
        $return_string = "";
        foreach($data as $k => $v) {
            if (!$this->manual_addslashes) {
                $v = preg_replace( "/'/", "\\'", $v );
            }
            if (is_numeric($v) and intval($v) == $v) {
                $return_string .= $k . "=".$v.",";
            } else {
                $return_string .= $k . "='".$v."',";
            }
        }
        $return_string = preg_replace( "/,$/" , "" , $return_string );
        return $return_string;
    }

}