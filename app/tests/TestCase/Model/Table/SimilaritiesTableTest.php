<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SimilaritiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SimilaritiesTable Test Case
 */
class SimilaritiesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SimilaritiesTable
     */
    public $Similarities;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.similarities',
        'app.cat1s',
        'app.cat2s'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Similarities') ? [] : ['className' => 'App\Model\Table\SimilaritiesTable'];
        $this->Similarities = TableRegistry::get('Similarities', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Similarities);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
