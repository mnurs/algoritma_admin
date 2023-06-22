<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_users_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => [ 'type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => TRUE ],
            'name' => [ 'type' =>  'VARCHAR', 'constraint' => '100' ],
            'username' => [ 'type' =>  'VARCHAR', 'constraint' => '24' ],
            'password' => [ 'type' =>  'TEXT' ],
            'current_id_soal' => [ 'type' => 'INT', 'constraint' => 10, 'default' => 1 ],
            'last_login' => [ 'type' => 'VARCHAR', 'constraint' => '100', 'null' => true ],
            'last_try' => [ 'type' => 'VARCHAR', 'constraint' => '100', 'null' => true ],
            'created_at' => [ 'type' => 'VARCHAR', 'constraint' => '100', 'null' => true ],
            'updated_at' => [ 'type' => 'VARCHAR', 'constraint' => '100', 'null' => true ],
        ));

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('username');

        $this->dbforge->create_table('users');
    }

    public function down() {
        $this->dbforge->drop_table('users');
    }

}
