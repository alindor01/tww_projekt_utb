<?php

namespace App\model\Guards;

use Nette\InvalidStateException;

class LoginGuard extends AGuard {

    public function protect(): void {
        if (!$this->isAllowed()) {
            $this->presenter->terminate();
        }
    }

    public function isAllowed(): bool {
        try {
            return $this->presenter->getUser()->isLoggedIn();
        } catch (InvalidStateException $e) {
            return false;
        }
    }
}
