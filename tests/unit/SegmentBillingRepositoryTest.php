<?php

namespace Test\unit;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\entity\SegmentBilling;
use Audiens\AppnexusClient\repository\SegmentBillingRepository;
use Doctrine\Common\Cache\VoidCache;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Argument;
use Test\TestCase;

class SegmentBillingRepositoryTest extends TestCase
{

    /**
     * @test
     */
    public function add_will_create_a_new_segment_and_return_a_repository_response()
    {
        $client = $this->prophesize(Auth::class);

        $repository = new SegmentBillingRepository($client->reveal(), new VoidCache(), getenv('MEMBER_ID'));

        $segmentBilling = new SegmentBilling();
        $segmentBilling->setIsPublic(true);
        $segmentBilling->setDataCategoryId(1001);
        $segmentBilling->setDataProviderId(getenv('DATA_PROVIDER_ID'));
        $segmentBilling->setActive(true);

        $fakeResponse = $this->getFakeResponse($this->getSingleBillingSegment());

        $payload = [
            'segment-billing-category' => $segmentBilling->toArray(),
        ];

        $client->request('POST', Argument::any(), ['body' => json_encode($payload)])->willReturn($fakeResponse)->shouldBeCalled();

        $repositoryResponse = $repository->add($segmentBilling);

        $this->assertTrue($repositoryResponse->isSuccessful());
        $this->assertEquals(123, $segmentBilling->getId());
    }

    /**
     * @test
     */
    public function update_will_edit_an_existing_segment()
    {
        $client = $this->prophesize(Auth::class);

        $repository = new SegmentBillingRepository($client->reveal(), new VoidCache(), getenv('MEMBER_ID'));

        $segmentBilling = new SegmentBilling();
        $segmentBilling->setId(123456);

        $responseBody = json_encode(
            [
                'response' => [
                    'status' => 'OK',
                ],
            ]
        );

        $fakeResponse = $this->prophesize(Response::class);
        $stream       = $this->prophesize(Stream::class);
        $stream->getContents()->willReturn($responseBody);
        $stream->rewind()->shouldBeCalled();
        $fakeResponse->getBody()->willReturn($stream->reveal());

        $payload = [
            'segment-billing-category' => $segmentBilling->toArray(),
        ];

        $client->request('PUT', Argument::any(), ['body' => json_encode($payload)])->willReturn($fakeResponse)->shouldBeCalled();

        $repositoryResponse = $repository->update($segmentBilling);

        $this->assertTrue($repositoryResponse->isSuccessful());
    }

    /**
     * @test
     */
    public function remove_will_remove_an_existing_segment()
    {
        $client     = $this->prophesize(Auth::class);
        $repository = new SegmentBillingRepository($client->reveal(), new VoidCache(), getenv('MEMBER_ID'));

        $id = '12346';

        $responseBody = json_encode(
            [
                'response' => [
                    'status' => 'OK',
                ],
            ]
        );

        $fakeResponse = $this->prophesize(Response::class);
        $stream       = $this->prophesize(Stream::class);
        $stream->getContents()->willReturn($responseBody);
        $stream->rewind()->shouldBeCalled();
        $fakeResponse->getBody()->willReturn($stream->reveal());

        $client->request('DELETE', Argument::containingString($id))->willReturn($fakeResponse)->shouldBeCalled();

        $repositoryResponse = $repository->remove($id);

        $this->assertTrue($repositoryResponse->isSuccessful());
    }

    /**
     * @test
     */
    public function find_all_will_return_an_array_of_segments()
    {
        $client       = $this->prophesize(Auth::class);
        $repository   = new SegmentBillingRepository($client->reveal(), new VoidCache(), getenv('MEMBER_ID'));
        $fakeResponse = $this->getFakeResponse($this->getMultipleBillingSegments());

        $client->request('GET', Argument::containingString('start_element=3'))->willReturn($fakeResponse)->shouldBeCalled();

        $segments = $repository->findAll(3, 3);

        $this->assertNotEmpty($segments);
        foreach ($segments as $segment) {
            $this->assertInstanceOf(SegmentBilling::class, $segment);
        }
    }
}
