<div class="row">
    <div class="col-md-12">
        <h3>Daftar</h3>
        <?php if ($data['open']): ?>
            <div class="bs-callout bs-callout-info">
                <h4>Perhatian</h4>
                <p>Pastikan anda mengikuti aturan yang telah ditetapkan sebelum melakukan pendaftaran.</p>
            </div>
            <?php echo validation_errors(); ?>
            <?php echo form_open(); ?>
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" name="name" class="form-control" id="name" value="<?php echo set_value('name'); ?>" placeholder="Nama Lengkap">
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" id="username" value="<?php echo set_value('username'); ?>" placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
            <?php echo form_close(); ?>
        <?php else: ?>
            <div class="alert alert-danger">
                <strong>Maaf!</strong> <?php echo $data['message']; ?>
            </div>
        <?php endif; ?>
    </div>
</div>