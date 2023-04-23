<?php require_once INCLUDES.'inc_header.php'; ?>
<?php require_once INCLUDES.'inc_navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <?php echo Flasher::flash(); ?>
        </div>
        <div class="col-12 py-3">
            <h1 class="mb-3 float-start text-light"><?php echo $d->title; ?></h1>
            <a href="home" class="btn btn-warning float-end">Go back</a>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">Project details</div>
                <div class="card-body">
                    <form id="project_form">
                        <input type="hidden" name="id" value="<?php echo $d->p->id; ?>" required>
                        <?php echo insert_inputs(); ?>
                        
                        <div class="mb-3">
                            <label for="title">Project title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $d->p->title; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="status">Status</label>
                            <!-- <input type="text" class="form-control" id="status" name="status"> -->
                            <select name="status" id="status" class="form-select">
                                <?php foreach (get_project_status() as $status): ?>
                                    <?php echo sprintf('<option value="%s">%s</option>', $status[0], $status[1]); ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description">Project description</label>
                            <textarea class="form-control" id="description" name="description" cols="3" rows="3"><?php echo $d->p->description; ?></textarea>
                        </div>

                        <button class="btn btn-success" type="submit">Update project</button>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Add task</div>
                <div class="card-body">
                    <form id="add_task_form">
                        <input type="hidden" name="id_project" value="<?php echo $d->p->id; ?>" required>
                        <?php echo insert_inputs(); ?>

                        <div class="mb-3">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required></input>
                        </div>

                        <div class="mb-3">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-select">
                                <?php foreach (get_task_type() as $type): ?>
                                    <?php echo sprintf('<option value="%s">%s</option>', $type[0], $type[1]); ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content" cols="2" rows="2"></textarea>
                        </div>

                        <button class="btn btn-success" type="submit">Add</button>
                    </form>
                </div>
            </div>

            <div class="card mb-3" style="display:none; ">
                <div class="card-header">Update task</div>
                <div class="card-body">
                    <form id="update_task_form">
                        <?php echo insert_inputs(); ?>
                        <input type="hidden" name="id" value="" required>

                        <div class="mb-3">
                            <label for="u_title">Title</label>
                            <input type="text" class="form-control" id="u_title" name="u_title" required></input>
                        </div>

                        <div class="mb-3">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-select">
                                <?php foreach (get_task_type() as $type): ?>
                                    <?php echo sprintf('<option value="%s">%s</option>', $type[0], $type[1]); ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content" cols="2" rows="2"></textarea>
                        </div>

                        <button class="btn btn-success" type="submit">Update</button>
                        <button class="btn btn-danger cancel_update_task" type="reset">Cancel</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="wrapper_tasks" data-id="<?php echo $d->p->id; ?>">
                <!-- Ajax loaded -->
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>