<?php

namespace App\Tests\Controller;

/**
 * @Route("/admin")
 */
class ElasticsearchLicenseControllerTest extends AbstractAppControllerTest
{
    /**
     * @Route("/license", name="license")
     */
    public function testIndex()
    {
        $this->client->request('GET', '/admin/license');

        if (false == $this->callManager->checkVersion('6.0')) {
            $this->assertResponseStatusCodeSame(403);
        } else {
            $this->assertResponseStatusCodeSame(200);
            $this->assertPageTitleSame('License');
        }
    }
}
