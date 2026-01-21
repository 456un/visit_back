<?php

declare(strict_types=1);

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class ParserYakStudio extends Command
{
    private const SITE_URL = 'https://yak-studio.ru/';

    private const DIT_FILE_PARSER = __DIR__ . '/../../../storage/parser/yak_studio/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:yak-studio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсер веб студий с сайта yak-studio.ru';

    /**
     * @return int
     */
    public function handle(): int
    {
        $offset = 0;
        $i = 0;
        $studio = [];
        while (true) {
            sleep(1);
            $this->info('Продолжаем построение списка offset: ' . $offset);

            $body = $this->getListStudioHtml($offset);

            $crawler = new Crawler($body);

            $nodes = $crawler->filter('a.agency-name');

            if ($nodes->count() === 0) {
                $this->info('Список студий спарсен: ' . count($studio));

                break;
            }

            $nodes->each(function ($node) use (&$studio, &$i) {
                $studio[$i]['name'] = $node->text();
                $studio[$i]['link'] = $node->attr('href');

                $i++;
            });

            $offset += 20;
        }

        $this->info('Получаем email студий');

        for ($j = 0; $j < count($studio); $j++) {
            sleep(1);

            $link = self::SITE_URL . $studio[$j]['link'];

            $this->info('Запрос email: ' . $studio[$j]['name']);

            $studioDesc = $this->getStudioDesc($link);

            $crawler = new Crawler($studioDesc);
            $crawler->filter('a.profile-info__link')->each(function ($node) use (&$studio, $j) {
                $href = $node->attr('href');

                if (str_contains($href, 'mailto')) {
                    $studio[$j]['email'] = $node->text();
                }
            });
        }

        $this->info('Сохраняем CSV');
        $this->saveCSV($studio);

        return self::SUCCESS;
    }

    /**
     * @param int $offset
     * @return string
     */
    private function getListStudioHtml(int $offset): string
    {
        $client = new Client();
        $headers = [
            'accept' => '*/*',
            'accept-language' => 'en-US,en;q=0.9',
            'cookie' => '_ym_uid=1763377197848482666; _ym_d=1763377197; PHPSESSID=60fe69b7e0a4b1dc03a80df04c9147bf; _ym_isad=2; _ym_visorc=w; CookieConsent=eyJuZWNlc3NhcnkiOnRydWUsImV4cGVyaWVuY2UiOnRydWUsInBlcmZvcm1hbmNlIjp0cnVlLCJ0cmFja2luZyI6dHJ1ZSwiYWR2ZXJ0aXNpbmciOnRydWV9',
            'priority' => 'u=1, i',
            'referer' => 'https://yak-studio.ru/web-studios',
            'sec-ch-ua' => '"Not)A;Brand";v="99", "Opera";v="113", "Chromium";v="127"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"Linux"',
            'sec-fetch-dest' => 'empty',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-site' => 'same-origin',
            'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36 OPR/113.0.0.0',
            'x-requested-with' => 'XMLHttpRequest',
        ];

        $request = new Request('GET', self::SITE_URL . "web-studios?&offset={$offset}", $headers);

        $res = $client->sendAsync($request)->wait();
        return (string)$res->getBody();
    }

    /**
     * @param string $url
     * @return string
     */
    private function getStudioDesc(string $url): string
    {
        $client = new Client();
        $headers = [
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'accept-language' => 'en-US,en;q=0.9',
            'cache-control' => 'max-age=0',
            'cookie' => '_ym_uid=1763377197848482666; _ym_d=1763377197; PHPSESSID=60fe69b7e0a4b1dc03a80df04c9147bf; _ym_isad=2; CookieConsent=eyJuZWNlc3NhcnkiOnRydWUsImV4cGVyaWVuY2UiOnRydWUsInBlcmZvcm1hbmNlIjp0cnVlLCJ0cmFja2luZyI6dHJ1ZSwiYWR2ZXJ0aXNpbmciOnRydWV9; _ym_visorc=w',
            'priority' => 'u=0, i',
            'referer' => 'https://yak-studio.ru/web-studios',
            'sec-ch-ua' => '"Not)A;Brand";v="99", "Opera";v="113", "Chromium";v="127"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"Linux"',
            'sec-fetch-dest' => 'document',
            'sec-fetch-mode' => 'navigate',
            'sec-fetch-site' => 'same-origin',
            'sec-fetch-user' => '?1',
            'upgrade-insecure-requests' => '1',
            'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36 OPR/113.0.0.0'
        ];
        $request = new Request('GET', $url, $headers);
        $res = $client->sendAsync($request)->wait();
        return (string)$res->getBody();
    }

    /**
     * @param array $studio
     * @return void
     */
    private function saveCSV(array $studio): void
    {
        $name = 'parser_yak_studio_' . date('Y_m_d_H_i_s') . '.csv';

        $fp = fopen(self::DIT_FILE_PARSER . $name, 'w+');

        // Заголовки
        fputcsv($fp, array_keys($studio[0]), ';');

        // Данные
        foreach ($studio as $row) {
            fputcsv($fp, $row, ';');
        }

        fclose($fp);
    }
}
