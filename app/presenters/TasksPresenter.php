<?php

namespace App\Presenters;

use App\model\Entities\AEntity;
use App\model\Entities\Task;
use App\model\Guards\LoginGuard;
use Exception;

class TasksPresenter extends AbstractAPIPresenter {

    public function startup() {
        $this->addPresenterGuard(new LoginGuard($this));
        parent::startup();
    }

    protected function getEntityClass(): string {
        return Task::class;
    }

    /**
     * @param AEntity|Task $entity
     * @return bool
     */
    protected function isAllowed(AEntity $entity): bool {
        return $entity->userId === $this->getUser()->getId();
    }

    public function actionMoveToUnfinished($id) {
        $task = Task::get($id);
        if ($task) {
            $task->isCompleted = false;
            Task::save($task);
        }

        $this->sendJson([]);
    }

    public function actionMoveToFinished($id) {
        $task = Task::get($id);
        if ($task) {
            $task->isCompleted = true;
            Task::save($task);
        }

        $this->sendJson([]);
    }

    public function actionGetAllUnfinished() {
        $entities = null;
        try {
            /** @var AEntity $entityClass */
            $entityClass = static::getEntityClass();
            $entities = $entityClass::getAll();

            $user = $this->getUser();

            /** @var Task $entity */
            $entities = array_filter($entities, fn($entity) => !$entity->isCompleted && $entity->userId === $user->id);
            $entities = array_values($entities);
        } catch (Exception $e) {
            $this->refused($e->getMessage());
        }

        $this->sendJson($entities);
    }

    public function actionGetAllCompleted() {
        $entities = null;
        try {
            /** @var AEntity $entityClass */
            $entityClass = static::getEntityClass();
            $entities = $entityClass::getAll();

            $user = $this->getUser();

            /** @var Task $entity */
            $entities = array_filter($entities, fn($entity) => $entity->isCompleted && $entity->userId === $user->id);
            $entities = array_values($entities);
        } catch (Exception $e) {
            $this->refused($e->getMessage());
        }

        $this->sendJson($entities);
    }
}
