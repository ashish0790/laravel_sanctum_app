<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    /**
     * 1)register route not found
     */

    public function test_register_route_not_found()
    {
        $this->json('POST', 'api/aas')->assertStatus(404);
    }

    /**
     * 2)register with correct data
     * @return array
     */
    public function test_user_can_register_with_valid_data()
    {
        $data = [
            'name' => 'ashish',
            'email' => 'ashishpatel1234@gmail.com',
            'password' => 'Ashishpatel@000',
            'password_confirmation' => 'Ashishpatel@000',
        ];

        $response = $this->json('POST', '/api/register', $data);
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'name' => 'ashish',
                'email' => 'ashishpatel0790@gmail.com',
            ],
        ]);

        $responseData = $response->json();
        $userId = $responseData['data']['id'];
        $name = $data['name'];
        $email = $data['email'];
        return ['id' => $userId, 'name' => $name, 'email' => $email];
    }

    /**
     * 3)register with empty data
     * @return void
     */

    public function test_user_cannot_register_with_unvalid_data()
    {
        $data = [
            'name' => null,
            'email' => null,
            'password' => null,
            'confirm_password' => null,
        ];

        $this->json('POST', '/api/register', $data)->assertStatus(422);
    }

    /**
     * 4)Login route not found
     * @return void
     */

    public function test_login_route_not_found()
    {
        $this->json('POST', 'api/aas')->assertStatus(404);
    }

    /**
     * 5)login with correct data
     * @depends  test_user_can_register_with_valid_data
     */

    public function test_Login_Before_ChangePassword()
    {
        $data = [
            'email' => 'ashishpatel0790@gmail.com',
            'password' => 'Ashishpatel@000',
        ];

        $response = $this->json('POST', '/api/login', $data);
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'name' => 'ashish',
                'email' => 'ashishpatel0790@gmail.com',
            ],
        ]);
        return $response->json('token');
    }

    /**
     * 6)passwrod chnage
     * @depends test_Login_Before_ChangePassword
     * @param string $token
     * @return void
     */

    public function test_reset_Password(string $token)
    {
        $data = [
            'current_password' => 'Ashishpatel@000',
            'new_password' => 'Ashishpatel@123',
            'new_password_confirmation' => 'Ashishpatel@123',
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('POST', 'api/loginpasswordChange', $data)
            ->assertStatus(200);

        $response->assertJson([
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * 5)login with correct data
     * @depends test_reset_Password
     */

    public function test_Login_After_Change_Password()
    {
        $data = [
            'email' => 'ashishpatel0790@gmail.com',
            'password' => 'Ashishpatel@123',
        ];

        $response = $this->json('POST', '/api/login', $data);
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'name' => 'ashish',
                'email' => 'ashishpatel0790@gmail.com',
            ],
        ]);
        return $response->json('token');
    }

    /**
     * 6)login with empty data
     * @return void
     */

    public function test_Login_With_EmptyData()
    {
        $data = [
            'email' => null,
            'password' => null,
        ];

        $this->json('POST', 'api/login', $data)->assertStatus(422);
    }

    /**
     * 7)Profile get
     * @return void
     * @depends test_Login_After_Change_Password
     * @param string $token
     */

    public function test_user_Get_Profile(string $token)
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('get', '/api/curUserDetail')
            ->assertStatus(200);

        $response->assertJson([
            'user' => [
                'name' => 'ashish',
                'email' => 'ashishpatel0790@gmail.com',
            ],
        ]);
    }

    /**
     * 8)Profile get without token
     * @return void
     * @depends test_Login_After_Change_Password
     * @param string $token
     */

    public function test_Profile_Without_Token(string $token)
    {
        $response = $this->json('get', 'api/curUserDetail')->assertStatus(401);

        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    /**
     * 9)Get Profile route not found
     * @return void
     */
    public function test_Profile_Route_Not_Found()
    {
        $this->json('GET', 'api/curUserDetai')->assertStatus(404);
    }

    /**
     * 9)user  logout
     * @depends test_Login_After_Change_Password
     * @return void
     * @param string $token
     */

    // public function test_user_can_logout(string $token)
    // {
    //         $this->withHeaders(['Authorization' => 'Bearer ' . $token])
    //         ->json('POST', 'api/logout')
    //         ->assertStatus(200);
    // }
}
