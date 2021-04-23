<?php

namespace App\model\Guards;

interface IGuard {
    /**
     * Take measures like redirect
     */
    public function protect(): void;

    /**
     * Decides whether you have rights to use presenter
     * @return bool
     */
    public function isAllowed(): bool;
}
