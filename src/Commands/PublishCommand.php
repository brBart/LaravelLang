<?php namespace Arcanedev\LaravelLang\Commands;

use Arcanedev\LaravelLang\Bases\Command;
use Arcanedev\LaravelLang\Contracts\TransPublisher;
use Arcanedev\LaravelLang\Exceptions\LangPublishException;

/**
 * Class     PublishCommand
 *
 * @package  Arcanedev\LaravelLang\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PublishCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature   = 'trans:publish
                                {locale : The language to publish the translations.}
                                {--force : Force to override the translations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the [locale] translations.';

    /**
     * The TransPublisher instance.
     *
     * @var \Arcanedev\LaravelLang\Contracts\TransPublisher
     */
    private $publisher;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new console command instance.
     *
     * @param  \Arcanedev\LaravelLang\Contracts\TransPublisher  $publisher
     */
    public function __construct(TransPublisher $publisher)
    {
        parent::__construct();

        $this->publisher = $publisher;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->copyright();

        $locale = (string) $this->argument('locale');
        $force  = (bool) $this->option('force');

        if ($this->publisher->isDefault($locale)) {
            $this->info('The locale [' . $locale . '] is a default lang and it\'s shipped with laravel.');
        }
        else {
            $this->publish($locale, $force);
        }

        $this->line('');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Publish the translations.
     *
     * @param  string  $locale
     * @param  bool    $force
     */
    private function publish($locale, $force)
    {
        try {
            $this->publisher->publish($locale, $force);

            $this->info('The locale [' . $locale . '] translations were published successfully.');
        }
        catch (LangPublishException $e) {
            $this->error($e->getMessage());
        }
    }
}
