<?php

namespace MixCode\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use MixCode\City;
use MixCode\Country;

class ImportLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import locations form api and insert it in database';

    /**
     * Progress bar container
     * 
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    protected $progressBar;

    /**
     * Countries collection
     *
     * @var array
     */
    protected $countries;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->warn('Fetching countries from API');
            
            $this->fetchCountries();

            $countriesCount = count($this->countries);
            
            $this->info("{$countriesCount} Country was fetched successfully");

            $this->warn('Saving countries to database');

            $this->initProgressBar($countriesCount);

            $this->saveCountriesWithCapitals();

            $this->line("");

            $this->info('Countries saved to database successfully');


        } catch (GuzzleException $th) {
            $this->error($th->getMessage());
        }
    }

    /**
     * Init Progress bar
     *
     * @return $this
     */
    protected function initProgressBar($max = 0)
    {
        $this->progressBar = $this->output->createProgressBar($max);

        $this->progressBar->setBarCharacter('█');
        $this->progressBar->setEmptyBarCharacter('-');
        $this->progressBar->setProgressCharacter('');

        return $this;
    }

    /**
     * Fetch countries and its capitals from api
     *
     * @return $this
     */
    protected function fetchCountries()
    {
        $client     = new Client();
        $res        = $client->get('https://restcountries.eu/rest/v2/all?fields=name;translations;capital');
        $this->countries = json_decode($res->getBody()->getContents());

        return $this;
    }

    /**
     * Save countries and its capitals in database
     *
     * @return $this
     */
    protected function saveCountriesWithCapitals()
    {
        foreach ($this->countries as $country) {
            $en_country_name    = strtolower($country->name);
            $ar_country_name    = strtolower($country->translations->fa);
            $capital_name       = strtolower($country->capital);
            
            if (!! $en_country_name && !! $ar_country_name) {
                $country = Country::create([
                    'en_name'   => $en_country_name,
                    'ar_name'   => $ar_country_name,
                    'status'    => Country::ACTIVE_STATUS,
                ]);
                
                if (!! $capital_name) {
                    $country->cities()->create([
                        'en_name'   => $capital_name,
                        'ar_name'   => $capital_name,
                        'status'    => City::ACTIVE_STATUS,
                    ]);
                }
            }
            
            $this->progressBar->advance();
        }

        $this->progressBar->finish();

        return $this;
    }
}
