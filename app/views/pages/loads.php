<?php $u = require_user(); ?>
<section class="dash-head"><div><p class="eyebrow">Marketplace</p><h1><?= $u['role'] === 'shipper' ? 'Your posted loads' : 'Find open loads' ?></h1></div></section>
<?php render_load_cards($u, true); ?>
