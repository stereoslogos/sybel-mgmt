<div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
      <a href="<?php echo URL; ?>" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
        <img src="./assets/images/sybel-icon.png" width="30">
      </a>

      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
        <li><a href="<?php echo URL; ?>" class="nav-link px-2 text-warning">Projects</a></li>
        <li><a href="#" class="nav-link px-2 disabled">FAQs</a></li>
        <li><a href="#" class="nav-link px-2 disabled">About</a></li>
      </ul>

      <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
        <!-- <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search"> -->
      </form>

      <div class="text-end">
        <?php if (!Auth::validate()): ?>
            <button type="button" class="btn btn-outline-light me-2">Login</button>
        <?php else: ?>
            <a href="logout" class="nav-link btn btn-danger">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </div>