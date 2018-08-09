<?php

class Clients{

    const TABLE = 'wd_cu_clientes';

    const RELATED = 'wd_cu_sucursales';

  static function add($name){
    global $wpdb;

    return $wpdb->insert(self::TABLE, array('nombre_cliente' => $name), array('%s') );
  }

  static function addSucursal($cliente_id, $sucursal_id){
    global $wpdb;

    $values = array( 'cliente_id' => $cliente_id,
                     'direccion_real' => $sucursal,
                     'direccion_publica' => $sucursal );

    $types = array( '%d', '%s', '%s' );

    return $wpdb->insert(self::RELATED, $values, $types);
  }

  static function updateSucursalFeature($params, $cliente_id, $sucursal_id){
    global $wpdb;

    $fields = [];
    $types = [];


    foreach ($params as $key => $value) {
      $types[] = ($key == 'direccion_publica') ? '%s' : '%d';
    }

    return $wpdb->update(self::RELATED, $params, ['id' => $sucursal_id, 'cliente_id' => $cliente_id], $types, ['%d', '%d']);
  }

  static function getAll(){
    global $wpdb;

    $queryStr = 'SELECT * FROM '. self::TABLE .' ORDER BY cliente_id ASC';
    return $wpdb->get_results($queryStr, ARRAY_A);
  }

  static function getByName($name){
    global $wpdb;

    $queryStr = 'SELECT * FROM '. self::TABLE .' WHERE nombre_cliente=%s';
    $query = $wpdb->prepare($queryStr, array($name));
    return $wpdb->get_results($query, ARRAY_A);
  }

  static function getSucursalesByClient($id){
    global $wpdb;

    $queryStr = 'SELECT * FROM '. self::TABLE;
    $queryStr.= ' RIGHT JOIN '. self::RELATED .' ON '. self::TABLE .'.cliente_id='. self::RELATED .'.cliente_id';
    $queryStr.= ' WHERE '. self::TABLE .'.cliente_id=%d';

    $query = $wpdb->prepare($queryStr, array($id));
    return $wpdb->get_results($query, ARRAY_A);
  }

  static function getSucursales(){
    global $wpdb;

    $queryStr = 'SELECT * FROM '. self::TABLE;
    $queryStr.= ' RIGHT JOIN '. self::RELATED .' ON '. self::TABLE .'.cliente_id='. self::RELATED .'.cliente_id';

    return $wpdb->get_results($queryStr, ARRAY_A);
  }



}
