<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;
use SplFileObject;

class AddEmailDB extends Command
{
    private const DIR_EMAIL_FILE = __DIR__ . '/../../../email/emails/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:add-email-from-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавление всех email из директории в БД';

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
     * @return int
     */
    public function handle(): int
    {
        $emails = $this->getEmailsFromFiles();
        if (empty($emails)) {
            $this->error('Файлы с email не обнаружены');
            return self::FAILURE;
        }

        if (!$this->emailService->addEmails($emails)) {
            $this->error('Ошибка добавления email в БД');

            return self::FAILURE;
        }

        $this->info('Email добавлены в БД');

        return self::SUCCESS;
    }

    /**
     * Получить email из CSV файла
     *
     * @return array
     */
    private function getEmailsFromFiles(): array
    {
        $files = scandir(self::DIR_EMAIL_FILE);

        $emails = [];
        foreach ($files as $name) {
            if ($name === '.' || $name === '..') {
                continue;
            }

            $path = self::DIR_EMAIL_FILE . $name;
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
        }

        return $emails;
    }
}
