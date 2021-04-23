<?php

namespace App\Presenters;

use App\model\Entities\User;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use Nette\Security\SimpleAuthenticator;

class LoginPresenter extends Presenter {

    public function actionDefault() {
        $request = $this->getHttpRequest();
        $login = $request->getPost("login");
        $password = $request->getPost("password");
        $password = md5($password);

        if (!$login || !$password) {
            throw new \Exception("no parameters");
        }

        $users = User::getAll();
        $asArray = [];
        foreach ($users as $user) {
            $asArray[$user->login] = $user->password;
        }

        $auth = new SimpleAuthenticator($asArray);

        try {
            $userModel = $this->getUser();
            $userModel->setAuthenticator($auth);
            $userModel->login($login, $password);
            $this->sendPayload();
        } catch (AuthenticationException $e) {
            $this->sendJson(["message" => $e->getMessage()]);
        }
    }
}
