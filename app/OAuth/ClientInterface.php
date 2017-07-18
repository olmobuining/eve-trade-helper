<?php
namespace App\OAuth;

interface ClientInterface
{
    const REQUEST_TYPES = [
        'CONNECT',
        'DELETE',
        'GET',
        'HEAD',
        'OPTIONS',
        'POST',
        'PUT',
    ];

    public function request(string $request_type, string $url, array $data = []);

    public function get();

    public function build();

    public function getClient();

    public function setClient($client);

}
