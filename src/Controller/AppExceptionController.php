<?php

namespace App\Controller;

use App\Kernel;
use App\Controller\AbstractAppController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class AppExceptionController extends AbstractAppController
{
    public function read(Request $request, FlattenException $exception, RequestStack $requestStack)
    {
        $masterRequest = $requestStack->getMasterRequest();

        $parameters = [
            'locale' => $masterRequest->getLocale(),
            'route' => $masterRequest->get('_route'),
        ];

        $codes = [401, 403, 404, 405, 500, 503];

        if (true == in_array($exception->getStatusCode(), $codes)) {
            if (500 == $exception->getStatusCode()) {
                $parameters['message'] = $exception->getMessage();
                $parameters['file'] = $exception->getFile();
                $parameters['line'] = $exception->getLine();

                $parameters['php_version'] = phpversion();
                $parameters['symfony_version'] = Kernel::VERSION;

            }
            return $this->renderAbstract($request, 'Modules/exception/exception_'.$exception->getStatusCode().'.html.twig', $parameters);
        }
    }
}