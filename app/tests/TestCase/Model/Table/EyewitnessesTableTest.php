<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EyewitnessesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EyewitnessesTable Test Case
 */
class EyewitnessesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EyewitnessesTable
     */
    public $Eyewitnesses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.eyewitnesses',
        'app.users',
        'app.cats',
        'app.cat_images',
        'app.favorites',
        'app.comments',
        'app.tags',
        'app.cats_tags',
        'app.comments_tags',
        'app.answers',
        'app.questions',
        'app.response_statuses',
        'app.eyewitness_images'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Eyewitnesses') ? [] : ['className' => 'App\Model\Table\EyewitnessesTable'];
        $this->Eyewitnesses = TableRegistry::get('Eyewitnesses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Eyewitnesses);

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
