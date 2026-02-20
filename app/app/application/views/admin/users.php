<?php
$total_users  = count($users);
$active_users = 0;
$blocked_users = 0;
foreach ($users as $u) {
    $u = (array)$u;
    if (isset($u['is_active']) && (int)$u['is_active'] === 0) $blocked_users++;
    else $active_users++;
}
$org_name    = !empty($org['company_name']) ? htmlspecialchars($org['company_name']) : 'Workspace #' . $org_id;
$org_email   = !empty($org['email']) ? htmlspecialchars($org['email']) : '—';
$org_status  = !empty($org['subscription_status']) ? ucfirst($org['subscription_status']) : '—';
$org_blocked = !empty($org['is_manual_blocked']);
?>

<style>
/* ── Admin Users – Modern UI ───────────────────────────────── */
.au-page {
    padding: 0 4px 40px;
}

/* Back link */
.au-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #667eea;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 22px;
    padding: 7px 14px;
    border-radius: 8px;
    background: #eff3ff;
    transition: background .15s;
}

.au-back:hover {
    background: #e0e7ff;
    color: #4f46e5;
    text-decoration: none;
}

/* Workspace banner */
.au-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 14px;
    padding: 22px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 28px;
    box-shadow: 0 4px 20px rgba(102, 126, 234, .35);
}

.au-banner-left h2 {
    margin: 0 0 4px;
    color: #fff;
    font-size: 20px;
    font-weight: 700;
}

.au-banner-left p {
    margin: 0;
    color: rgba(255, 255, 255, .8);
    font-size: 13px;
}

.au-banner-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.au-pill {
    padding: 5px 13px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: rgba(255, 255, 255, .2);
    color: #fff;
}

.au-pill.blocked {
    background: #fecdd3;
    color: #9f1239;
}

/* Stats */
.au-stats {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}

.au-stat-card {
    flex: 1 1 120px;
    background: #fff;
    border-radius: 12px;
    padding: 16px 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
    display: flex;
    align-items: center;
    gap: 12px;
}

.au-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
}

.au-stat-icon.total {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.au-stat-icon.active {
    background: linear-gradient(135deg, #11998e, #38ef7d);
}

.au-stat-icon.blocked {
    background: linear-gradient(135deg, #eb3349, #f45c43);
}

.au-stat-num {
    font-size: 24px;
    font-weight: 700;
    color: #1a1d23;
    line-height: 1;
}

.au-stat-label {
    font-size: 12px;
    color: #888;
    margin-top: 2px;
}

/* Toolbar */
.au-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    gap: 12px;
    flex-wrap: wrap;
}

.au-toolbar h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1a1d23;
}

.au-search {
    position: relative;
    flex: 0 0 260px;
}

.au-search input {
    width: 100%;
    padding: 8px 12px 8px 36px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #1a1d23;
    outline: none;
    transition: border-color .2s;
}

.au-search input:focus {
    border-color: #667eea;
}

.au-search .sic {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    pointer-events: none;
}

/* Grid */
.au-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 18px;
}

/* User card */
.au-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    display: flex;
    flex-direction: column;
}

.au-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 26px rgba(0, 0, 0, .13);
}

