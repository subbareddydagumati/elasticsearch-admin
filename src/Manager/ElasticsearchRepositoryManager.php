<?php

namespace App\Manager;

use App\Manager\CallManager;
use App\Model\CallModel;

class ElasticsearchRepositoryManager
{
    /**
     * @required
     */
    public function setCallManager(CallManager $callManager)
    {
        $this->callManager = $callManager;
    }

    public function selectRepositories()
    {
        $repositories = [];

        $call = new CallModel();
        $call->setPath('/_cat/repositories');
        $call->setQuery(['s' => 'id', 'h' => 'id']);
        $rows = $this->callManager->call($call);

        foreach ($rows as $row) {
            $repositories[] = $row['id'];
        }

        return $repositories;
    }
}