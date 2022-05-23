<?php

namespace Test\unit;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\entity\UploadJobStatus;
use Audiens\AppnexusClient\entity\UploadTicket;
use Audiens\AppnexusClient\exceptions\UploadException;
use Audiens\AppnexusClient\service\UserUpload;
use Doctrine\Common\Cache\VoidCache;
use Prophecy\Argument;
use Test\TestCase;

/**
 * Class UserUploadTest
 */
class UserUploadTest extends TestCase
{

    /**
     * @test
     */
    public function upload_will_throw_an_exception_if_the_file_string_is_empty()
    {

        $client = $this->prophet->prophesize(Auth::class);

        $userUpload = new UserUpload($client->reveal(), new VoidCache());

        $this->expectException(UploadException::class);

        $userUpload->upload(123456, '');

    }

    /**
     * @test
     */
    public function get_job_status_will_return_a_job_status()
    {

        $client = $this->prophet->prophesize(Auth::class);
        $userUpload = new UserUpload($client->reveal(), new VoidCache());

        $uploadTicket = new UploadTicket();
        $uploadTicket->setMemberId(123456);
        $uploadTicket->setJobId('a_job_id');

        $fakeResponse = $this->getFakeResponse($this->getCompletedJob());

        $client->request('GET', UserUpload::BASE_URL."?member_id=123456&job_id=a_job_id")->willReturn($fakeResponse);

        $uploadJobStatus = $userUpload->getJobStatus($uploadTicket);

        $this->assertTrue($uploadJobStatus->isCompeted());

    }

    /**
     * @test
     */
    public function get_job_status_on_failed_response()
    {

        $client = $this->prophet->prophesize(Auth::class);
        $userUpload = new UserUpload($client->reveal(), new VoidCache());

        $uploadTicket = new UploadTicket();
        $uploadTicket->setMemberId(123456);
        $uploadTicket->setJobId('a_job_id');

        $fakeResponse = $this->getFakeResponse($this->getFailedResponse());
        $client->request(Argument::any(), Argument::any())->willReturn($fakeResponse);

        $this->expectException(UploadException::class);

        $userUpload->getJobStatus($uploadTicket);

    }

    /**
     * @test
     */
    public function get_upload_hystory_on_failed_response()
    {

        $client = $this->prophet->prophesize(Auth::class);
        $userUpload = new UserUpload($client->reveal(), new VoidCache());

        $fakeResponse = $this->getFakeResponse($this->getFailedResponse());
        $client->request(Argument::any(), Argument::any())->willReturn($fakeResponse);

        $this->expectException(UploadException::class);

        $userUpload->getUploadHistory(1, 0, 3);

    }

    /**
     * @test
     */
    public function get_upload_hystory_will_return_an_array_of_upload_status()
    {

        $client = $this->prophet->prophesize(Auth::class);
        $userUpload = new UserUpload($client->reveal(), new VoidCache());

        $fakeResponse = $this->getFakeResponse($this->getUploadHystory());

        $client->request('GET', UserUpload::BASE_URL."?member_id=1&start_element=0&num_elements=3")->willReturn(
            $fakeResponse
        );

        $uploadJobStatuses = $userUpload->getUploadHistory(1, 0, 3);

        foreach ($uploadJobStatuses as $uploadJobStatus) {
            $this->assertInstanceOf(UploadJobStatus::class, $uploadJobStatus);
        }

    }

    /**
     * @test
     */
    public function get_upload_ticker_will_return_an_upload_ticket()
    {

        $client = $this->prophet->prophesize(Auth::class);
        $userUpload = new UserUpload($client->reveal(), new VoidCache());

        $fakeResponse = $this->getFakeResponse($this->getUploadTicket());

        $client->request('POST', UserUpload::BASE_URL."?member_id=1")->willReturn($fakeResponse);

        $uploadTicket = $userUpload->getUploadTicket(1);

        $this->assertNotEmpty($uploadTicket->getJobId());

    }

}
