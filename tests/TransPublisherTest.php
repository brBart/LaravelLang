<?php namespace Arcanedev\LaravelLang\Tests;

use Arcanedev\LaravelLang\TransPublisher;

/**
 * Class     TransPublisherTest
 *
 * @package  Arcanedev\LaravelLang\Tests\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransPublisherTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  TransPublisher */
    private $publisher;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->publisher = $this->app['arcanedev.laravel-lang.publisher'];
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->publisher);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\LaravelLang\TransPublisher::class,
        ];

        foreach ($expectations as $expected) {
            $this->instance($expected, $this->publisher);
        }
    }

    /** @test */
    public function it_can_publish()
    {
        $locale = 'es';

        $this->assertTrue($this->publisher->publish($locale));

        // Clean the lang folder content.
        $this->cleanLangDirectory($locale);

        $this->assertTrue($this->publisher->publish($locale));

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_not_publish_if_is_not_forced()
    {
        $locale = 'es';

        $this->assertTrue($this->publisher->publish($locale));

        try {
            $this->publisher->publish($locale);
        }
        catch(\Arcanedev\LaravelLang\Exceptions\LangPublishException $e) {
            $this->assertEquals(
                'You can\'t publish the translations because the [es] folder is not empty. '.
                'To override the translations, try to clean/delete the [es] folder or force the publication.',
                $e->getMessage()
            );
        }

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_publish_on_force()
    {
        $locale = 'es';

        $this->assertTrue($this->publisher->publish($locale));
        $this->assertTrue($this->publisher->publish($locale, true));

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_skip_if_locale_is_english()
    {
        $this->assertTrue($this->publisher->publish('en'));
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\LaravelLang\Exceptions\LangPublishException
     * @expectedExceptionMessage  The locale [arcanedev] is not supported.
     */
    public function it_must_throw_an_exception_on_unsupported_locale()
    {
        $this->publisher->publish('arcanedev');
    }
}