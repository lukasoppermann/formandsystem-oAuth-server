<?php

class ClientTest extends BasetestCase
{
    /**
     * get a token.
     */
    public function getToken()
    {
        return json_decode($this->client->post('token', [
            'form_params' => [
                'client_id' => 'test_cms_id',
                'client_secret' => 'test_cms_secret',
                'grant_type' => 'client_credentials',
                'scope' => 'client.read,client.create,client.delete,client.update',
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ])->getBody()->getContents())->data->id;
    }
    /**
     * @test
     */
    public function request_options_of_client_route()
    {
        $response = $this->client->options('client', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        $this->checkDefaultHeader($response);
        $this->checkAuthHeader($response);
        $this->checkStatusCode(self::HTTP_NO_CONTENT, $response->getStatusCode());
    }
    /**
     * @test
     */
    public function create_a_client()
    {
        $response = $this->client->post('client', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->getToken(),
            ],
            'form_params' => [
                'client_name' => 'created_by_test',
            ],
        ]);

        $this->checkDefaultHeader($response);
        $this->checkAuthHeader($response);
        $this->checkStatusCode(self::HTTP_CREATED, $response->getStatusCode());
    }
    /**
     * @test
     */
    public function failed_create_a_client()
    {
        $response = $this->client->post('client', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->getToken(),
            ],
        ]);

        $this->checkDefaultHeader($response);
        $this->checkAuthHeader($response);
        $this->checkStatusCode(self::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }
    /**
     * @test
     */
    public function request_get_client_by_id()
    {
        $response = $this->client->get('client/test_client_id', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorizer' => 'Bearer '.$this->getToken(),
            ],
        ]);
        // print_r($response);
        $this->checkDefaultHeader($response);
        $this->checkAuthHeader($response);
        $this->checkStatusCode(self::HTTP_OK, $response->getStatusCode());
    }
}
