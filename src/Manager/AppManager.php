<?php

namespace App\Manager;

use App\Manager\AbstractAppManager;

class AppManager extends AbstractAppManager
{
    private $version = '1.0';

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getIndices(): array
    {
        return [
            '.elasticsearch-admin-users',
            '.elasticsearch-admin-roles',
            '.elasticsearch-admin-permissions',
            '.elasticsearch-admin-subscriptions',
        ];
    }

    public function getSettings(string $index): array
    {
        return [
            'index' => [
                'number_of_shards' => 1,
                'auto_expand_replicas' => '0-1',
            ],
        ];
    }

    public function getMappings(string $index): array
    {
        switch ($index) {
            case '.elasticsearch-admin-users':
                return [
                    'properties' => [
                        'email' => [
                            'type' => 'keyword',
                        ],
                        'password' => [
                            'type' => 'keyword',
                        ],
                        'roles' => [
                            'type' => 'keyword',
                        ],
                        'created_at' => [
                            'type' => 'date',
                            'format' => 'yyyy-MM-dd HH:mm:ss',
                        ],
                    ],
                ];
            case '.elasticsearch-admin-roles':
                return [
                    'properties' => [
                        'name' => [
                            'type' => 'keyword',
                        ],
                        'created_at' => [
                            'type' => 'date',
                            'format' => 'yyyy-MM-dd HH:mm:ss',
                        ],
                    ],
                ];
            case '.elasticsearch-admin-permissions':
                return [
                    'properties' => [
                        'role' => [
                            'type' => 'keyword',
                        ],
                        'module' => [
                            'type' => 'keyword',
                        ],
                        'permission' => [
                            'type' => 'keyword',
                        ],
                        'created_at' => [
                            'type' => 'date',
                            'format' => 'yyyy-MM-dd HH:mm:ss',
                        ],
                    ],
                ];
            case '.elasticsearch-admin-subscriptions':
                return [
                    'properties' => [
                        'user_id' => [
                            'type' => 'keyword',
                        ],
                        'endpoint' => [
                            'type' => 'keyword',
                        ],
                        'public_key' => [
                            'type' => 'keyword',
                        ],
                        'authentication_secret' => [
                            'type' => 'keyword',
                        ],
                        'content_encoding' => [
                            'type' => 'keyword',
                        ],
                        'ip' => [
                            'type' => 'keyword',
                        ],
                        'os' => [
                            'type' => 'keyword',
                        ],
                        'client' => [
                            'type' => 'keyword',
                        ],
                        'created_at' => [
                            'type' => 'date',
                            'format' => 'yyyy-MM-dd HH:mm:ss',
                        ],
                    ],
                ];
        }
    }
}
