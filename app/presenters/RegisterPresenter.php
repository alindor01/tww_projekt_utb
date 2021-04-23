<?php

namespace App\Presenters;

use App\model\Entities\User;
use Exception;
use Nette\Application\AbortException;

class RegisterPresenter extends AbstractCommonPresenter {

    /**
     * @throws AbortException
     */
    public function actionRegister() {
        try {
            $request = $this->getHttpRequest();
            $login = $request->getPost("login");
            $password = $request->getPost("password");
            $name = $request->getPost("name");

            if (!$login || !$password) {
                throw new Exception("no parameters");
            }

            $users = User::getAll();
            $users = array_filter($users, fn($user) => $user->login === $login);

            if (strlen($password) < 4) {
                throw new Exception("Heslo je příliš krátké");
            }

            if (!empty($users)) {
                throw new Exception("Uživatel již existuje");
            }

            $user = new User();
            $user->name = $name;
            $user->login = $login;
            $user->password = md5($password);
            User::save($user);

        } catch (Exception $e) {
            $this->sendJson(["message" => $e->getMessage()]);
        }

        $this->sendJson("");
    }
}

