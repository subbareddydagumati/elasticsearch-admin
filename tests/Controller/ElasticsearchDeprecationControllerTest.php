<?php

namespace App\Tests\Controller;

/**
 * @Route("/admin")
 */
class ElasticsearchDeprecationControllerTest extends AbstractAppControllerTest
{
    /**
     * @Route("/deprecations", name="deprecations")
     */
    public function testIndex()
    {
        $this->client->request('GET', '/admin/deprecations');

        if (false == $this->callManager->hasFeature('deprecations')) {
            $this->assertResponseStatusCodeSame(403);
        } else {
            $this->assertResponseStatusCodeSame(200);
            $this->assertPageTitleSame('Deprecations');
            $this->assertSelectorTextSame('h1', 'Deprecations');
        }
    }
}
