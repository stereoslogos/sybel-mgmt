<?php 

class homeController extends Controller {
  function __construct()
  {
    if (!Auth::validate()) {
      Flasher::new('You must login first.', 'danger');
      Redirect::to('login');
    }
  }

  function index()
  {
    $data =
    [
      'title' => 'Projects',
      'project' => projectModel::all_paginated(),
      'bg'    => 'dark'
    ];

    View::render('index', $data);
  }

}