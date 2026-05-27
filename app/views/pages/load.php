<?php $u = require_user(); $load = load_with_people((int)($_GET['id'] ?? 0)); ?>
<?php if (!$load): ?><p>Load not found.</p><?php else: render_load_detail($load, $u); endif; ?>
