<div class="row">
    <div class="col-md-12">
        <a href="<?php echo base_url('admin/workspaces'); ?>" class="btn btn-default">&larr; Back to Workspaces</a>
        <br><br>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Last Activity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($users)): ?>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <td><?php echo $user->id; ?></td>
                                <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                                <td><?php echo $user->email; ?></td>
                                <td><?php echo isset($user->user_role_id) ? ($user->user_role_id == 1 ? 'Admin' : 'Staff') : 'N/A'; ?></td>
                                <td><?php echo $user->last_activity; ?></td>
                                <td>
                                    <?php if(isset($user->is_active) && $user->is_active == 0): ?>
                                        <span class="label label-danger">Blocked</span>
                                    <?php else: ?>
                                        <span class="label label-success">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(isset($user->is_active) && $user->is_active == 0): ?>
                                        <a href="<?php echo base_url('admin/unblock_user/' . $org_id . '/' . $user->id); ?>" class="btn btn-sm btn-success" onclick="return confirm('Unblock user?')">Unblock</a>
                                    <?php else: ?>
                                        <a href="<?php echo base_url('admin/block_user/' . $org_id . '/' . $user->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Block user?')">Block</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No users found in this workspace.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
