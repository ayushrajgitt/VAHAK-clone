<?php $u = require_user(); ?>
<section class="auth">
    <h1>Edit profile</h1>
    <form method="post" class="form">
        <input type="hidden" name="action" value="update_profile">
        <label>Name<input required name="name" value="<?= h($u['name']) ?>"></label>
        <label>Profile picture URL<input required name="photo" value="<?= h($u['photo']) ?>"></label>
        <label>Bio<textarea required name="bio"><?= h($u['bio']) ?></textarea></label>
        <button class="button">Save profile</button>
    </form>
</section>
