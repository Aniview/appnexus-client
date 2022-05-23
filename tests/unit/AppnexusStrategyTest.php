<?php

namespace Test\unit;

use Audiens\AppnexusClient\authentication\AdnxStrategy;
use Audiens\AppnexusClient\authentication\AppnexusStrategy;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Argument;
use Prophecy\Prophet;
use Test\TestCase;

/**
 * Class AppnexusStrategyTest
 */
class AppnexusStrategyTest extends TestCase
{

    /**
     * @test
     */
    public function should_make_a_post_to_the_auth_endpoint()
    {
        $username = 'sample_username';
        $password = 'sample_password';
        $token = 'a_sample_token123456789';

        $fakeResponseContent = [
            "response" => [
                "token" => $token,
            ],
        ];

        $cache = $this->prophet->prophesize(Cache::class);

        $cache->contains(Argument::any())->willReturn(false)->shouldBeCalled();
        $cache->save(Argument::any(), $token, Argument::type('integer'))->shouldBeCalled();

        $dummyStream = $this->prophet->prophesize(Stream::class);
        $dummyStream->getContents()->willReturn(json_encode($fakeResponseContent));
        $dummyStream->rewind()->shouldBeCalled();

        $dummyResponse = $this->prophet->prophesize(Response::class);
        $dummyResponse->getBody()->willReturn($dummyStream->reveal());

        $payload = [
            'auth' => [
                'username' => $username,
                'password' => $password,
            ],
        ];

        $client = $this->prophet->prophesize(ClientInterface::class);

        $client
            ->request('POST', AppnexusStrategy::BASE_URL, ['body' => json_encode($payload)])
            ->willReturn($dummyResponse->reveal());

        $auth = new AppnexusStrategy($client->reveal(), $cache->reveal());
        $auth->authenticate($username, $password, true);


    }

}
