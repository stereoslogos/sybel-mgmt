<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de projects
 */
class projectsController extends Controller {
  function __construct()
  {
    // Validación de sesión de usuario, descomentar si requerida
    if (!Auth::validate()) {
      Flasher::new('You must be logged in first.', 'danger');
      Redirect::to('login');
    }
  }
  
  function index()
  {
    Redirect::to('home');
  }

  function see($id)
  {
    if(!$project = projectModel::by_id($id)){
      Flasher::new('Project not found.', 'danger');
      Redirect::to('home');
    }
    $data = 
    [
      'title' => sprintf('Project #%s', $project['number']),
      'p' => $project,
      'bg'    => 'dark'
    ];

    View::render('see', $data);
  }

  function add()
  {
    try {
      $data = [
        'number' => rand(111111, 999999),
        'title' => '',
        'description' => '',
        'status' => 'draft',
        'created' => now(),
        'updated' => now()
      ];

      if(!$id = projectModel::add(projectModel::$t1, $data)){
        throw new PDOException('Could not add project.');
      }
      Redirect::to(sprintf('projects/see/%s', $id));

    } catch (PDOException $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::to('home');
    }
  }

  function post_agregar()
  {

  }

  function editar($id)
  {
    View::render('editar');
  }

  function post_editar()
  {

  }

  function delete($id)
  {
    // Proceso de borrado
    try {
      if (!$project = projectModel::by_id($id)) {
        throw new Exception ('Project not found.');
      }

      $sql = 'DELETE t1, t2 FROM project t1 LEFT JOIN sub t2 ON t1.id = t2.id_project WHERE t1.id = :id';

      if (!projectModel::query($sql, ['id' => $id])){
        throw new Exception('Error deleting the project.');
      }

      Flasher::new(sprintf('Project <b>#%s</b> successfully deleted.', $project['number']), 'success');
      Redirect::back();

    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }
}