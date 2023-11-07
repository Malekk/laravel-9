<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSendEmailWithValidData()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;
        $data = [
            [
                'email' => 'recipient1@example.com',
                'subject' => 'Test Subject 1',
                'body' => 'This is a test email message 1.'
            ],
            [
                'email' => 'recipient2@example.com',
                'subject' => 'Test Subject 2',
                'body' => 'This is a test email message 2.'
            ]
        ];

        $response = $this->actingAs($user)->json(
            'POST',
            'api/' . $user->id . '/send',
            [
                'emails' => $data,
                'api_token' => $token
            ]);
        $response->assertStatus(200);
    }

    public function testSendEmailWithUnauthorizedUser()
    {
        $user = User::factory()->create();
        $data = [
            [
                'email' => 'recipient1@example.com',
                'subject' => 'Test Subject 1',
                'body' => 'This is a test email message 1.'
            ],
            [
                'email' => 'recipient2@example.com',
                'subject' => 'Test Subject 2',
                'body' => 'This is a test email message 2.'
            ]
        ];

        $response = $this->actingAs($user)->json(
            'POST',
            'api/' . $user->id . '/send',
            [
                'emails' => $data
            ]);
        $response->assertStatus(403);
    }
}
