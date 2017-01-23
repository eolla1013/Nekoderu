<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CatImageAnalysesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CatImageAnalysesTable Test Case
 */
class CatImageAnalysesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CatImageAnalysesTable
     */
    public $CatImageAnalyses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.cat_image_analyses',
        'app.cat_images',
        'app.users',
        'app.cats',
        'app.favorites',
        'app.comments',
        'app.tags',
        'app.cats_tags',
        'app.comments_tags',
        'app.reports',
        'app.answers',
        'app.questions',
        'app.eyewitnesses',
        'app.eyewitness_images',
        'app.response_statuses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CatImageAnalyses') ? [] : ['className' => 'App\Model\Table\CatImageAnalysesTable'];
        $this->CatImageAnalyses = TableRegistry::get('CatImageAnalyses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CatImageAnalyses);

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
