<div class="row">
    <div class="col-md-12">
        <h3>Masuk</h3>
        <p>Silahkan masuk menggunakan kredensial login anda.</p>
        <?php echo $collections['form_error']('form_login'); ?>
        <?php echo form_open(); ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" value="<?php echo set_value('username'); ?>" placeholder="Masukkan username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Authentikasi</button>
        <?php echo form_close(); ?>
    </div>
</div>