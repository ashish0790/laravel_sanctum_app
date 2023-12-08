<?php

namespace Tests\Feature;

use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    /**
     * 1)List Route Not Found
     * @return void
     */
    public function test_list_Route_Not_Found()
    {
        $this->json('GET', '/api/employee_list')->assertStatus(404);
    }

    /**
     * 2) create employee data with correct data
     * @param string $token
     * @return mixed
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */
    public function test_create_employee(string $token)
    {
        $data = [
            'name' => 'av',
            'email' => 'av1234@gmail.com',
            'designation' => 'developer',
            'number' => 1234555555,
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('POST', '/api/employees', $data)
            ->assertStatus(200);

        $response->assertJson([
            'data' => [
                'name' => 'av',
                'email' => 'av1234@gmail.com',
                'designation' => 'developer',
                'number' => 1234555555,
            ],
        ]);
        return $response->json();
    }

    /**
     * 3)Create employeeswith Empty Data
     * @param string $token
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */

    public function test_Create_employees_validation(string $token)
    {
        $data = [
            'name' => 'av',
            'email' => null,
            'designation' => 'developer',
            'number' => 1234555555,
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('POST', '/api/employees', $data)
            ->assertStatus(422);

        $response->assertJson([
            'message' => 'The email field is required.',
            'errors' => [
                'email' => ['The email field is required.'],
            ],
        ]);
    }

    /**
     * 4)Get Employees It ById
     * @depends test_create_employee
     * @param $id
     * @param string $token
     * @return void
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */

    public function test_Get_Employees_By_Id($id, string $token)
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('GET', 'api/employees/' . $id['data']['id'])
            ->assertStatus(200);

        $response->assertJson([
            'data' => [
                'name' => 'av',
                'designation' => 'developer',
                'number' => 1234555555,
                'email' => 'av1234@gmail.com',
            ],
        ]);
    }

    /**
     * 5)Get All Employees Details
     * @param string $token
     * @return void
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */

    public function test_get_all_employee_details(string $token)
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('GET', '/api/employees')
            ->assertStatus(200);

        // $response->assertJson([
        //     'data' => [
        //         'data' => [
        //             [
        //                 'name' => 'av',
        //                 'designation' => 'developer',
        //                 'number' => 1234555555,
        //                 'email' => 'av1234@gmail.com',
        //             ],
        //         ],
        //     ],
        // ]);

        return $response->json();
    }

    /**
     * 6)Update employees With Correct Data
     * @depends test_create_employee
     * @param $id
     * @param string $token
     * @return void
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */

    public function test_Update_Employees_Type($id, string $token)
    {
        $data = [
            'name' => 'ashish',
            'email' => 'av1234@gmail.com',
            'designation' => 'developer',
            'number' => 1234555555,
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('PUT', 'api/employees/' . $id['data']['id'], $data)
            ->assertStatus(200);

        $response->assertJson([
            'data' => [
                'name' => 'ashish',
                'email' => 'av1234@gmail.com',
                'designation' => 'developer',
                'number' => 1234555555,
            ],
        ]);
        return $response->json();
    }

    /**
     * 7)Get Updated Employee details
     * @depends test_create_employee
     * @param $id
     * @param string $token
     * @return void
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */

    public function test_Get_Updated_Employees_Type($id, string $token)
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('GET', 'api/employees/' . $id['data']['id'])
            ->assertStatus(200);

        $response->assertJson([
            'data' => [
                'name' => 'ashish',
                'designation' => 'developer',
                'number' => 1234555555,
                'email' => 'av1234@gmail.com',
            ],
        ]);
    }

    /**
     * 8)Update Industries Type With Empty Data
     * @depends test_create_employee
     * @param $id
     * @param string $token
     * @return void
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */

    public function test_Update_Employees_Details_With_EmptyData($id, string $token)
    {
        $data = [
            'name' => 'av',
            'email' => null,
            'designation' => 'developer',
            'number' => 1234555555,
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('PUT', 'api/employees/' . $id['data']['id'], $data)
            ->assertStatus(422);

        $response->assertJson([
            'message' => 'The email field is required.',
            'errors' => [
                'email' => ['The email field is required.'],
            ],
        ]);
    }

    /**
     * 9)Delete Industries Type
     * @depends  test_create_employee
     * @param $id
     * @param string $token
     * @return void
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */

    public function test_Delete_Employee_Type($id, string $token)
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('DELETE', 'api/employees/' . $id['data']['id'])
            ->assertStatus(200);
    }

    /**
     * 10)Delete Industries by same id
     * @depends  test_create_employee
     * @param $id
     * @param string $token
     * @return void
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */

    public function test_Delete_Industries_Type_By_SameId($id, string $token)
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('DELETE', '/api/employee/' . $id['data']['id'])
            ->assertStatus(404);
    }

    /**
     * 12)Get Industries Type By WrongId
     * @depends test_create_employee
     * @param $id
     * @param string $token
     * @return void
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */
    public function test_Get_employee_details_By_Wrong_Id($id, string $token)
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('GET', 'api/employee/' . $id['data']['id'])
            ->assertStatus(404);
    }

    /**
     * 13)Get All Employees Details
     * @param string $token
     * @return void
     * @depends Tests\Feature\LoginTest::test_Login_Before_ChangePassword
     */

    public function test_get_again_all_employee_details(string $token)
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('GET', '/api/employees')
            ->assertStatus(200);

        //  dd($response);

        //  $response->assertJson([
        //      'data' => [
        //          'data' => [
        //              [
        //                  'name' => 'av',
        //                  'designation' => 'developer',
        //                  'number' => 1234555555,
        //                  'email' => 'av1234@gmail.com',
        //              ],
        //          ],
        //      ],
        //  ]);

        return $response->json();
    }
}
