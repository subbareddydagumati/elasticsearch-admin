<?php

namespace App\Controller;

use App\Controller\AbstractAppController;
use App\Manager\ElasticsearchIndexManager;
use App\Model\CallRequestModel;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @Route("/admin")
 */
class AppIndexDatabaseImportController extends AbstractAppController
{
    public function __construct(ElasticsearchIndexManager $elasticsearchIndexManager)
    {
        $this->elasticsearchIndexManager = $elasticsearchIndexManager;
    }

    /**
     * @Route("/{index}/database-import/connect", name="index_database_import_connect")
     */
    public function connect(Request $request, string $index): JsonResponse
    {
        $index = $this->elasticsearchIndexManager->getByName($index);

        if (false == $index) {
            throw new NotFoundHttpException();
        }

        if (true == $index->isSystem()) {
            throw new AccessDeniedHttpException();
        }

        $fields = $request->request->all();

        try {
            $conn = $this->getConnection($fields);

            $sql = $fields['query'].' LIMIT 1';
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $columns = [];
            while ($row = $stmt->fetch()) {
                foreach ($row as $k => $v) {
                    $columns[] = $k;
                }
            }

            $json = [
                'error' => false,
                'columns' => $columns,
            ];
        } catch (\Exception $e) {
            $json = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }

        return new JsonResponse($json, JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/{index}/database-import/mappings", name="index_database_import_mappings")
     */
    public function mappings(Request $request, string $index): JsonResponse
    {
        $index = $this->elasticsearchIndexManager->getByName($index);

        if (false == $index) {
            throw new NotFoundHttpException();
        }

        if (true == $index->isSystem()) {
            throw new AccessDeniedHttpException();
        }

        $fields = $request->request->all();

        try {
            $conn = $this->getConnection($fields);

            $sql = $fields['query'];//.' LIMIT 1'
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $body = '';

            while ($row = $stmt->fetch()) {
                $id = false;
                $type = false;
                $line = [];

                if (true == isset($fields['_id']) && '' != $fields['_id']) {
                    $id = $row[$fields['_id']];
                }

                foreach ($index->getMappingsFlat() as $field => $mapping) {
                    if (true == isset($fields[$field]) && '' != $fields[$field]) {
                        $line[$field] = $row[$fields[$field]];
                    }
                }

                if ($id) {
                    if ($type) {
                        $body .= json_encode(['index' => ['_id' => $id, '_type' => $type]])."\r\n";
                    } else {
                        $body .= json_encode(['index' => ['_id' => $id]])."\r\n";
                    }
                } else {
                    $body .= json_encode(['index' => (object)[]])."\r\n";
                }

                $body .= json_encode($line)."\r\n";
            }

            $callRequest = new CallRequestModel();
            $callRequest->setMethod('POST');
            $callRequest->setPath($index->getName().'/_bulk');
            $callRequest->setBody($body);
            $callResponse = $this->callManager->call($callRequest);
            $parameters['response'] = $callResponse->getContent();

            $callResponse = $this->elasticsearchIndexManager->refreshByName($index->getName());

            $json = [
                'error' => false,
            ];
        } catch (\Exception $e) {
            $json = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }

        return new JsonResponse($json, JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/{index}/database-import", name="index_database_import")
     */
    public function index(Request $request, string $index): Response
    {
        $index = $this->elasticsearchIndexManager->getByName($index);

        if (false == $index) {
            throw new NotFoundHttpException();
        }

        if (true == $index->isSystem()) {
            throw new AccessDeniedHttpException();
        }

        $allowedDrivers = [];

        $availableDrivers = \PDO::getAvailableDrivers();
        if (true == in_array('mysql', $availableDrivers)) {
            $allowedDrivers[] = 'mysql';
        }
        if (true == in_array('pgsql', $availableDrivers)) {
            $allowedDrivers[] = 'pgsql';
        }

        return $this->renderAbstract($request, 'Modules/app_index_database_import/app_index_database_import_index.html.twig', [
            'index' => $index,
            'drivers' => $allowedDrivers,
        ]);
    }

    private function getConnection($fields)
    {
        $connectionParams = [
            'dbname' => $fields['dbname'],
            'user' => $fields['user'],
            'password' => $fields['password'],
            'host' => $fields['host'],
            'driver' => 'pdo_'.$fields['driver'],
        ];

        return \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
    }
}
