<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="container">
  <div class="py-5 text-center">
    <a href="<?php echo URL; ?>"><img src="<?php echo IMAGES.'sybel-logo.png' ?>" alt="Bee framework" class="img-fluid" style="width: 200px;"></a>
    <h2>Sybel Project Manager</h2>
    <p class="lead">Login to your account to start managing your project with us.</p>
  </div>

  <div class="row">
    <div class="col-12">
      <?php echo Flasher::flash(); ?>
    </div>

    <!-- formulario -->
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h4>Associate account</h4>
        </div>
        <div class="card-body">
          <form action="login/post_login" method="post" novalidate>
            <?php echo insert_inputs(); ?>
            
            <div class="mb-3 row">
              <div class="col-xl-6">
                <label for="usuario">User</label>
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="admin" required>
                <!-- <small class="text-muted">Ingresa bee</small> -->
              </div>
              <div class="col-xl-6">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password"required>
                <!-- <small class="text-muted">Ingresa 123456</small> -->
              </div>
            </div>

            <button class="btn btn-primary btn-block" type="submit">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once INCLUDES.'inc_footer.php'; ?>

