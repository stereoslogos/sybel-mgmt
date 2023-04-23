<?php 

/**
 * La primera función de pruebas del curso de creando el framework MVC
 *
 * @return void
 */
function en_custom() {
  return 'ESTOY DENTRO DE CUSTOM_FUNCTIONS.';
}

/**
 * Carga las diferentes divisas soporatadas en el proyecto de pruebas
 *
 * @return void
 */
function get_coins() {
  return 
  [
    'MXN',
    'USD',
    'CAD',
    'EUR',
    'ARS',
    'AUD',
    'JPY'
  ];
}

function get_project_status()
{
  return
  [
    ['draft', 'Draft'],
    ['out', 'Out of target'],
    ['progress', 'In progress'],
    ['done', 'Done'],
    ['idea', 'Idea'],
    ['unknown', 'Unknown']
  ];
}

function format_project_status($status){
  $text = '';
  $classes = '';
  $icon = '';
  $placeholder = '<span class="%s"><i class="%s"></i> %s</span>';

  switch ($status) {
    case 'draft':
      $text = 'Draft';
      $classes = 'badge bg-secondary text-dark';
      $icon = 'fas fa-eraser';
      break;
    case 'out':
      $text = 'Out of target';
      $classes = 'badge bg-danger text-dark';
      $icon = 'fas fa-times';
      break;
    case 'progress':
      $text = 'In progress';
      $classes = 'badge bg-warning text-dark';
      $icon = 'fas fa-clock';
      break;
    case 'done':
      $text = 'Done';
      $classes = 'badge bg-success text-dark';
      $icon = 'fas fa-check';
      break;
    case 'idea':
      $text = 'Idea';
      $classes = 'badge bg-light text-dark';
      $icon = 'fas fa-lightbulb';
      break;
    default:
      $text = 'Unknown';
      $classes = 'badge bg-info text-dark';
      $icon = 'fas fa-question-circle';
  }
  return sprintf($placeholder, $classes, $icon, $text);
}

function get_task_type(){
  return[
    ['meeting', 'Meeting'],
    ['document', 'Document'],
    ['media', 'Media'],
    ['web', 'Web'],
    ['money', 'Money']
  ];
}

function format_task_type($task_type)
{
  $placeholder = '<i class="%s"></i>';
  $icon = '';

  switch ($task_type) {
    case 'meeting':
      $icon = 'fas fa-calendar';
      break;
      
    case 'media':
      $icon = 'fas fa-camera';
      break;
      
      case 'web':
      $icon = 'fas fa-globe';
      break;
    
    case 'money':
      $icon = 'fas fa-money-bill';
      break;
    
    default:
      $icon = 'fas fa-file';
      break;
  }
  return sprintf($placeholder, $icon);
}

function check_project_status($id_project)
{
  if(!$project = projectModel::by_id($id_project)) return false;

  //Check empty tasks
  if(empty($project['sub'])){
    projectModel::update(projectModel::$t1, ['id' => $id_project], ['status' => 'draft']);
    return true;
  }

  //Iterate tasks
  $tasks = $project['sub'];
  $status = $project['status'];
  $total_tasks = count($tasks);
  $ready = 0;
  $pending = 0;

  foreach ($tasks as $t) {
    if($t['status'] === 'progress'){
      $pending++;
    }else{
      $ready++;
    }
  }

  if($total_tasks == $ready && $status === 'draft'){
    projectModel::update(projectModel::$t1, ['id' => $id_project], ['status' => 'done']);
  }else if($total_tasks != $ready && $status === 'done'){
    projectModel::update(projectModel::$t1, ['id' => $id_project], ['status' => 'draft']);
  }

  return true;
}