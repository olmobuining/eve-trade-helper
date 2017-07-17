<?php
/**
 * Created by PhpStorm.
 * User: hyperion
 * Date: 13/07/2017
 * Time: 07:59
 */

namespace App\OAuth;


interface ClientInterface
{
    const CONNECT = 1;
    const DELETE = 2;
    const GET = 4;
    const HEAD = 8;
    const OPTIONS = 16;
    const POST = 32;
    const PUT = 64;

    public function send(string $request_type, string $url, array $data = []);
    public function getClient();
    public function setClient($client);

}
