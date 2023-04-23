$(document).ready(function() {

  // Toast para notificaciones
  //toastr.warning('My name is Inigo Montoya. You killed my father, prepare to die!');

  // Waitme
  //$('body').waitMe({effect : 'orbit'});
  
  /**
   * Alerta para confirmar una acción establecida en un link o ruta específica
   */
  $('body').on('click', '.confirmar', function(e) {
    e.preventDefault();

    let url = $(this).attr('href'),
    ok      = confirm('¿Estás seguro?');

    // Redirección a la URL del enlace
    if (ok) {
      window.location = url;
      return true;
    }
    
    console.log('Acción cancelada.');
    return true;
  });

  /**
   * Inicializa summernote el editor de texto avanzado para textareas
   */
  if ($('.summernote').length !== 0) {
    $('.summernote').summernote({
      placeholder: 'Escribe en este campo...',
      tabsize: 2,
      height: 300
    });
  }

  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  ///////// NO REQUERIDOS, SOLO PARA EL PROYECTO DEMO DE GASTOS E INGRESOS
  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  
  // Agregar un movimiento
  $('.bee_add_movement').on('submit', bee_add_movement);
  function bee_add_movement(event) {
    event.preventDefault();

    var form    = $('.bee_add_movement'),
    hook        = 'bee_hook',
    action      = 'add',
    data        = new FormData(form.get(0)),
    type        = $('#type').val(),
    description = $('#description').val(),
    amount      = $('#amount').val();
    data.append('hook', hook);
    data.append('action', action);

    // Validar que este seleccionada una opción type
    if(type === 'none') {
      toastr.error('Selecciona un tipo de movimiento válido', '¡Upss!');
      return;
    }

    // Validar description
    if(description === '' || description.length < 5) {
      toastr.error('Ingresa una descripción válida', '¡Upss!');
      return;
    }

    // Validar amount
    if(amount === '' || amount <= 0) {
      toastr.error('Ingresa un monto válido', '¡Upss!');
      return;
    }

    // AJAX
    $.ajax({
      url: 'ajax/bee_add_movement',
      type: 'post',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 201) {
        toastr.success(res.msg, '¡Bien!');
        form.trigger('reset');
        bee_get_movements();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  // Cargar movimientos
  bee_get_movements();
  function bee_get_movements() {
    var wrapper = $('.bee_wrapper_movements'),
    hook        = 'bee_hook',
    action      = 'load';

    if (wrapper.length === 0) {
      return;
    }

    $.ajax({
      url: 'ajax/bee_get_movements',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action
      },
      beforeSend: function() {
        wrapper.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        wrapper.html(res.data);
      } else {
        toastr.error(res.msg, '¡Upss!');
        wrapper.html('');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
      wrapper.html('');
    }).always(function() {
      wrapper.waitMe('hide');
    })
  }

  // Actualizar un movimiento
  $('body').on('dblclick', '.bee_movement', bee_update_movement);
  function bee_update_movement(event) {
    var li              = $(this),
    id                  = li.data('id'),
    hook                = 'bee_hook',
    action              = 'get',
    add_form            = $('.bee_add_movement'),
    wrapper_update_form = $('.bee_wrapper_update_form');

    // AJAX
    $.ajax({
      url: 'ajax/bee_update_movement',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        wrapper_update_form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        wrapper_update_form.html(res.data);
        add_form.hide();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      wrapper_update_form.waitMe('hide');
    })
  }

  $('body').on('submit', '.bee_save_movement', bee_save_movement);
  function bee_save_movement(event) {
    event.preventDefault();

    var form    = $('.bee_save_movement'),
    hook        = 'bee_hook',
    action      = 'update',
    data        = new FormData(form.get(0)),
    type        = $('select[name="type"]', form).val(),
    description = $('input[name="description"]', form).val(),
    amount      = $('input[name="amount"]', form).val(),
    add_form            = $('.bee_add_movement');
    data.append('hook', hook);
    data.append('action', action);

    // Validar que este seleccionada una opción type
    if(type === 'none') {
      toastr.error('Selecciona un tipo de movimiento válido', '¡Upss!');
      return;
    }

    // Validar description
    if(description === '' || description.length < 5) {
      toastr.error('Ingresa una descripción válida', '¡Upss!');
      return;
    }

    // Validar amount
    if(amount === '' || amount <= 0) {
      toastr.error('Ingresa un monto válido', '¡Upss!');
      return;
    }

    // AJAX
    $.ajax({
      url: 'ajax/bee_save_movement',
      type: 'post',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, '¡Bien!');
        form.trigger('reset');
        form.remove();
        add_form.show();
        bee_get_movements();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  // Borrar un movimiento
  $('body').on('click', '.bee_delete_movement', bee_delete_movement);
  function bee_delete_movement(event) {
    var boton   = $(this),
    id          = boton.data('id'),
    hook        = 'bee_hook',
    action      = 'delete',
    wrapper     = $('.bee_wrapper_movements');

    if(!confirm('¿Estás seguro?')) return false;

    $.ajax({
      url: 'ajax/bee_delete_movement',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        wrapper.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, 'Bien!');
        bee_get_movements();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      wrapper.waitMe('hide');
    })
  }

  // Guardar o actualizar opciones
  $('.bee_save_options').on('submit', bee_save_options);
  function bee_save_options(event) {
    event.preventDefault();

    var form = $('.bee_save_options'),
    data     = new FormData(form.get(0)),
    hook     = 'bee_hook',
    action   = 'add';
    data.append('hook', hook);
    data.append('action', action);

    // AJAX
    $.ajax({
      url: 'ajax/bee_save_options',
      type: 'post',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200 || res.status === 201) {
        toastr.success(res.msg, '¡Bien!');
        bee_get_movements();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  ///Update project/////////////////////////////////////////////////////////////
  $('#project_form').on('submit', project_form);
  function project_form(e) {
    e.preventDefault();

    var form    = $(this),
    hook        = 'bee_hook',
    action      = 'post',
    data        = new FormData(form.get(0));

    data.append('hook', hook);
    data.append('action', action);
    
    // AJAX
    $.ajax({
      url: 'ajax/project_form',
      type: 'POST',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, 'Good!');
      } else {
        toastr.error(res.msg, 'Opss!');
      }
    }).fail(function(err) {
      toastr.error('There was an error in the request!', 'Opss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }
///New task////////////////
  $('#add_task_form').on('submit', add_task_form);
  function add_task_form(e) {
    e.preventDefault();

    var form    = $(this),
    hook        = 'bee_hook',
    action      = 'post',
    data        = new FormData(form.get(0));

    data.append('hook', hook);
    data.append('action', action);
    
    // AJAX
    $.ajax({
      url: 'ajax/add_task_form',
      type: 'POST',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 201) {
        toastr.success(res.msg, 'Good!');
        form.trigger('reset');
        get_tasks();
      } else {
        toastr.error(res.msg, 'Opss!');
      }
    }).fail(function(err) {
      toastr.error('There was an error in the request!', 'Opss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  ///Load tasks//////////////////////////////////////////// 
  get_tasks();
  function get_tasks() {
    var wrapper = $('.wrapper_tasks'),
    id = wrapper.data('id'),
    hook        = 'bee_hook',
    action      = 'get';

    if (wrapper.length === 0) {
      return;
    }

    $.ajax({
      url: 'ajax/get_tasks',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        wrapper.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        wrapper.html(res.data);
        init_tasks_acordion();
        re_enumerate();
      } else {
        toastr.error(res.msg, 'Opss!');
        wrapper.html(res.msg);
      }
    }).fail(function(err) {
      toastr.error('There was an error processing the request', 'Opss!');
      wrapper.html('There was an error loading the tasks. Try again later.');
    }).always(function() {
      wrapper.waitMe('hide');
    })
  }

  function init_tasks_acordion(){
    $( "#accordion" )
    .accordion({
        header: "> div > h3",
        collapsible: true
    })
    .sortable({
        axis: "y",
        handle: "h3",
        stop: function( event, ui ) {
        // IE doesn't register the blur when sorting
        // so trigger focusout handlers to remove .ui-state-focus
        ui.item.children( "h3" ).triggerHandler( "focusout" );

        // Refresh accordion to handle new order
        $( this ).accordion( "refresh" );
        save_new_order();
        }
    });
  }

  function save_new_order(){
    var acordion = $('#accordion'),
    divs = $('.group', acordion),
    tasks = [],
    action = 'put',
    hook = 'bee_hook',
    wrapper = $('.wrapper_tasks');

    divs.map(function(i, task){
      var task = {'index': i, 'id': task.getAttribute('data-id')};
      tasks.push(task);
    });

    // AJAX
    $.ajax({
      url: 'ajax/save_new_order',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data : {action, hook, tasks},
      beforeSend: function() {
        wrapper.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, 'Good!');
        re_enumerate();

      } else {
        toastr.error(res.msg, 'Opss!');
      }
    }).fail(function(err) {
      toastr.error('There was an error in the request!', 'Opss!');
    }).always(function() {
      wrapper.waitMe('hide');
    })
  }

  function re_enumerate() {
    var acordion = $('#accordion'),
    divs = $('.group', acordion);

    divs.each(function(i, task_numb) {
      var h3 = $('h3', task_numb);
      
      $('span.numeracion', h3).html('-' + (i + 1) + ' ');
    })
  }
  ///Delete task////////////////
  $('body').on('click', '.delete_task', delete_task);
  function delete_task(e) {
    var boton   = $(this),
    id          = boton.data('id'),
    hook        = 'bee_hook',
    action      = 'delete';

    if(!confirm('Do you really want to delete this task?')) return false;

    $.ajax({
      url: 'ajax/delete_task',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        $('body').waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, 'Good!');
        get_tasks();
      } else {
        toastr.error(res.msg, 'Opss!');
      }
    }).fail(function(err) {
      toastr.error('There was an error processing the request.', 'Opss!');
    }).always(function() {
      $('body').waitMe('hide');
    })
  }

  ///Load task info////////////////////////////////
  $('body').on('click', '.open_update_task_form', open_update_task_form);
  function open_update_task_form(e){
    e.preventDefault();

    var button = $(this),
    id = button.data('id'),
    action = 'get',
    hook = 'bee_hook',
    form_a = $('#add_task_form'),
    form = $('#update_task_form');

    $.ajax({
      url: 'ajax/open_update_task_form',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        $('body').waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        $('[name="id"]', form).val(res.data.id);
        $('[name="u_title"]', form).val(res.data.title);
        $('[name="content"]', form).val(res.data.content);
        $('[name="type"]', form).val(res.data.type);

        form_a.closest('.card').fadeOut('fluid');
        form.closest('.card').fadeIn();

      } else {
        toastr.error(res.msg, 'Opss!');
      }
    }).fail(function(err) {
      toastr.error('There was an error processing the request', 'Opss!');
    }).always(function() {
      $('body').waitMe('hide');
    })
  }

  ///Load PROJECT info////////////////////////////////
  $('body').on('click', '.open_project_form', open_project_form);
  function open_project_form(e){
    e.preventDefault();

    var button = $(this),
    id = button.data('id'),
    action = 'get',
    hook = 'bee_hook',
    form = $('#project_form');

    $.ajax({
      url: 'ajax/open_project_form',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        $('body').waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        $('[name="status"]', form).val(res.data.status);
        form.closest('.card').fadeIn();

      } else {
        toastr.error(res.msg, 'Opss!');
      }
    }).fail(function(err) {
      toastr.error('There was an error processing the request', 'Opss!');
    }).always(function() {
      $('body').waitMe('hide');
    })
  }

  ///Cancel task editing/////////////////////////////
  $('.cancel_update_task').on('click', cancel_update_task);
  function cancel_update_task(e){
    e.preventDefault();

    var button = $(this),
    form_a = $('#add_task_form'),
    form = $('#update_task_form');

    form.trigger('reset');
    form.closest('.card').fadeOut();
    form_a.closest('.card').fadeIn();
    return true;
  }
  
  ///Update task/////////////////////////////////////////////////////////////
  $('#update_task_form').on('submit', update_task_form);
  function update_task_form(e) {
    e.preventDefault();

    var form    = $(this),
    form_a = $('#add_task_form'),
    data        = new FormData(form.get(0));

    if(!confirm('Are you sure you want to update this task?')) return;
    
    // AJAX
    $.ajax({
      url: 'ajax/update_task_form',
      type: 'POST',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, 'Good!');
        get_tasks();
        form.closest('.card').fadeOut();
        form.trigger('reset');
        form_a.closest('.card').fadeIn();
      } else {
        toastr.error(res.msg, 'Opss!');
      }
    }).fail(function(err) {
      toastr.error('There was an error in the request!', 'Opss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  $('body').on('click', '.update_task_status', update_task_status);
  function update_task_status(e){
    e.preventDefault();

    var button = $(this),
    id = button.data('id'),
    action = 'put',
    hook = 'bee_hook';

    if(!confirm('Are you sure you want to update the status for this task?')) return;
    
    // AJAX
    $.ajax({
      url: 'ajax/update_task_status',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data : {action, hook, id},
      beforeSend: function() {
        button.closest('.group').waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, 'Good!');

        if(res.data.status === 'pending'){
          button.removeClass('btn-success text-light').addClass('btn-warning text-dark');
        }else{
          button.removeClass('btn-warning text-dark').addClass('btn-success text-light');
        }

      } else {
        toastr.error(res.msg, 'Opss!');
      }
    }).fail(function(err) {
      toastr.error('There was an error in the request!', 'Opss!');
    }).always(function() {
      button.closest('.group').waitMe('hide');
    })
  }
});