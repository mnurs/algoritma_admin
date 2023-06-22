<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Manage Website
            </div>
            <div class="panel-body">
                <h4>Registrasi</h4>
                <p>Status registrasi: <strong><?php echo ($data['settings']['REGISTER_OPENED'] == 1) ? 'Dibuka' : 'Ditutup';?></strong></p>
                <?php echo form_open(); ?>
                    <div class="form-group">
                        <label for="REGISTER_CLOSE_MSG">Pesan Penutupan</label>
                        <input type="text" class="form-control" name="REGISTER_CLOSE_MSG" value="<?php echo $data['settings']['REGISTER_CLOSE_MSG']; ?>" >
                    </div>
                    <button type="submit" class="btn btn-<?php echo ($data['settings']['REGISTER_OPENED'] == 1) ? 'success' : 'danger';?>"><?php echo ($data['settings']['REGISTER_OPENED'] == 1) ? 'Tutup Registrasi' : 'Buka Registrasi';?></button>
                <?php echo form_close(); ?>
                <hr>
                <h4>Keamanan</h4>
                <p>Pengaturan tentang pada level berapa user akan otomatis direset ke level pertama dan pengaplikasian captcha dan antibot guard.</p>
                <?php echo form_open(); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="RESET_START_LEVEL">Reset Level</label>
                                <input type="number" class="form-control" name="RESET_START_LEVEL" value="<?php echo $data['settings']['RESET_START_LEVEL']; ?>" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="CAPTCHA_START_LEVEL">Captcha Level</label>
                                <input type="number" class="form-control" name="CAPTCHA_START_LEVEL" value="<?php echo $data['settings']['CAPTCHA_START_LEVEL']; ?>" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ANTIBOT_START_LEVEL">Anti-bot Level</label>
                                <input type="number" class="form-control" name="ANTIBOT_START_LEVEL" value="<?php echo $data['settings']['ANTIBOT_START_LEVEL']; ?>" >
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Ubah Pengaturan Keamanan</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <?php

    function calculate_passed($data, $current_level, $passed = true)
    {
        $calculate = 0;

        if ($passed)
        {
            foreach ($data['level_repository'] as $level => $number)
            {
                if ($current_level >= $level)
                    break;
                
                $calculate += $number;
            }
        } else
        {
            foreach ($data['level_repository'] as $level => $number)
            {
                if ($current_level < $level)
                    continue;
                
                $calculate += $number;
            }
        }

        return $calculate;
    }

    ?>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Manage Question
            </div>
            <div class="panel-body">
                <p><a href="<?php echo base_url('/president/new_question'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Level</a></p>
                <p>Kamu hanya dapat menghapus soal terakhir.</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>User Not Passed</th>
                            <th>User Passed</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['questions'])): foreach ($data['questions'] as $question): ?>
                            <tr>
                                <th scope="row"><?php echo $question->id; ?></th>
                                <td><?php echo calculate_passed($data, $question->id, false); ?></td>
                                <td><?php echo calculate_passed($data, $question->id); ?></td>
                                <td><a class="btn btn-primary btn-xs" href="<?php echo base_url('/president/question/' . $question->id); ?>">Lihat</a></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Manage User
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Last Login</th>
                            <th>Last Try</th>
                            <th>Level Sekarang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['users'])): foreach ($data['users'] as $user): ?>
                            <tr>
                                <th><?php echo $user->name; ?></th>
                                <th scope="row"><?php echo $user->username; ?></th>
                                <th><?php echo date('D, d M Y', strtotime($user->last_login)); ?></th>
                                <th><?php echo date('D, d M Y', strtotime($user->last_try)); ?></th>
                                <th><?php echo $user->current_id_soal; ?></th>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>