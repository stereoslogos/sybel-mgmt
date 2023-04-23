<?php 

class ajaxController extends Controller {
  
  private $accepted_actions = ['add', 'get', 'load', 'update', 'post', 'put', 'delete', 'options'];
  private $required_params  = ['hook', 'action'];

  function __construct()
  {
    foreach ($this->required_params as $param) {
      if(!isset($_POST[$param])) {
        json_output(json_build(403));
      }
    }

    if(!in_array($_POST['action'], $this->accepted_actions)) {
      json_output(json_build(403));
    }
  }

  function index()
  {
    /**
    200 OK
    201 Created
    300 Multiple Choices
    301 Moved Permanently
    302 Found
    304 Not Modified
    307 Temporary Redirect
    400 Bad Request
    401 Unauthorized
    403 Forbidden
    404 Not Found
    410 Gone
    500 Internal Server Error
    501 Not Implemented
    503 Service Unavailable
    550 Permission denied
    */
    json_output(json_build(403));
  }

  function project_form()
  {
    try {
      $id = (int) $_POST['id'];
      $title = clean($_POST['title']);
      $status = clean($_POST['status']);
      $description = clean($_POST['description']);

      $data = [
        'title' => $title,
        'status' => $status,
        'description' => $description
      ];

      if(!projectModel::update(projectModel::$t1, ['id' => $id], $data)) {
        json_output(json_build(400, null, 'There was an error updating the project. Please try again.'));
      }
  
      // se guardó con éxito
      $project = projectModel::by_id($id);
      json_output(json_build(200, $project, 'Project updated successfully.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function add_task_form()
  {
    try {
      $id_project = (int) $_POST['id_project'];
      $title = clean($_POST['title']);
      $content = clean($_POST['content']);
      $type = clean($_POST['type']);
      $orden = 0;

      if(strlen($title) <3){
        json_output(json_build(400, null, 'The title should have at least 4 characters. Please try again.'));
      }
      
      if(!$project = projectModel::by_id($id_project)){
        json_output(json_build(400, null, 'The project does not exist. Please try again.'));
      }

      if(!empty($project['sub'])){
        $last_task = end($project['sub']);
        $last_order = $last_task['orden'];
        $orden = $last_order + 1;
      }

      $data = [
        'id_project' => $id_project,
        'title' => $title,
        'content' => $content,
        'type' => $type,
        'status' => 'pending',
        'orden' => $orden,
        'created' => now(),
        'updated' => now()
      ];

      if(!$id_task = subModel::add(subModel::$t1, $data)) {
        json_output(json_build(400, null, 'There was an error creating the task. Please try again.'));
      }
  
      // se guardó con éxito
      $project = projectModel::by_id($id_project);
      json_output(json_build(201, $project, 'Task added successfully.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function get_tasks()
  {
    try {
      $id = clean($_POST['id']);
      $data = get_module('listTasks', projectModel::by_id($id));
      json_output(json_build(200, $data));
    } catch(Exception $e) {
      json_output(json_build(400, $e->getMessage()));
    }

  }

  function delete_task()
  {
    try {
      $id_task = (int) $_POST['id'];

      if(!$task = subModel::by_id($id_task)) {
        throw new Exception('Task not found.');
      }

      if(!subModel::remove(subModel::$t1, ['id' => $id_task], 1)) {
        json_output(json_build(400, null, 'There was an error removing the task.'));
      }

      check_project_status($task['id_project']);

      json_output(json_build(200, null, 'Task removed succesfully.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function bee_update_movement()
  {
    try {
      $movement     = new movementModel;
      $movement->id = $_POST['id'];
      $mov          = $movement->one();

      if(!$mov) {
        json_output(json_build(400, null, 'No existe el movimiento'));
      }

      $data = get_module('updateForm', $mov);
      json_output(json_build(200, $data));
    } catch(Exception $e) {
      json_output(json_build(400, $e->getMessage()));
    }
  }

  function bee_save_movement()
  {
    try {
      $mov              = new movementModel();
      $mov->id          = $_POST['id'];
      $mov->type        = $_POST['type'];
      $mov->description = $_POST['description'];
      $mov->amount      = (float) $_POST['amount'];
      if(!$mov->update()) {
        json_output(json_build(400, null, 'Hubo error al guardar los cambios'));
      }
  
      // se guardó con éxito
      json_output(json_build(200, $mov->one(), 'Movimiento actualizado con éxito'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function bee_save_options()
  {
    $options =
    [
      'use_taxes' => $_POST['use_taxes'],
      'taxes'     => (float) $_POST['taxes'],
      'coin'      => $_POST['coin']
    ];

    foreach ($options as $k => $option) {
      try {
        if(!$id = optionModel::save($k, $option)) {
          json_output(json_build(400, null, sprintf('Hubo error al guardar la opción %s', $k)));
        }
    
        
      } catch (Exception $e) {
        json_output(json_build(400, null, $e->getMessage()));
      }
    }

    // se guardó con éxito
    json_output(json_build(200, null, 'Opciones actualizadas con éxito'));
  }

  function open_update_task_form()
  {
    try {
      $id = $_POST['id'];
      if(!$task = subModel::by_id($id)){
        throw new PDOException('Unable to find task.');
      }
      json_output(json_build(200, $task));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function update_task_form()
  {
    try {
      $id = (int) $_POST['id'];
      $title = clean($_POST['u_title']);
      $type = clean($_POST['type']);
      $content = clean($_POST['content']);

      $data = [
        'title' => $title,
        'type' => $type,
        'content' => $content
      ];

      if(!subModel::update(subModel::$t1, ['id' => $id], $data)) {
        json_output(json_build(400, null, 'There was an error updating the task. Please try again.'));
      }
  
      // se guardó con éxito
      $task = subModel::by_id($id);
      json_output(json_build(200, $task, 'Task updated successfully.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function save_new_order()
  {
    try {
      if(!isset($_POST['tasks'])){
        throw new Exception('There was an error in the request.');
      }

      if(empty($_POST['tasks'])){
        throw new Exception('No tasks to update.');
      }

      foreach ($_POST['tasks'] as $t) {
        if(!subModel::update(subModel::$t1, ['id' => $t['id']], ['orden' => $t['index']])) {
          continue;
        }
      }

      json_output(json_build(200, null, 'Tasks ordered successfully.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function update_task_status()
  {
    try {
      $id = clean($_POST['id']);

      if(!$task = subModel::by_id($id)) {
        throw new PDOException('Task not found.');
      }

      $status = $task['status'];
      if($status === 'done'){
        $status = 'pending';
      }else{
        $status = 'done';
      }

      if(!subModel::update(subModel::$t1, ['id' => $id], ['status' => $status])) {
        throw new PDOException('Task status not updated.');
      }

      check_project_status($task['id_project']);

      $task = subModel::by_id($id);

      json_output(json_build(200, $task, 'Task status updated successfully.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function open_project_form()
  {
    try {
      $id = $_POST['id'];
      if(!$task = subModel::by_id($id)){
        throw new PDOException('Unable to find task.');
      }
      json_output(json_build(200, $task));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }
}