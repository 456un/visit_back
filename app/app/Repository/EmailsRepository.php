<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Emails;

class EmailsRepository
{
    /**
     * @param string $email
     * @return bool
     */
    public function addEmail(string $email): bool
    {
        if ($this->isExists($email)) {
            return false;
        }

        $emailModel = new Emails();
        $emailModel->email = strtolower($email);
        return $emailModel->save();
    }

    /**
     * @param string $email
     * @return bool
     */
    public function isExists(string $email): bool
    {
        return Emails::query()
            ->where('email', '=', strtolower($email))
            ->exists();
    }
}
