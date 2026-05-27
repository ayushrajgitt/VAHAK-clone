<section class="auth">
    <h1>Create your Vahak account</h1>
    <p>One email can be used for one role only.</p>
    <form method="post" class="form">
        <input type="hidden" name="action" value="signup">
        <label>Name<input required name="name"></label>
        <label>Gmail / Email<input required type="email" name="email"></label>
        <label>Password<input required type="password" name="password"></label>
        <label>Register as<select required name="role"><option value="shipper">Shipper</option><option value="driver">Driver</option><option value="transporter">Transporter</option></select></label>
        <button class="button">Signup</button>
    </form>
</section>
