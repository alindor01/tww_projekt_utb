<?php

namespace App\model\Guards;

class OnlyAdminGuard extends AGuard {

    public function protect(): void {
        // TODO: Implement protect() method.
    }

    public function isAllowed(): bool {
        // TODO: Implement isAllowed() method.
        return true;
    }
}