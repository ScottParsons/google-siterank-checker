<?php
use Sunra\PhpSimple\HtmlDomParser;

class PageChecker
{
    /**
     * Google search URL
     */
    const SEARCH_URL = 'http://www.google.co.uk/search?hl=en&tbo=d&site=&source=hp&';

    /**
     * Set number of pages to scrape
     */
    const PAGES_COUNT = 5;

    /**
     * @var array
     */
    private $data;

    /**
     * Results array
     *
     * @var array
     */
    public $results = [];

    /**
     * PageChecker constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function preparePhraseForGoogle($phrase)
    {
        return str_replace(' ', '+', $phrase);
    }

    /**
     * @param $phrase
     * @param $pager
     * @return string
     */
    public function prepareQueryStringForGoogle($phrase, $pager)
    {
        $queryData = [
            'q' => $phrase,
            'oq' => $phrase,
            'start' => $pager
        ];

        return self::SEARCH_URL . urldecode(http_build_query($queryData));
    }

    public function init()
    {
        foreach ($this->data as $domain => $phrases) {
            foreach ($phrases as $phrase) {
                $googlePhrase = $this->preparePhraseForGoogle($phrase);

                $pageRank = 0;
                for ($i = 0; $i < self::PAGES_COUNT*10; $i+= 10) {

                    $url = $this->prepareQueryStringForGoogle($googlePhrase, $i);
                    $html = HtmlDomParser::file_get_html($url);

                    if ($html) {
                        $linkObjs = $html->find('h3.r a');
                        foreach ($linkObjs as $linkObj) {
                            $pageRank++;

                            $link = trim($linkObj->href);

                            // if it is not a direct link but url reference found inside it, then extract
                            if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
                                $link = $matches[1];
                            } else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
                                continue;
                            }

                            $cleanUrl = $this->parseUrl($link);
                            if ($cleanUrl === $domain) {
                                $this->results[$domain][$phrase] = ['pagerank' => $pageRank, 'link' => $link];
                            }
                        }
                    }
                }
            }
        }
        $this->output($this->results);
    }

    /**
     * Get domain name only from URL in Google and remove www
     *
     * @param $url
     * @return string
     */
    public function parseUrl($url)
    {
        $parts = parse_url($url);
        return preg_replace('#^www\.(.+\.)#i', '$1', $parts['host']);
    }

    /**
     * Print results
     *
     * @param $results
     */
    public function output($results)
    {
        foreach ($results as $domain => $phrases) {
            foreach ($phrases as $phrase => $data) {
                echo "$domain: Found in position " . $data['pagerank'] . " for phrase '$phrase'" . PHP_EOL;
                echo "Search results link: " . $data['link'] . PHP_EOL . PHP_EOL;
            }
        }
    }
}

