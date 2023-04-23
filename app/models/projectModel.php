<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de project
 */
class projectModel extends Model {
  public static $t1   = 'project'; // Nombre de la tabla en la base de datos;
  
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
    $sql = 'SELECT * FROM project ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function all_paginated()
  {
    // Todos los registros
    $sql = 'SELECT t1.*,
    (SELECT COUNT(ta.id) FROM sub ta WHERE ta.id_project = t1.id) AS total_tasks
    FROM project t1
    ORDER BY t1.id DESC';
    return PaginationHandler::paginate($sql);
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM project WHERE id = :id LIMIT 1';
    $rows = parent::query($sql, ['id' => $id]);

    if(!$rows) return [];

    //if does exist
    $rows = $rows[0];

    $rows['sub'] = subModel::by_project($id);
    
    return $rows;
  }
}

