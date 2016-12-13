<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EyewitnessImagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EyewitnessImagesTable Test Case
 */
class EyewitnessImagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EyewitnessImagesTable
     */
    public $EyewitnessImages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.eyewitness_images',
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
        $config = TableRegistry::exists('EyewitnessImages') ? [] : ['className' => 'App\Model\Table\EyewitnessImagesTable'];
        $this->EyewitnessImages = TableRegistry::get('EyewitnessImages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EyewitnessImages);

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
