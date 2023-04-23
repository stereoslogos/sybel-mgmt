<?php if(!empty($d->sub)): ?>
    <div id="accordion">
        <?php foreach ($d->sub as $task): ?>
            <div class="group" data-id="<?php echo $task->id; ?>">
                <h3 class="clearfix">
                    <span class="numeracion"></span>
                    <?php echo sprintf('%s %s', format_task_type($task->type), $task->title); ?>
                    <button class="btn btn-sm float-end update_task_status <?php echo $task->status === 'pending' ? 'btn-warning text-dark' : 'btn-success text-light'; ?>" data-id="<?php echo $task->id; ?>" data-status="<?php echo $task->status; ?>"><i class="fas fa-check"></i> Ready</button>
                </h3>
                <div>
                    <?php echo empty($task->content) ? '<span class="text-muted">No content.</span>' : $task->content; ?>
                    <div class="mt-3">
                        <div class="btn-group">
                            <button class="btn btn-primary btn-sm open_update_task_form" data-id="<?php echo $task->id; ?>"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm delete_task" data-id="<?php echo $task->id; ?>"><i class="fas fa-trash"></i></button>
                        </div>
                        
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <img src="<?php echo IMAGES.'file.png'; ?>" alt="Nothing found" class="img-fluid" style="width: 120px;">
        <p class="text-muted">There is nothing to show here.</p>
    </div>
<?php endif; ?>