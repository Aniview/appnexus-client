<?php

namespace Test\unit;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\entity\ReportStatus;
use Audiens\AppnexusClient\entity\ReportTicket;
use Audiens\AppnexusClient\exceptions\ReportException;
use Audiens\AppnexusClient\service\Report;
use Doctrine\Common\Cache\VoidCache;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Argument;
use Test\TestCase;

/**
 * Class ReportTest
 */
class ReportTest extends TestCase
{

    /**
     * @test
     */
    public function get_report_ticket_will_return_a_report_ticket()
    {

        $client = $this->prophet->prophesize(Auth::class);

        $fakeResponse = $this->getFakeResponse($this->getReportTicket());

        $client->request('POST', Report::BASE_URL, Argument::any())->willReturn($fakeResponse);

        $report = new Report($client->reveal(), new VoidCache());

        $reportTicket = $report->getReportTicket();

        $this->assertEquals('3e1f487cc75298a30032998c4a4b8d6c', $reportTicket->getReportId());

    }

    /**
     * @test
     */
    public function get_report_ticket_will_throw_an_exception_if_the_response_is_not_ok()
    {

        $client = $this->prophet->prophesize(Auth::class);

        $fakeResponse = $this->getFakeResponse($this->getFailedResponse());

        $client->request('POST', Report::BASE_URL, Argument::any())->willReturn($fakeResponse);

        $report = new Report($client->reveal(), new VoidCache());

        $this->expectException(ReportException::class);

        $report->getReportTicket();

    }

    /**
     * @test
     */
    public function get_report_status_will_return_a_status_object()
    {

        $client = $this->prophet->prophesize(Auth::class);

        $fakeResponse = $this->prophet->prophesize(Response::class);
        $stream = $this->prophet->prophesize(Stream::class);
        $stream->getContents()->willReturn($this->getReportResponse());
        $fakeResponse->getBody()->willReturn($stream->reveal());

        $client->request('GET', Report::BASE_URL_DOWNLOAD.'a_url', Argument::any())->willReturn($fakeResponse);

        $report = new Report($client->reveal(), new VoidCache());

        $reportStatus = new ReportStatus();

        $reportStatus->setReportId(10);
        $reportStatus->setStatus(ReportStatus::STATUS_READY);
        $reportStatus->setUrl('a_url');

        $report = $report->getReport($reportStatus);

        $this->assertEquals([1, 1, 1, 1, 1, 1, 1, 1, 1, 1], $report[1]);

    }

    /**
     * @test
     */
    public function get_report_status_will_return_an_object_containing_the_downaload_id()
    {

        $client = $this->prophet->prophesize(Auth::class);

        $uploadTicket = new ReportTicket();
        $uploadTicket->setReportId('_a_job_id');

        $fakeResponse = $this->getFakeResponse($this->getReportStatus());

        $client->request('GET', Argument::any(), Argument::any())->willReturn($fakeResponse);

        $report = new Report($client->reveal(), new VoidCache());

        $reportTicket = $report->getReportStatus($uploadTicket);

        $this->assertEquals('report-download?id=4b22a2c9a361f6d8a8feb99c10745a66', $reportTicket->getUrl());

    }
}