.au-card-stripe {
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.au-card-stripe.blocked {
    background: linear-gradient(90deg, #eb3349, #f45c43);
}

.au-card-body {
    padding: 16px 18px 12px;
    flex: 1;
}

/* Avatar */
.au-avatar-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}

.au-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.au-avatar.blocked {
    background: linear-gradient(135deg, #eb3349, #f45c43);
}

.au-name {
    font-size: 15px;
    font-weight: 700;
    color: #1a1d23;
    line-height: 1.3;
}

.au-role {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 1px;
}

.au-info {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.au-meta {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.au-meta-chip {
    font-size: 11px;
    background: #f8fafc;
    color: #6b7280;
    border-radius: 6px;
    padding: 3px 8px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.au-status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.au-status-badge.active {
    background: #d1fae5;
    color: #065f46;
}

.au-status-badge.blocked {
    background: #fee2e2;
    color: #991b1b;
}

.au-card-footer {
    padding: 10px 18px 14px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    gap: 8px;
}

.au-btn {
    flex: 1;
    padding: 8px 10px;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .15s, transform .1s;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}

.au-btn:active {
    transform: scale(.97);
}

.au-btn.block {
    background: #fff1f2;
    color: #be123c;
}

.au-btn.block:hover {
    background: #fecdd3;
}

.au-btn.unblock {
    background: #f0fdf4;
    color: #166534;
}

.au-btn.unblock:hover {
    background: #bbf7d0;
}

/* Empty */
.au-empty {
    text-align: center;
    padding: 60px 20px;
    color: #9ca3af;
    grid-column: 1/-1;
}

.au-empty svg {
    width: 52px;
    height: 52px;
    margin-bottom: 12px;
    opacity: .4;
}

/* Confirm modal */
.au-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .55);
    backdrop-filter: blur(3px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.au-modal-overlay.open {
    display: flex;
}

.au-modal {
    background: #fff;
    border-radius: 16px;
    width: 100%;
    max-width: 380px;
    margin: 20px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, .22);
    overflow: hidden;
    animation: auSlide .2s ease;
}

@keyframes auSlide {
    from {
        opacity: 0;
        transform: translateY(-10px) scale(.97);
    }

    to {
        opacity: 1;
        transform: none;
    }
}

.au-modal-header {
    padding: 16px 22px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.au-modal-header.block {
    background: linear-gradient(135deg, #eb3349, #f45c43);
}

.au-modal-header.unblock {
    background: linear-gradient(135deg, #11998e, #38ef7d);
}

.au-modal-header h3 {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
}

.au-modal-close {
    background: rgba(255, 255, 255, .2);
    border: none;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    cursor: pointer;
    color: #fff;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.au-modal-body {
    padding: 20px 22px;
    font-size: 14px;
    color: #374151;
}

.au-modal-body strong {
    color: #1a1d23;
}

.au-modal-footer {
    padding: 12px 22px 18px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.au-modal-footer button {
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
}

.au-modal-footer .mx-cancel {
    background: #f1f5f9;
    color: #475569;
}

.au-modal-footer .mx-block {
    background: linear-gradient(135deg, #eb3349, #f45c43);
    color: #fff;
}

.au-modal-footer .mx-unblock {
    background: linear-gradient(135deg, #11998e, #38ef7d);
    color: #fff;
}
</style>

<!-- Block Modal -->
<div class="au-modal-overlay" id="auBlockModal">
    <div class="au-modal">
        <div class="au-modal-header block">
            <h3>Block User</h3>
            <button class="au-modal-close" onclick="closeAuModal('block')">&times;</button>
        </div>
        <div class="au-modal-body">
            Are you sure you want to <strong>block</strong> <strong id="auBlockName"></strong>?<br>
            <span style="font-size:12px;color:#9ca3af;margin-top:6px;display:block">They will lose access to the
                workspace immediately.</span>
        </div>
        <div class="au-modal-footer">
            <button class="mx-cancel" onclick="closeAuModal('block')">Cancel</button>
            <button class="mx-block" onclick="confirmAuBlock()">Yes, Block</button>
        </div>
    </div>
</div>

<!-- Unblock Modal -->
<div class="au-modal-overlay" id="auUnblockModal">
    <div class="au-modal">
        <div class="au-modal-header unblock">
            <h3>Unblock User</h3>
            <button class="au-modal-close" onclick="closeAuModal('unblock')">&times;</button>
        </div>
        <div class="au-modal-body">
            Unblock <strong id="auUnblockName"></strong>?<br>
            <span style="font-size:12px;color:#9ca3af;margin-top:6px;display:block">They will regain access to the
                workspace.</span>
        </div>
        <div class="au-modal-footer">
            <button class="mx-cancel" onclick="closeAuModal('unblock')">Cancel</button>
            <button class="mx-unblock" onclick="confirmAuUnblock()">Yes, Unblock</button>
        </div>
    </div>
</div>

<div class="au-page">

    <a href="<?php echo base_url('admin/workspaces'); ?>" class="au-back">
        &#8592; Back to Workspaces
    </a>

    <!-- Workspace Banner -->
    <div class="au-banner">
        <div class="au-banner-left">
            <h2>&#x1F3E2; <?php echo $org_name; ?></h2>
            <p><?php echo $org_email; ?></p>
        </div>
        <div class="au-banner-badges">
            <span class="au-pill"><?php echo $org_status; ?></span>
            <?php if ($org_blocked): ?>
            <span class="au-pill blocked">&#x1F6AB; Workspace Blocked</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stats -->
    <div class="au-stats">
        <div class="au-stat-card">
            <div class="au-stat-icon total">&#x1F465;</div>
            <div>
                <div class="au-stat-num"><?php echo $total_users; ?></div>
                <div class="au-stat-label">Total Users</div>
            </div>
        </div>
        <div class="au-stat-card">
            <div class="au-stat-icon active">&#x2714;</div>
            <div>
                <div class="au-stat-num"><?php echo $active_users; ?></div>
                <div class="au-stat-label">Active</div>
            </div>
        </div>
        <div class="au-stat-card">
            <div class="au-stat-icon blocked">&#x1F6AB;</div>
            <div>
                <div class="au-stat-num"><?php echo $blocked_users; ?></div>
                <div class="au-stat-label">Blocked</div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="au-toolbar">
        <h3>User Accounts</h3>
        <div class="au-search">
            <svg class="sic" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <path d="M21 21l-4.35-4.35" />
            </svg>
            <input type="text" id="auSearch" placeholder="Search by name or email…" oninput="auFilter()" />
        </div>
    </div>

    <!-- Card Grid -->
    <div class="au-grid" id="auGrid">
        <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
        <?php
                    $u        = (array)$user;
                    $isBlocked = isset($u['is_active']) && (int)$u['is_active'] === 0;
                    $fname    = trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? ''));
                    $fname    = $fname ?: '(No Name)';
                    $initials = strtoupper(substr($u['first_name'] ?? '?', 0, 1) . substr($u['last_name'] ?? '', 0, 1));
                    $email    = htmlspecialchars($u['email'] ?? '—');
                    $role     = isset($u['user_role_id']) ? ((int)$u['user_role_id'] === 1 ? 'Admin' : 'Staff') : 'N/A';
                    $lastAct  = !empty($u['last_activity']) ? $u['last_activity'] : '—';
                    $uid      = (int)$u['id'];
                ?>
        <div class="au-card" data-search="<?php echo strtolower($fname . ' ' . $email); ?>">
            <div class="au-card-stripe <?php echo $isBlocked ? 'blocked' : ''; ?>"></div>
            <div class="au-card-body">
                <div class="au-avatar-row">
                    <div class="au-avatar <?php echo $isBlocked ? 'blocked' : ''; ?>"><?php echo $initials; ?></div>
                    <div>
                        <div class="au-name"><?php echo htmlspecialchars($fname); ?></div>
                        <div class="au-role"><?php echo $role; ?></div>
                    </div>
                    <span class="au-status-badge <?php echo $isBlocked ? 'blocked' : 'active'; ?>"
                        style="margin-left:auto">
                        <?php echo $isBlocked ? 'Blocked' : 'Active'; ?>
                    </span>
                </div>
                <div class="au-info">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                    <?php echo $email; ?>
                </div>
                <div class="au-meta">
                    <div class="au-meta-chip">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <?php echo $lastAct; ?>
                    </div>
                    <div class="au-meta-chip">ID #<?php echo $uid; ?></div>
                </div>
            </div>
            <div class="au-card-footer">
                <?php if ($isBlocked): ?>
                <button class="au-btn unblock"
                    onclick="openAuUnblock(<?php echo $uid; ?>, '<?php echo addslashes(htmlspecialchars($fname)); ?>')">&#x2705;
                    Unblock</button>
                <?php else: ?>
                <button class="au-btn block"
                    onclick="openAuBlock(<?php echo $uid; ?>, '<?php echo addslashes(htmlspecialchars($fname)); ?>')">&#x1F6AB;
                    Block</button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="au-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            <p>No users found in this workspace.</p>
        </div>
        <?php endif; ?>
    </div>

</div>

<script>
var _auBlockId = null,
    _auUnblockId = null;

function openAuBlock(id, name) {
    _auBlockId = id;
    document.getElementById('auBlockName').textContent = name;
    document.getElementById('auBlockModal').classList.add('open');
}

function openAuUnblock(id, name) {
    _auUnblockId = id;
    document.getElementById('auUnblockName').textContent = name;
    document.getElementById('auUnblockModal').classList.add('open');
}

function closeAuModal(type) {
    if (type === 'block') {
        _auBlockId = null;
        document.getElementById('auBlockModal').classList.remove('open');
    }
    if (type === 'unblock') {
        _auUnblockId = null;
        document.getElementById('auUnblockModal').classList.remove('open');
    }
}

function confirmAuBlock() {
    if (_auBlockId) window.location.href = '<?php echo base_url('admin/block_user/'  . $org_id ); ?>' +
        '/' + _auBlockId;
}

function confirmAuUnblock() {
    if (_auUnblockId) window.location.href = '<?php echo base_url('admin/unblock_user/' . $org_id ); ?>' +
        '/' + _auUnblockId;
}

['auBlockModal', 'auUnblockModal'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('open');
            _auBlockId = _auUnblockId = null;
        }
    });
});

function auFilter() {
    var q = document.getElementById('auSearch').value.toLowerCase();
    document.querySelectorAll('.au-card').forEach(function(c) {
        c.style.display = (!q || c.dataset.search.includes(q)) ? '' : 'none';
    });
}
</script>