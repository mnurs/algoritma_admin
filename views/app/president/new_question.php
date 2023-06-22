<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Toolbox
            </div>
            <div class="panel-body">
            <a href="<?php echo base_url('/president'); ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Tambah Level
            </div>
            <div class="panel-body">
                <?php echo $collections['form_error']('form_new_question'); ?>
                <?php echo form_open(); ?>
                    <div class="form-group">
                        <label for="question">Pertanyaan</label>
                        <textarea name="question" id="question" class="form-control" cols="30" rows="10"></textarea>
                        <p class="help-block">Anda dapat memasukkan kode HTML untuk pertanyaan.</p>
                    </div>
                    <div class="form-group">
                        <label for="answer">Jawaban</label>
                        <input type="text" name="answer" class="form-control" placeholder="Jawaban pertanyaan">
                    </div>
                    <button type="submit" class="btn btn-primary">Buat</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>