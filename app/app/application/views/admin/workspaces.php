<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Company Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Trial Ends</th>
                <th>Manual Block</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($workspaces)): ?>
                <?php foreach($workspaces as $ws): ?>
                    <tr>
                        <td><?php echo $ws['id']; ?></td>
                        <td><?php echo $ws['company_name']; ?></td>
                        <td><?php echo $ws['email']; ?></td>
                        <td>
                            <span class="label label-<?php echo ($ws['subscription_status'] == 'active' ? 'success' : ($ws['subscription_status'] == 'trial' ? 'info' : 'danger')); ?>">
                                <?php echo ucfirst($ws['subscription_status']); ?>
                            </span>
                        </td>
                        <td><?php echo $ws['trial_ends_at']; ?></td>
                        <td>
                            <?php if($ws['is_manual_blocked']): ?>
                                <span class="label label-danger">BLOCKED</span>
                                <br><small><?php echo $ws['manual_block_reason']; ?></small>
                            <?php else: ?>
                                <span class="label label-success">Active</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo base_url('admin/users/' . $ws['id']); ?>" class="btn btn-sm btn-info">Users</a>
                            <?php if($ws['is_manual_blocked']): ?>
                                <button class="btn btn-sm btn-success" onclick="unblockWorkspace(<?php echo $ws['id']; ?>)">Unblock</button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-danger" onclick="blockWorkspace(<?php echo $ws['id']; ?>)">Block</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No workspaces found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function blockWorkspace(id) {
    var reason = prompt("Enter reason for blocking:");
    if (reason) {
        window.location.href = "<?php echo base_url('admin/block'); ?>/" + id + "?reason=" + encodeURIComponent(reason);
    }
}

function unblockWorkspace(id) {
    if (confirm("Are you sure you want to unblock this workspace?")) {
        window.location.href = "<?php echo base_url('admin/unblock'); ?>/" + id;
    }
}
</script>
