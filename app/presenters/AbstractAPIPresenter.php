<?php

namespace App\Presenters;

use App\model\Entities\AEntity;
use App\model\Guards\IGuard;
use Exception;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;

abstract class AbstractAPIPresenter extends Presenter {
    /** @var IGuard[] $guard */
    private array $guards = [];

    public function startup() {
        parent::startup();
        foreach ($this->guards as $guard) {
            $guard->protect();
        }
    }

    public function actionEdit() {
        try {
            $entity = $this->getParameter("entity", []);
            $entity["user"] = $this->getUser()->getId();

            /** @var AEntity $entityClass */
            $entityClass = static::getEntityClass();
            $entity = $entityClass::fromArray($entity);

            if (!$validationResult = $entity->isValid()) {
                $this->notValidResponse($entity, $validationResult);
            }

            if (!$this->isAllowed($entity)) {
                throw new Exception("Cannot edit entity");
            }

            $savedEntity = $entityClass::save($entity);
        } catch (Exception $e) {
            $this->refused($e->getMessage());
        }

        $this->sendJson($savedEntity);
    }

    public function actionGet($id) {
        $entity = null;
        try {
            /** @var AEntity $entityClass */
            $entityClass = static::getEntityClass();
            $entity = $entityClass::get($id);

            if (!$this->isAllowed($entity)) {
                throw new Exception("Not allowed to read");
            }

        } catch (Exception $e) {
            $this->refused($e->getMessage());
        }

        $this->sendJson($entity);
    }

    protected function isAllowed(AEntity $entity): bool {
        return true;
    }

    public function actionGetAll() {
        $entities = null;
        try {
            /** @var AEntity $entityClass */
            $entityClass = static::getEntityClass();
            $entities = $entityClass::getAll();
            $entities = array_filter($entities, fn($entity) => $this->isAllowed($entity));

        } catch (Exception $e) {
            $this->refused($e->getMessage());
        }

        $this->sendJson($entities);
    }

    public function actionDelete($id) {
        try {
            /** @var AEntity $entityClass */
            $entityClass = static::getEntityClass();
            $entityClass::delete($id);
        } catch (Exception $e) {
            $this->refused($e->getMessage());
        }
        $this->sendJson([]);
    }

    protected function addPresenterGuard(IGuard $guard): void {
        $this->guards[] = $guard;
    }

    public function refused(string $message) {
        $httpResponse = $this->getHttpResponse();
        $httpResponse->setCode(403);

        $response = new JsonResponse([$message]);
        $this->sendResponse($response);
    }

    public function noData() {
        $httpResponse = $this->getHttpResponse();
        $httpResponse->setCode(404);

        $response = new JsonResponse([]);
        $this->sendResponse($response);
    }

    public function notValidResponse($entity, $validationResult) {
        $httpResponse = $this->getHttpResponse();
        $httpResponse->setCode(405);

        $response = new JsonResponse([$entity, $validationResult]);
        $this->sendResponse($response);
    }

    abstract protected function getEntityClass(): string;
}
