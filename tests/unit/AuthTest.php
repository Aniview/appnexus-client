<?php

namespace Test\unit;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\authentication\AuthStrategyInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Argument;
use Test\TestCase;

/**
 * Class AuthTest
 */
class AuthTest extends TestCase
{

    /**
     * @test
     */
    public function should_append_the_authorization_token_when_performing_any_request()
    {

        $username = 'sample_username';
        $password = 'sample_password';
        $token = 'a_sample_token123456789';

        $dummyStream = $this->prophet->prophesize(Stream::class);
        $dummyStream->getContents()->willReturn("{'response:{}'}");
        $dummyStream->rewind()->shouldBeCalled();

        $dummyResponse = $this->prophet->prophesize(Response::class);
        $dummyResponse->getBody()->willReturn($dummyStream->reveal());

        $authStrategy = $this->prophet->prophesize(AuthStrategyInterface::class);

        $authStrategy->authenticate($username, $password, Argument::any())->willReturn($token)->shouldBeCalled();

        $expectedRequestOptions = [
            'headers' => [
                'Authorization' => $token,
            ],
        ];

        $client = $this->prophet->prophesize(ClientInterface::class);
        $client->request('POST', 'random_url', $expectedRequestOptions)->willReturn($dummyResponse->reveal())->shouldBeCalled();

        $auth = new Auth($username, $password, $client->reveal(), $authStrategy->reveal());
        $auth->request('POST', 'random_url', []);


    }

    /**
     * @param string $token
     *
     * @return Response
     */
    protected function getTokenResponse($token)
    {

        $responseBody = json_encode(
            [
                'response' => [
                    'token' => $token,
                ],
            ]
        );

        return new Response(200, [], $responseBody);

    }

}
