<?php

class Clients{

    const TABLE = 'wd_cu_clientes';

    const RELATED = 'wd_cu_sucursales';

  static function add($params){
    global $wpdb;
    $values = array();

    foreach ( $params as $key => $value )
      $values[] = $wpdb->prepare( "(%s)", $value['nombre_cliente']);

    $query = "INSERT INTO " .Access::TABLE. " (nombre_cliente) VALUES ";
    $query .= implode( ",\n", $values );

    return $wpdb->query($query);
  }

  static function getAll(){
    global $wpdb;

    $queryStr = 'SELECT * FROM '. self::TABLE .' ORDER BY cliente_id ASC';
    return $wpdb->get_results($queryStr, ARRAY_A);
  }

  static function getSucursalesByClient($id){
    global $wpdb;

    $queryStr = 'SELECT * FROM '. self::TABLE;
    $queryStr.= ' LEFT JOIN '. self::RELATED .' ON '. self::TABLE .'.cliente_id='. self::RELATED .'.cliente_id';
    $queryStr.= ' WHERE '. self::TABLE .'.cliente_id=%d';

    $query = $wpdb->prepare($queryStr, array($id));
    return $wpdb->get_results($query, ARRAY_A);
  }

  static function getSucursales(){
    global $wpdb;

    $queryStr = 'SELECT * FROM '. self::TABLE;
    $queryStr.= ' LEFT JOIN '. self::RELATED .' ON '. self::TABLE .'.cliente_id='. self::RELATED .'.cliente_id';

    return $wpdb->get_results($queryStr, ARRAY_A);
  }
}
