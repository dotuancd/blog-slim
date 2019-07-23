<?php

namespace Tests\Functional;

class PostTest extends BaseTestCase
{
    public function test_guess_cannot_create_post()
    {
        $response = $this->post('/api/posts', ['content' => 'Some Content', 'title' => 'Some Title']);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertContains('Unauthorized', $response->getBody()->getContents());
    }

    public function test_user_can_create_draft_post()
    {
        $body = [];
        $response = $this->post('/api/posts', $body);
    }

    public function test_user_can_request_review_post()
    {
        $response = $this->get('/api/posts');

        $this->assertEquals(200, $response->getStatusCode());
        dd(json_decode($response->getBody() . ''));
    }

    public function test_user_can_unpublished_their_post()
    {

    }

    public function test_user_can_delete_their_post()
    {

    }

    public function test_admin_can_approve_post()
    {

    }

    public function test_admin()
    {

    }
}