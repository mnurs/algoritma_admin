<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_questions_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => [ 'type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => TRUE ],
            'question' => [ 'type' =>  'TEXT' ],
            'answer' => [ 'type' =>  'VARCHAR', 'constraint' => '100' ],
            'created_at' => [ 'type' => 'VARCHAR', 'constraint' => '100', 'null' => true ],
            'updated_at' => [ 'type' => 'VARCHAR', 'constraint' => '100', 'null' => true ],
        ));

        $this->dbforge->add_key('id', true);

        $this->dbforge->create_table('questions');
    }

    public function down() {
        $this->dbforge->drop_table('questions');
    }

}
