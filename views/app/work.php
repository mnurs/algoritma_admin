<div class="row">
  <div class="col-md-12">
    <?php if (empty($data['auth']->question)): ?>
      <?php if ($data['completed']): ?>
        <h3><i class="fa fa-coffee"></i> Good job!</h1>
        <p>Selamat anda telah menyelesaikan Algoritmaru, HAVE FUN FOREVER!</p>
      <?php else: ?>
        <h3>Bumi belum berpenghuni</h1>
        <p>Saat ini tidak ada yang dapat anda kerjakan, silahkan akses laman ini lain kali.</p>
      <?php endif; ?>
    <?php else: ?>
      <h3>Soal Level <?php echo $data['auth']->question->id; ?></h3>
      <div class="question">
        <?php echo $data['auth']->question->question; ?>
      </div>
      <?php echo form_open('', ['class' => 'form-work']); ?>
        <div class="form-group">
          <label for="answer">Answer</label>
          <input type="text" name="answer" class="form-control" placeholder="Jawaban anda" autofocus>
        </div>
        <?php if ($data['captcha_enable']): ?>
          <div class="form-group">
            <label for="captcha">Challange: <?php echo $data['captcha_data']['q']; ?></label>
            <input type="text" name="captcha" class="form-control" placeholder="Answer above captcha question">
          </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Jawab</button>
      <?php echo form_close(); ?>
    <?php endif; ?>
  </div>
</div>