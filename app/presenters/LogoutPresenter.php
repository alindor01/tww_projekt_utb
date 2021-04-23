<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;

final class LogoutPresenter extends Presenter {
    public function actionDefault() {
        $this->getUser()->logout();
        $this->redirect("Homepage:default");
    }
}
