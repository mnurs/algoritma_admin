<div class="row">
    <div class="col-md-12">
        <h3>Private Access Only</h3>
        <p>Jika anda bukan merupakan pengurus laman ini, dimohon untuk meninggalkan laman ini.</p>
        <?php echo form_open(); ?>
            <div class="form-group">
                <label for="username">Access Code</label>
                <input type="password" name="access" class="form-control" id="username" value="<?php echo set_value('access'); ?>" placeholder="Masukkan akses kode">
            </div>
            <button type="submit" class="btn btn-primary">Enter</button>
        <?php echo form_close(); ?>
    </div>
</div>