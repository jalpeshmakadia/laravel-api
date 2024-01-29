<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Urls;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Redis;


class ScrapeDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;

    /**
     * Create a new job instance.
     */
    public function __construct(Urls $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        /**
         * I have scraped data based on fix url and some fix element.
         * EX URL: https://reiztech.recruitee.com/o/devops-engineer-3
         * We can scrape more data based on requirement.
        */

        try {

            $client = new Client();
            $response = $client->request('GET',  $this->url->url);

            $html = $response->getBody()->getContents();

            $crawler = new Crawler($html);

            // Get first h1 heading as title
            $h1Data = $description = null;
            $h1Node = $crawler->filter('h1')->first();
            if (!empty($h1Node->getNode(0))) {
                $h1Data = $h1Node->text();
            }


            // Get all links
            $aTagData = [];
            $crawler->filter('a')->each(function (Crawler $node) use (&$aTagData) {
                $aData['href'] = $node->attr('href');
                $aData['title'] = $node->text();
                $aTagData[] = $aData;
            });

            // Get description data
            $descNode = $crawler->filter('div[class="sc-1v95195-0 jscoSJ"]');
            if (!empty($descNode->getNode(0))) {
                $description = $descNode->text();
            }

            // Save data to redis
            $urlData ['title'] = $h1Data;
            $urlData['a_tags'] = $aTagData;
            $urlData['description'] = $description;

            Redis::set('job:'.$this->url->id, json_encode($urlData));

            Urls::where('id', $this->url->id)->update(['is_scraped' => true]);

        } catch (\Exception $e) {

            Log::info('Error on Scraping data URL: '. $this->url->url . ' :: '. $e->getMessage());

        }

    }
}
