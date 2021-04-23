<?php

namespace App\model\Guards;

use Nette\Application\UI\Presenter;

abstract class AGuard implements IGuard {
    /** @var Presenter  */
    protected Presenter $presenter;

    public function __construct(Presenter $presenter) {
        $this->presenter = $presenter;
    }
}
