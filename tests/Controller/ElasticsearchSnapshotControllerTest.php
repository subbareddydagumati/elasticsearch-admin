<?php

namespace App\Tests\Controller;

/**
 * @Route("/admin")
 */
class ElasticsearchSnapshotControllerTest extends AbstractAppControllerTest
{
    /**
     * @Route("/snapshots", name="snapshots")
     */
    public function testIndex()
    {
        $this->client->request('GET', '/admin/snapshots');

        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleSame('Snapshots');
        $this->assertSelectorTextSame('h1', 'Snapshots');
        $this->assertSelectorTextContains('h3', 'List');
    }

    /**
     * @Route("/snapshots/stats", name="snapshots_stats")
     */
    public function testStats()
    {
        $this->client->request('GET', '/admin/snapshots/stats');

        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleSame('Snapshots - Stats');
        $this->assertSelectorTextSame('h1', 'Snapshots');
        $this->assertSelectorTextSame('h3', 'Stats');
    }

    /**
     * @Route("/snapshots/create", name="snapshots_create")
     */
    public function testCreate()
    {
        $this->client->request('GET', '/admin/snapshots/create');

        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleSame('Snapshots - Create snapshot');
        $this->assertSelectorTextSame('h1', 'Snapshots');
        $this->assertSelectorTextSame('h3', 'Create snapshot');
    }

    /**
     * @Route("/snapshots/{repository}/{snapshot}", name="snapshots_read")
     */
    public function testRead404()
    {
        $this->client->request('GET', '/admin/snapshots/'.uniqid().'/'.uniqid());

        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * @Route("/snapshots/{repository}/{snapshot}/restore", name="snapshots_read_restore")
     */
    public function testRestore404()
    {
        $this->client->request('GET', '/admin/snapshots/'.uniqid().'/'.uniqid().'/restore');

        $this->assertResponseStatusCodeSame(404);
    }
}
