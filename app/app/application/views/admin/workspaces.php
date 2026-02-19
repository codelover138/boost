<?php
$total      = count($workspaces);
$blocked    = 0;
$active     = 0;
$trial      = 0;
foreach ($workspaces as $ws) {
    $ws = (array)$ws;
    if ($ws['is_manual_blocked']) $blocked++;
    elseif (isset($ws['subscription_status']) && $ws['subscription_status'] === 'active') $active++;
    else $trial++;
}
?>

<style>
/* ── Admin Workspaces – Modern UI ──────────────────────────── */
.ws-page { padding: 0 4px 40px; }

/* Stats strip */
.ws-stats {
    display: flex; gap: 16px; flex-wrap: wrap;
    margin-bottom: 28px;
}
.ws-stat-card {
    flex: 1 1 130px;
    background: #fff;
    border-radius: 12px;
    padding: 18px 22px;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
    display: flex; align-items: center; gap: 14px;
}
.ws-stat-card .stat-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff;
}
.ws-stat-card .stat-icon.all    { background: linear-gradient(135deg,#667eea,#764ba2); }
.ws-stat-card .stat-icon.active { background: linear-gradient(135deg,#11998e,#38ef7d); }
.ws-stat-card .stat-icon.trial  { background: linear-gradient(135deg,#f7971e,#ffd200); }
.ws-stat-card .stat-icon.block  { background: linear-gradient(135deg,#eb3349,#f45c43); }
.ws-stat-card .stat-num  { font-size: 26px; font-weight: 700; color: #1a1d23; line-height: 1; }
.ws-stat-card .stat-label{ font-size: 12px; color: #888; margin-top: 2px; }

/* Search */
.ws-toolbar {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 20px; gap: 12px; flex-wrap: wrap;
}
.ws-toolbar h2 { margin: 0; font-size: 20px; font-weight: 700; color: #1a1d23; }
.ws-search {
    position: relative; flex: 0 0 280px;
}
.ws-search input {
    width: 100%; padding: 9px 14px 9px 38px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    font-size: 14px; color: #1a1d23; outline: none;
    transition: border-color .2s;
}
.ws-search input:focus { border-color: #667eea; }
.ws-search .search-ic {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%); color: #aaa; pointer-events: none;
}

/* Grid */
.ws-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
    gap: 20px;
}

/* Card */
.ws-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 14px rgba(0,0,0,.08);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    display: flex; flex-direction: column;
}
.ws-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0,0,0,.14);
}
.ws-card-stripe {
    height: 5px;
    background: linear-gradient(90deg,#667eea,#764ba2);
}
.ws-card-stripe.blocked { background: linear-gradient(90deg,#eb3349,#f45c43); }
.ws-card-stripe.trial   { background: linear-gradient(90deg,#f7971e,#ffd200); }

.ws-card-body { padding: 18px 20px 14px; flex: 1; }

.ws-card-head {
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 10px;
}
.ws-card-name {
    font-size: 16px; font-weight: 700; color: #1a1d23;
    line-height: 1.3; max-width: 70%;
    word-break: break-word;
}
.ws-badge {
    display: inline-block; padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600; white-space: nowrap;
    text-transform: uppercase; letter-spacing: .4px;
}
.ws-badge.active  { background: #d1fae5; color: #065f46; }
.ws-badge.trial   { background: #fef3c7; color: #92400e; }
.ws-badge.expired { background: #fee2e2; color: #991b1b; }
.ws-badge.blocked { background: #fee2e2; color: #991b1b; }
.ws-badge.other   { background: #f3f4f6; color: #374151; }

.ws-card-email {
    font-size: 13px; color: #6b7280; margin-bottom: 10px;
    display: flex; align-items: center; gap: 6px;
}
.ws-card-email svg { flex-shrink: 0; }

.ws-meta { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 12px; }
.ws-meta-item {
    font-size: 12px; color: #6b7280;
    background: #f8fafc; border-radius: 6px; padding: 4px 9px;
    display: flex; align-items: center; gap: 5px;
}

.ws-block-reason {
    background: #fff5f5; border-left: 3px solid #f87171;
    border-radius: 0 6px 6px 0; padding: 7px 10px;
    font-size: 12px; color: #7f1d1d; margin-bottom: 10px;
}
.ws-block-reason strong { display: block; margin-bottom: 2px; }

.ws-card-footer {
    padding: 12px 20px 14px;
    border-top: 1px solid #f1f5f9;
    display: flex; gap: 8px; flex-wrap: wrap;
}
.ws-btn {
    flex: 1; min-width: 70px; padding: 8px 12px;
    border: none; border-radius: 8px; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: opacity .15s, transform .1s;
    text-align: center; text-decoration: none; display: inline-block;
}
.ws-btn:active { transform: scale(.97); }
.ws-btn.btn-users  { background: #eff6ff; color: #1d4ed8; }
.ws-btn.btn-users:hover { background: #dbeafe; }
.ws-btn.btn-block  { background: #fff1f2; color: #be123c; }
.ws-btn.btn-block:hover { background: #fecdd3; }
.ws-btn.btn-unblock{ background: #f0fdf4; color: #166534; }
.ws-btn.btn-unblock:hover { background: #bbf7d0; }

/* Empty state */
.ws-empty {
    text-align: center; padding: 60px 20px; color: #9ca3af;
    grid-column: 1/-1;
}
.ws-empty svg { width: 56px; height: 56px; margin-bottom: 12px; opacity: .4; }
.ws-empty p { font-size: 15px; }

/* Modal */
.ws-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(15,23,42,.55); backdrop-filter: blur(3px);
    z-index: 9999; align-items: center; justify-content: center;
}
.ws-modal-overlay.open { display: flex; }
.ws-modal {
    background: #fff; border-radius: 16px; width: 100%; max-width: 420px;
    margin: 20px; box-shadow: 0 24px 60px rgba(0,0,0,.25);
    overflow: hidden; animation: wsSlideIn .22s ease;
}
@keyframes wsSlideIn {
    from { opacity: 0; transform: translateY(-12px) scale(.97); }
    to   { opacity: 1; transform: none; }
}
.ws-modal-header {
    background: linear-gradient(135deg,#eb3349,#f45c43);
    padding: 18px 24px; color: #fff;
    display: flex; justify-content: space-between; align-items: center;
}
.ws-modal-header h3 { margin: 0; font-size: 16px; font-weight: 700; }
.ws-modal-close {
    background: rgba(255,255,255,.2); border: none; border-radius: 50%;
    width: 28px; height: 28px; cursor: pointer; color: #fff;
    font-size: 16px; display: flex; align-items: center; justify-content: center;
}
.ws-modal-body { padding: 22px 24px; }
.ws-modal-body label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; display: block; }
.ws-modal-body textarea {
    width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px;
    padding: 10px 12px; font-size: 14px; resize: vertical; min-height: 90px;
    outline: none; transition: border-color .2s; box-sizing: border-box;
}
.ws-modal-body textarea:focus { border-color: #eb3349; }
.ws-modal-footer {
    padding: 14px 24px 20px; display: flex; gap: 10px; justify-content: flex-end;
}
.ws-modal-footer button {
    padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 600;
    cursor: pointer; border: none;
}
.ws-modal-footer .btn-cancel  { background: #f1f5f9; color: #475569; }
.ws-modal-footer .btn-confirm { background: linear-gradient(135deg,#eb3349,#f45c43); color: #fff; }
</style>

<!-- Block Reason Modal -->
<div class="ws-modal-overlay" id="blockModal">
    <div class="ws-modal">
        <div class="ws-modal-header">
            <h3>Block Workspace</h3>
            <button class="ws-modal-close" onclick="closeBlockModal()">&times;</button>
        </div>
        <div class="ws-modal-body">
            <label for="blockReason">Reason for blocking <em style="color:#9ca3af;font-weight:400">(optional)</em></label>
            <textarea id="blockReason" placeholder="Enter the reason…"></textarea>
        </div>
        <div class="ws-modal-footer">
            <button class="btn-cancel" onclick="closeBlockModal()">Cancel</button>
            <button class="btn-confirm" onclick="confirmBlock()">Block Workspace</button>
        </div>
    </div>
</div>

<!-- Unblock Confirm Modal -->
<div class="ws-modal-overlay" id="unblockModal">
    <div class="ws-modal">
        <div class="ws-modal-header" style="background:linear-gradient(135deg,#11998e,#38ef7d)">
            <h3>Unblock Workspace</h3>
            <button class="ws-modal-close" onclick="closeUnblockModal()">&times;</button>
        </div>
        <div class="ws-modal-body">
            <p style="margin:0;font-size:14px;color:#374151">Are you sure you want to <strong>unblock</strong> this workspace? The account will regain full access immediately.</p>
        </div>
        <div class="ws-modal-footer">
            <button class="btn-cancel" onclick="closeUnblockModal()">Cancel</button>
            <button class="btn-confirm" style="background:linear-gradient(135deg,#11998e,#38ef7d)" onclick="confirmUnblock()">Yes, Unblock</button>
        </div>
    </div>
</div>

<div class="ws-page">

    <!-- Stats -->
    <div class="ws-stats">
        <div class="ws-stat-card">
            <div class="stat-icon all">&#x1F3E2;</div>
            <div>
                <div class="stat-num"><?php echo $total; ?></div>
                <div class="stat-label">Total Workspaces</div>
            </div>
        </div>
        <div class="ws-stat-card">
            <div class="stat-icon active">&#x2714;</div>
            <div>
                <div class="stat-num"><?php echo $active; ?></div>
                <div class="stat-label">Active</div>
            </div>
        </div>
        <div class="ws-stat-card">
            <div class="stat-icon trial">&#x23F3;</div>
            <div>
                <div class="stat-num"><?php echo $trial; ?></div>
                <div class="stat-label">Trial / Other</div>
            </div>
        </div>
        <div class="ws-stat-card">
            <div class="stat-icon block">&#x1F6AB;</div>
            <div>
                <div class="stat-num"><?php echo $blocked; ?></div>
                <div class="stat-label">Blocked</div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="ws-toolbar">
        <h2>All Workspaces</h2>
        <div class="ws-search">
            <svg class="search-ic" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" id="wsSearch" placeholder="Search by name or email…" oninput="filterCards()" />
        </div>
    </div>

    <!-- Card Grid -->
    <div class="ws-grid" id="wsGrid">
        <?php if (!empty($workspaces)): ?>
            <?php foreach ($workspaces as $ws): ?>
                <?php
                    $ws = (array)$ws;
                    $isBlocked = !empty($ws['is_manual_blocked']);
                    $status    = isset($ws['subscription_status']) ? strtolower($ws['subscription_status']) : 'trial';
                    $stripeClass = $isBlocked ? 'blocked' : ($status === 'trial' ? 'trial' : '');
                    $badgeClass  = $isBlocked ? 'blocked' : ($status === 'active' ? 'active' : ($status === 'trial' ? 'trial' : ($status === 'expired' ? 'expired' : 'other')));
                    $badgeLabel  = $isBlocked ? 'Blocked' : ucfirst($status);
                    $trialEnd    = !empty($ws['trial_ends_at']) ? $ws['trial_ends_at'] : '—';
                    $company     = !empty($ws['company_name']) ? htmlspecialchars($ws['company_name']) : '(No Name)';
                    $email       = !empty($ws['email']) ? htmlspecialchars($ws['email']) : '—';
                    $reason      = !empty($ws['manual_block_reason']) ? htmlspecialchars($ws['manual_block_reason']) : '';
                ?>
                <div class="ws-card" data-search="<?php echo strtolower($company . ' ' . $email); ?>">
                    <div class="ws-card-stripe <?php echo $stripeClass; ?>"></div>
                    <div class="ws-card-body">
                        <div class="ws-card-head">
                            <div class="ws-card-name"><?php echo $company; ?></div>
                            <span class="ws-badge <?php echo $badgeClass; ?>"><?php echo $badgeLabel; ?></span>
                        </div>
                        <div class="ws-card-email">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <?php echo $email; ?>
                        </div>
                        <div class="ws-meta">
                            <div class="ws-meta-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Trial ends: <?php echo $trialEnd; ?>
                            </div>
                            <div class="ws-meta-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                ID #<?php echo (int)$ws['id']; ?>
                            </div>
                        </div>
                        <?php if ($isBlocked && $reason): ?>
                            <div class="ws-block-reason">
                                <strong>Block reason:</strong>
                                <?php echo $reason; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="ws-card-footer">
                        <a href="<?php echo base_url('admin/users/' . $ws['id']); ?>" class="ws-btn btn-users">&#x1F465; Users</a>
                        <?php if ($isBlocked): ?>
                            <button class="ws-btn btn-unblock" onclick="openUnblockModal(<?php echo (int)$ws['id']; ?>)">&#x2705; Unblock</button>
                        <?php else: ?>
                            <button class="ws-btn btn-block" onclick="openBlockModal(<?php echo (int)$ws['id']; ?>)">&#x1F6AB; Block</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="ws-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <p>No workspaces found.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
var _pendingBlockId   = null;
var _pendingUnblockId = null;

function openBlockModal(id) {
    _pendingBlockId = id;
    document.getElementById('blockReason').value = '';
    document.getElementById('blockModal').classList.add('open');
}
function closeBlockModal() {
    _pendingBlockId = null;
    document.getElementById('blockModal').classList.remove('open');
}
function confirmBlock() {
    if (!_pendingBlockId) return;
    var reason = document.getElementById('blockReason').value.trim();
    window.location.href = '<?php echo base_url('admin/block'); ?>/' + _pendingBlockId + '?reason=' + encodeURIComponent(reason);
}

function openUnblockModal(id) {
    _pendingUnblockId = id;
    document.getElementById('unblockModal').classList.add('open');
}
function closeUnblockModal() {
    _pendingUnblockId = null;
    document.getElementById('unblockModal').classList.remove('open');
}
function confirmUnblock() {
    if (!_pendingUnblockId) return;
    window.location.href = '<?php echo base_url('admin/unblock'); ?>/' + _pendingUnblockId;
}

// Close on overlay click
['blockModal','unblockModal'].forEach(function(id){
    document.getElementById(id).addEventListener('click', function(e){
        if (e.target === this) {
            this.classList.remove('open');
            _pendingBlockId = _pendingUnblockId = null;
        }
    });
});

// Live search
function filterCards() {
    var q = document.getElementById('wsSearch').value.toLowerCase();
    document.querySelectorAll('.ws-card').forEach(function(card){
        var match = !q || card.dataset.search.includes(q);
        card.style.display = match ? '' : 'none';
    });
}
</script>
