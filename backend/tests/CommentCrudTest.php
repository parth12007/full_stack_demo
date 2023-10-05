<?php

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use App\Models\Comment;

class CrudControllerTest extends TestCase
{

    protected $comment;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');

        $this->comment = Comment::factory()->create();
    }

    /** @test */
    public function it_can_list_comments()
    {

        // Make a GET request to your 'lists' route
        $this->get('/api/crud/lists');
        $this->seeStatusCode(Response::HTTP_OK);
    }

    /** @test */
    public function it_can_add_comment()
    {
        // Define your request data
        $requestData = [
            'request_data' => [
                "client_id" => $this->comment->client_id,
                "email" => $this->comment->email,
                "name" => $this->comment->name,
                "comment" => $this->comment->comment,
                "phone_number" => $this->comment->phone_number
            ],
        ];
        // Make a POST request to your 'add' route
        $response = $this->post('/api/crud/add', $requestData);
        $response->seeStatusCode(Response::HTTP_OK);
    }

    /** @test */
    public function it_can_get_comment_by_id()
    {
        // Make a GET request to your 'get' route with the comment ID
        $response = $this->get('/api/crud/get/'.$this->comment->id);

        $response->seeStatusCode(Response::HTTP_OK);
    }

    /** @test */
    public function it_can_update_comment()
    {
        // Define your update request data
        $updateData = [
            'request_data' => [
                "client_id" => "fd73bbbb-9f3a-4fa9-8485-193f3fb688ff",
                "email" => "test-edit@gmail.com",
                "name" => "test edit",
                "comment" => "test edit comment",
                "phone_number" => "9876543210"
            ],
        ];

        // Make a POST request to your 'update' route with the comment ID
        $response = $this->put('/api/crud/update/'.$this->comment->id, $updateData);

        $response->seeStatusCode(Response::HTTP_OK);
    }

    /** @test */
    public function it_can_delete_comment()
    {
        // Make a DELETE request to your 'delete' route with the comment ID
        $response = $this->delete('/api/crud/delete/'.$this->comment->id);

        $response->seeStatusCode(Response::HTTP_OK);
    }

}
