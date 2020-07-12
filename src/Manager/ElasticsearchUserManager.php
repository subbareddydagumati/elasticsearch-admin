<?php

namespace App\Manager;

use App\Manager\AbstractAppManager;
use App\Manager\CallManager;
use App\Model\CallRequestModel;
use App\Model\CallResponseModel;
use App\Model\ElasticsearchUserModel;
use Symfony\Component\HttpFoundation\Response;

class ElasticsearchUserManager extends AbstractAppManager
{
    public function getByName(string $name): ?ElasticsearchUserModel
    {
        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_security/user/'.$name);
        $callResponse = $this->callManager->call($callRequest);

        if (Response::HTTP_NOT_FOUND == $callResponse->getCode()) {
            $userModel = null;
        } else {
            $user = $callResponse->getContent();
            $userNice = $user[key($user)];
            $userNice['name'] = key($user);

            $userModel = new ElasticsearchUserModel();
            $userModel->convert($userNice);
        }

        return $userModel;
    }

    public function getAll(): array
    {
        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_security/user');
        $callResponse = $this->callManager->call($callRequest);
        $results = $callResponse->getContent();

        foreach ($results as $k => $row) {
            $row['name'] = $k;
            $userModel = new ElasticsearchUserModel();
            $userModel->convert($row);
            $users[$k] = $userModel;
        }
        ksort($users);

        return $users;
    }

    public function send(ElasticsearchUserModel $userModel): CallResponseModel
    {
        $json = $userModel->getJson();
        $callRequest = new CallRequestModel();
        $callRequest->setMethod('PUT');
        $callRequest->setPath('/_security/user/'.$userModel->getName());
        $callRequest->setJson($json);

        return $this->callManager->call($callRequest);
    }

    public function deleteByName(string $name): CallResponseModel
    {
        $callRequest = new CallRequestModel();
        $callRequest->setMethod('DELETE');
        $callRequest->setPath('/_security/user/'.$name);

        return $this->callManager->call($callRequest);
    }

    public function enableByName(string $name): CallResponseModel
    {
        $callRequest = new CallRequestModel();
        $callRequest->setMethod('PUT');
        $callRequest->setPath('/_security/user/'.$name.'/_enable');

        return $this->callManager->call($callRequest);
    }

    public function disableByName(string $name): CallResponseModel
    {
        $callRequest = new CallRequestModel();
        $callRequest->setMethod('PUT');
        $callRequest->setPath('/_security/user/'.$name.'/_disable');

        return $this->callManager->call($callRequest);
    }

    public function selectUsers()
    {
        $users = [];

        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_security/user');
        $callResponse = $this->callManager->call($callRequest);
        $rows = $callResponse->getContent();

        foreach ($rows as $k => $row) {
            $users[] = $k;
        }

        sort($users);

        return $users;
    }
}
