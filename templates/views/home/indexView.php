<?php require_once INCLUDES.'inc_header.php'; ?>
<?php require_once INCLUDES.'inc_navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12 mt-4">
            <?php echo Flasher::flash(); ?>
        </div>
        <div class="col-12 py-3">
            <h1 class="mb-3 float-start text-light">Current projects</h1>
            <a href="projects/add" class="btn btn-success float-end">Add new project</a>
        </div>
        <div class="col-lg-12 col-12">

            <?php if (empty($d->project->rows)): ?>
                <div class="text-center py-5">
                    <img src="<?php echo IMAGES.'file.png'; ?>" alt="Nothing found" class="img-fluid" style="width: 120px;">
                    <p class="text-muted">There is nothing to show here.</p>
                </div>
            <?php else: ?>
                <table class="table table-striped table-hover text-light">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Title</th>
                            <th># of Tasks</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($d->project->rows as $p): ?>
                            <tr class="text-light">
                                <td><?php echo sprintf('<a href="projects/see/%s">%s</a>', $p->id, $p->number); ?></td>
                                <td><?php echo empty($p->title) ? '<span class="text-muted">Untitled</span>' : add_ellipsis($p->title, 50); ?></td>
                                <td><?php echo $p->total_tasks; ?></td>
                                <td><?php echo format_project_status($p->status); ?></td>
                                <td><?php echo format_date($p->created); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo sprintf('projects/see/%s', $p->id); ?>" class="btn btn-success btn-sm"><i class="fas fa-eye"></i> View</a>
                                        <a href="<?php echo buildURL(sprintf('projects/delete/%s', $p->id)); ?>" class="btn btn-danger btn-sm confirmar"><i class="fas fa-trash"></i> Trash</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php echo $d->project->pagination; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once INCLUDES.'inc_footer.php'; ?>