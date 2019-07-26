<?php
//pure connection library

class mySQLAccessObject {
  private $link ;
  private $host ;
  private $user ;
  private $password ;
  private $database = null ;

  protected function __construct($d1,$d2,$d3,$d4) 
  {
    $this->host = $d1 ;
    $this->user = $d2 ;
    $this->password = $d3 ;
    $this->database = $d4 ;
    $this->link = ($GLOBALS["__mysqli_ston"] = mysqli_connect($this->host,$this->user,$this->password,$this->database)) ;

    $this->link->set_charset("UTF8") ;

    if ( mysqli_connect_errno() ) {
      echo "ERROR connect to MySQL : ". mysqli_connect_error() ;
      return false ;
    }

  }

  public function __destruct()
  {
    if ( ! isset($this->link) ) mysqli_close($this->link) ;
  }

  public function get_select ($db_table,$positions="*",$where="",$order="",$limit="") {
    if ( empty($db_table) ) return false ;
    $string_query = "SELECT {$positions} FROM {$db_table}" ;
    if ( !empty($where) ) $string_query = $string_query . " where " . $where ;
    if ( !empty($order) ) $string_query = $string_query . " ORDER BY " . $order ;
    if ( !empty($limit) ) $string_query = $string_query . " " . $limit ;
    if ( $result = $this->link->query($string_query) )
      while ( $row = $result->fetch_assoc() ) $output[] = $row ;
    else return false ;
    if ( isset($output) )return $output ;
    else return null ;
  }

  public function insert_to($db_table,$fields,$values) {
    if ( empty($db_table) || empty($fields) || empty($values)) return false ;
    $query = "INSERT INTO {$db_table} {$fields} VALUES {$values}" ;
    $result = $this->link->query($query) ;
    return $result ;
  }

  public function delete_data($db_table,$values) {
    if ( empty($db_table) || empty($values) ) return false ;
    $query = "DELETE FROM {$db_table} WHERE {$values}" ;
    $result = $this->link->query($query) ;
    return $result ;
  }

  public function update_data($db_table,$set_values,$find_values) {
    if ( empty($db_table) || empty($set_values) || empty($find_values) ) return false ;
    $query = "UPDATE {$db_table} SET {$set_values} WHERE {$find_values}" ;
    $result = $this->link->query($query) ;
    
    return $result ;
  }

  public function create_table($tname,$fields) {
    if ( empty($tname) || empty($fields) ) return false ;
    $query = 'CREATE TABLE ' . $tname . '(' . $fields . ') ENGINE=InnoDB  DEFAULT CHARSET=utf8' ;
    $result = $this->link->query($query) ;
    return $result ;
  }

}

//for the singleton
class mySQL_connection extends mySQLAccessObject {
  private static $object_connect ;

  private function __construct() {
    $object_connect = null ;
  }

  private function __clone() {
    return ;
  }

  public static function get_connected($host,$user,$password,$db) {
    if ( !isset(self::$object_connect)) {
      self::$object_connect = new mySQLAccessObject($host,$user,$password,$db) ;
      if ( isset(self::$object_connect) ) return self::$object_connect ;
    }
    return self::$object_connect ;
  }

  public static function stop_connected() {
    if ( isset(self::$object_connect) ) self::$object_connect = null ;
  }


}

function string_legalize($string) {
  $result = "" ;
  $s = trim($string) ;
  $i = 0 ;
  if ( $s[0] == "'" ) $i++ ;
  for ( $i ; $i < strlen($s) ; $i++ ) {
    if ( $s[$i] == "'" || $s[$i] == '"' || $s[$i] == "!" ) $result .= "\\" ;
    else if ( $s[$i] == " " ) $i++ ;
    $result .= $s[$i] ;
  }
  return $result ;
}

function rand_string($length) {
  $string = "" ;
  $source = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ;
  for ( $i=0 ; $i <$length ; $i++) $string .= $source[rand(0,61)] ;
  return $string ;
}

function isStringLegal($string) {
  if ( empty($string) ) return true ;
  for ( $i=0 ; $i < strlen($string) ; $i++ ) if ( $string[$i] == '"' || $string[$i] == "'" || $string[$i] == "+" || $string[$i] == "|" || $string[$i] == "&" || $string[$i] == "!") return false ; 
  return true ;
}

function getEmailAccount($string) {
  if ( empty($string) ) return null ;
  for ( $i= 0 ; $i < strlen($string) ; $i++ ) if ( $string[$i] == "@" ) break ;
  return substr($string,0,$i) ;
}

//Suppose time1 happened after time2
//some bug still exist, do not use
function getTimeInterval($time1,$time2) {
  $result = '' ;
  $tem = 0 ;
  $interval = (strtotime($time1)-strtotime($time2)) ;
  $interval = floor($interval / 60) ;
  if ( $interval < 1440 ) {
    $tem = $interval % 60 ;
    $interval = floor($interval / 60) ;
    if ( $interval <= 0 ) $result = $tem . "分鐘" ;
    else $result = $interval . "小時" . $tem . "分鐘" ;
  }else if ( $interval < 43200 ) {
    $tem = floor(($interval % 1440)/24) ;
    $interval = floor($interval / 1440) ;
    if ( $interval <= 0 ) $result = $tem . "小時" ;
    else $result = $interval . "天" . $tem . "小時" ;
  }else {
    $tem = floor($interval / 43200) ;
    $interval = floor($interval / 525600) ;
    if ( $interval <= 0 ) $result = $tem . "月" ;
    else $result = $interval . "年" . $tem . "月" ;
  }
  return $result ;
}

?>