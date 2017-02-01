<?php
use Migrations\AbstractMigration;

class CreateSimilarities extends AbstractMigration
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
        $table->addColumn('image1_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('image2_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('answer', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
