<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\EmailsRepository;
use Exception;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailService
{
    /** @var EmailsRepository $emailsRepository */
    private EmailsRepository $emailsRepository;

    /**
     * @param EmailsRepository $emailsRepository
     */
    public function __construct(EmailsRepository $emailsRepository)
    {
        $this->emailsRepository = $emailsRepository;
    }

    /**
     * Отправка email с обычным текстом
     *
     * @param string $email
     * @param string $title
     * @param string $text
     * @return bool
     */
    public function sendEmail(string $email, string $title, string $text): bool
    {
        try {
            Mail::raw($text, function ($message) use ($email, $title) {
                $message->to($email)->subject($title);
            });
        } catch (Throwable $exception) {
            return false;
        }

        return true;
    }

    /**
     * Добавляем email в БД
     *
     * @param string[] $emails
     * @return bool
     */
    public function addEmails(array $emails): bool
    {
        foreach ($emails as $email) {
            try {
                $this->emailsRepository->addEmail($email);
            } catch (Exception) {
                return false;
            }
        }

        return true;
    }
}
