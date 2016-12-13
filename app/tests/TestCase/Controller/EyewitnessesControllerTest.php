<?php
namespace App\Test\TestCase\Controller;

use App\Controller\EyewitnessesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\EyewitnessesController Test Case
 */
class EyewitnessesControllerTest extends IntegrationTestCase
{

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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
