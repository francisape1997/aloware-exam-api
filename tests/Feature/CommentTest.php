<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    private const POST_ID = 1;
    private const HEADERS = [
        'Accept' => 'application/json',
    ];

    public function test_add_comment_to_post_no_parent()
    {
        $this->seed();

        $this->post('api/comment',
        [
            'post_id'     => self::POST_ID,
            'title'       => $this->faker()->word(),
            'description' => $this->faker()->sentence(),
        ])
        ->assertStatus(200);
    }

    public function test_add_comment_to_post_with_parent()
    {
        $this->seed();

        $response = $this->post('api/comment',
        [
            'post_id'     => self::POST_ID,
            'title'       => $this->faker()->word(),
            'description' => $this->faker()->sentence(),
        ])
        ->assertStatus(200)
        ->decodeResponseJson();
        
        $commentId = $response['id'];

        $this->post('api/comment',
        [
            'post_id'     => self::POST_ID,
            'comment_id'  => $commentId,
            'title'       => $this->faker()->word(),
            'description' => $this->faker()->sentence(),
        ])
        ->assertStatus(200);
    }

    /**
     * Test replying to a comment that is on the 3rd layer
     */
    public function test_reply_to_comment_with_three_layers()
    {
        $this->seed();

        # Top Comment
        $response = $this->post('api/comment',
        [
            'post_id'     => self::POST_ID,
            'title'       => $this->faker()->word(),
            'description' => $this->faker()->sentence(),
        ])
        ->assertStatus(200)
        ->decodeResponseJson();

        # 1st comment id
        $commentId = $response['id'];

        # 1st Layer
        $response = $this->post('api/comment',
        [
            'post_id'     => self::POST_ID,
            'comment_id'  => $commentId,
            'title'       => $this->faker()->word(),
            'description' => $this->faker()->sentence(),
        ])
        ->assertStatus(200)
        ->decodeResponseJson();

        # 2nd comment id
        $commentId = $response['id'];

        # 2nd Layer
        $response = $this->post('api/comment',
        [
            'post_id'     => self::POST_ID,
            'comment_id'  => $commentId,
            'title'       => $this->faker()->word(),
            'description' => $this->faker()->sentence(),
        ])
        ->assertStatus(200)
        ->decodeResponseJson();

        # 3rd comment id
        $commentId = $response['id'];

        # 3rd Layer
        $response = $this->post('api/comment',
        [
            'post_id'     => self::POST_ID,
            'comment_id'  => $commentId,
            'title'       => $this->faker()->word(),
            'description' => $this->faker()->sentence(),
        ])
        ->assertStatus(200)
        ->decodeResponseJson();

        $commentId = $response['id'];

        # Reply to 3rd layer comment
        $this->post('api/comment',
        [
            'post_id'     => self::POST_ID,
            'comment_id'  => $commentId,
            'title'       => $this->faker()->word(),
            'description' => $this->faker()->sentence(),
        ])
        ->assertStatus(403);
    }

    public function test_add_comment_empty_title()
    {
        $this->seed();

        $this->post('api/comment',
        [
            'post_id'     => self::POST_ID,
            'title'       => null,
            'description' => $this->faker()->sentence(),
        ],
        self::HEADERS)
        ->assertStatus(422);
    }

    public function test_add_comment_empty_description()
    {
        $this->seed();

        $this->post('api/comment',
        [
            'post_id'     => self::POST_ID,
            'title'       => $this->faker()->word(),
            'description' => null,
        ],
        self::HEADERS)
        ->assertStatus(422);
    }

    public function test_add_comment_with_invalid_post_id()
    {
        $this->seed();

        $this->post('api/comment',
        [
            'post_id'     => 2,
            'title'       => $this->faker()->word(),
            'description' => $this->faker()->sentence(),
        ],
        self::HEADERS)
        ->assertStatus(422);
    }
}
