<?php
use Migrations\AbstractMigration;

class AddUserToSimilarities extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('similarities');
        $table->addColumn('user_id', 'char', [
            'default' => null,
            'limit' => 36,
            'null' => true,
        ]);
        $table->update();
    }
}
