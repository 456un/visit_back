<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;
use SplFileObject;

class MassEmailDistribution extends Command
{
    private const DIR_EMAIL_FILE = __DIR__ . '/../../../email/emails/';
    private const DIR_TEXT_FILE = __DIR__ . '/../../../email/text/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:mass-email-distribution
        {--email= : наименование CSV файла с email}
        {--text= : наименование текстового файла с содержимым email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Массовая рассылка по email';

    /** @var EmailService $emailService */
    private EmailService $emailService;

    /**
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService) {
        parent::__construct();

        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $fileNameEmails = $this->option('email');
        $fileNameText = $this->option('text');

        if (empty($fileNameEmails)) {
            $this->error('Не задан параметр --email');
            return self::FAILURE;
        }

        if (empty($fileNameText)) {
            $this->error('Не задан параметр --text');
            return self::FAILURE;
        }

        $emails = $this->getEmailsFromFile($fileNameEmails);
        if (empty($emails)) {
            $this->error('Файл CSV с перечнем email не валиден');
            return self::FAILURE;
        }

        $text = $this->getTextFromFile($fileNameText);
        if (empty($text)) {
            $this->error('Содержимое email не задано');
            return self::FAILURE;
        }

        preg_match('/\{\{\s*(.*?)\s*\}\}/u', $text, $matches);

        $title = $matches[1] ?? null;
        $body = preg_replace('/\{\{\s*.*?\s*\}\}\s*/u', '', $text);

        $title = trim($title);
        $body  = trim($body);

        if (empty($title)) {
            $this->error('Не задан заголовок для email');
            return self::FAILURE;
        }

        if (empty($body)) {
            $this->error('Не задано содержимое для email');
            return self::FAILURE;
        }

        foreach ($emails as $email) {
            if ($this->emailService->isExistsEmail($email)) {
                $this->warn($email . ' - repeat');
                continue;
            }

            if (!$this->emailService->sendEmail($email, $title, $body)) {
                $this->warn($email . ' - error');
            } else {
                $this->info($email . ' - success');
            }

            if (!$this->emailService->addEmail($email)) {
                $this->error("Ошибка добавления {$email} в БД");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Получить email из CSV файла
     *
     * @param string $name
     * @return array
     */
    private function getEmailsFromFile(string $name): array
    {
        $path = self::DIR_EMAIL_FILE . $name . '.csv';
        if (!file_exists($path)) {
            return [];
        }

        $file = new SplFileObject($path);
        $file->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::DROP_NEW_LINE
        );

        $file->setCsvControl(
            separator: ';',
        );

        $isFirst = true;
        $emails = [];
        foreach ($file as $row) {
            if ($row === [null] || empty($row) || $isFirst) {
                $isFirst = false;
                continue;
            }

            $email = $row[0] ?? null;

            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $email;
            }
        }

        return $emails;
    }

    /**
     * Получить данные для тела email
     *
     * @param string $name
     * @return string
     */
    private function getTextFromFile(string $name): string
    {
        $path = self::DIR_TEXT_FILE . $name . '.txt';
        if (!file_exists($path)) {
            return '';
        }

        return file_get_contents($path) ?? '';
    }
}
