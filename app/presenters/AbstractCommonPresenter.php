<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;

abstract class AbstractCommonPresenter extends Presenter {

    public function beforeRender() {
        $this->template->logged = $this->getUser()->isLoggedIn();
        parent::beforeRender();
    }
}
