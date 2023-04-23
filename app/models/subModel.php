<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de sub
 */
class subModel extends Model {
  public static $t1   = 'sub'; // Nombre de la tabla en la base de datos;
  
  // Nombre de tabla 2 que talvez tenga conexión con registros
  //public static $t2 = '__tabla 2___'; 
  //public static $t3 = '__tabla 3___'; 

  function __construct()
  {
    // Constructor general
  }
  
  static function all()
  {
    // Todos los registros
    $sql = 'SELECT * FROM sub ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM sub WHERE id = :id LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }

  static function by_project($id_project)
  {
    $sql = 'SELECT * FROM sub WHERE id_project = :id_project ORDER BY orden ASC';
    return($rows = parent::query($sql, ['id_project' => $id_project])) ? $rows : [];
  }
}

