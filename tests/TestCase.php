<?php

namespace Test;

use Audiens\AppnexusClient\entity\UploadJobStatus;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function getFakeResponse($responseBody)
    {
        /** @var ObjectProphecy|ResponseInterface $fakeResponse */
        $fakeResponse = $this->prophesize(ResponseInterface::class);
        /** @var ObjectProphecy|Stream $stream */
        $stream = $this->prophesize(Stream::class);
        $stream->getContents()->willReturn($responseBody);
        $stream->rewind()->shouldBeCalled();
        $fakeResponse->getBody()->willReturn($stream->reveal());

        return $fakeResponse;
    }

    /**
     * @return string
     */
    protected function getReportResponse()
    {
        return "day,seller_member,publisher_id,publisher_name,publisher_code,buyer_member_id,buyer_member_name,imps,imps_delivered,seller_revenue\n
                1,1,1,1,1,1,1,1,1,1";
    }

    /**
     * @return string
     */
    protected function getCompletedJob()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'batch_segment_upload_job' => [
                        [
                            'phase' => UploadJobStatus::PHASE_COMPLETED,
                            "start_time" => '2016-04-08 08:41:16',
                            "uploaded_time" => '2016-04-08 08:41:17',
                            "validated_time" => '2016-04-08 08:41:19',
                            "completed_time" => '2016-04-08 08:41:19',
                            "error_code" => null,
                            "time_to_process" => 0.01,
                            "percent_complete" => 100,
                            "num_valid" => 88529,
                            "num_valid_user" => 0,
                            "num_invalid_format" => 0,
                            "num_invalid_user" => 0,
                            "num_invalid_segment" => 0,
                            "num_unauth_segment" => 0,
                            "num_past_expiration" => 0,
                            "num_inactive_segment" => 0,
                            "num_other_error" => 0,
                            "error_log_lines" => null,
                            "segment_log_lines" => null,
                            "created_on" => '2016-04-08 08:41:15',
                            "id" => 25450408,
                            "job_id" => 'iAMaO5SVpsdt42RIU7nIj4GXejfX1n1460104875',
                            "member_id" => 3847,
                            "last_modified" => '2016-04-08 08:41:19',
                            "upload_url" => null,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getReportTicket()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'report_id' => '3e1f487cc75298a30032998c4a4b8d6c',
                    'existing' => null,
                    'cached' => 1,
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getReportStatus()
    {
        return json_encode(
            [
                'response' => [
                    "status" => "OK",
                    "execution_status" => 'ready',
                    "report" => [
                        "name" => "Weekly SSP Revenue Report",
                        "created_on" => "2016-04-12 09:06:57",
                        "cache_hit" => 1,
                        "fact_cache_hit" => 1,
                        "fact_cache_error" => null,
                        "json_request" => "{}",
                        "header_info" => "Report type: seller_platform_billing",
                        "row_count" => null,
                        "report_size" => 131,
                        "url" => "report-download?id=4b22a2c9a361f6d8a8feb99c10745a66",
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getUploadTicket()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'batch_segment_upload_job' => [
                        "id" => 25450408,
                        "job_id" => 'iAMaO5SVpsdt42RIU7nIj4GXejfX1n1460104875',
                        "member_id" => 3847,
                        "last_modified" => '2016-04-08 08:41:19',
                        "upload_url" => 'upload_here',
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getSingleSegment()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'segment' => [
                        "id" => 5012,
                        "active" => true,
                        "description" => 'a description',
                        "member_id" => 'member_id',
                        "code" => '$code',
                        "provider" => '$provider',
                        "price" => 0.80,
                        "short_name" => 'a sample short_name',
                        "expire_minutes" => 'expire_minutes',
                        "category" => 'category',
                        "last_activity" => 'last_activity',
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getMultipleSegments()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'segments' => [
                        [
                            "id" => 123,
                            "active" => true,
                            "description" => 'a description',
                            "member_id" => 'member_id',
                            "code" => '$code',
                            "provider" => '$provider',
                            "price" => 0.80,
                            "short_name" => 'a sample short_name',
                            "expire_minutes" => 'expire_minutes',
                            "category" => 'category',
                            "last_activity" => 'last_activity',
                        ],
                        [
                            "id" => 456,
                            "active" => true,
                            "description" => 'a description',
                            "member_id" => 'member_id',
                            "code" => '$code',
                            "provider" => '$provider',
                            "price" => 0.80,
                            "short_name" => 'a sample short_name',
                            "expire_minutes" => 'expire_minutes',
                            "category" => 'category',
                            "last_activity" => 'last_activity',
                        ],
                        [
                            "id" => 789,
                            "active" => true,
                            "description" => 'a description',
                            "member_id" => 'member_id',
                            "code" => '$code',
                            "provider" => '$provider',
                            "price" => 0.80,
                            "short_name" => 'a sample short_name',
                            "expire_minutes" => 'expire_minutes',
                            "category" => 'category',
                            "last_activity" => 'last_activity',
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getSingleBillingSegment()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    "segment-billing-category" => [
                        0 => [
                            "id" => 123,
                            "segment_id" => 123,
                            "data_provider_id" => 1,
                            "data_category_id" => 1001,
                            "active" => true,
                            "member_id" => 'member_id',
                            "is_public" => true,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getMultipleBillingSegments()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'segment-billing-categories' => [
                        [
                            "id" => 123,
                            "segment_id" => 123,
                            "data_provider_id" => 1,
                            "data_category_id" => 1001,
                            "active" => true,
                            "member_id" => 'member_id',
                            "is_public" => true,
                        ],
                        [
                            "id" => 456,
                            "segment_id" => 456,
                            "data_provider_id" => 1,
                            "data_category_id" => 1001,
                            "active" => true,
                            "member_id" => 'member_id',
                            "is_public" => true,
                        ],
                        [
                            "id" => 789,
                            "segment_id" => 789,
                            "data_provider_id" => 1,
                            "data_category_id" => 1001,
                            "active" => true,
                            "member_id" => 'member_id',
                            "is_public" => true,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getFailedResponse()
    {
        return json_encode(
            [
                'response' => [
                    'error_id' => 'SYNTAX',
                    'error' => 'Invalid path /segment - member is required',
                    'error_description' => null,
                    'error_code' => null,
                    'service' => 'segment',
                    'method' => 'POST',
                ],
            ]

        );
    }

    /**
     * @return string
     */
    protected function getUploadHystory()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'batch_segment_upload_job' => [
                        [
                            'phase' => UploadJobStatus::PHASE_COMPLETED,
                            "start_time" => '2016-04-08 08:41:16',
                            "uploaded_time" => '2016-04-08 08:41:17',
                            "validated_time" => '2016-04-08 08:41:19',
                            "completed_time" => '2016-04-08 08:41:19',
                            "error_code" => null,
                            "time_to_process" => 0.01,
                            "percent_complete" => 100,
                            "num_valid" => 88529,
                            "num_valid_user" => 0,
                            "num_invalid_format" => 0,
                            "num_invalid_user" => 0,
                            "num_invalid_segment" => 0,
                            "num_unauth_segment" => 0,
                            "num_past_expiration" => 0,
                            "num_inactive_segment" => 0,
                            "num_other_error" => 0,
                            "error_log_lines" => null,
                            "segment_log_lines" => null,
                            "created_on" => '2016-04-08 08:41:15',
                            "id" => 25450408,
                            "job_id" => 'iAMaO5SVpsdt42RIU7nIj4GXejfX1n1460104875',
                            "member_id" => 3847,
                            "last_modified" => '2016-04-08 08:41:19',
                            "upload_url" => null,
                        ],
                        [
                            'phase' => UploadJobStatus::PHASE_COMPLETED,
                            "start_time" => '2016-04-08 08:41:16',
                            "uploaded_time" => '2016-04-08 08:41:17',
                            "validated_time" => '2016-04-08 08:41:19',
                            "completed_time" => '2016-04-08 08:41:19',
                            "error_code" => null,
                            "time_to_process" => 0.01,
                            "percent_complete" => 100,
                            "num_valid" => 88529,
                            "num_valid_user" => 0,
                            "num_invalid_format" => 0,
                            "num_invalid_user" => 0,
                            "num_invalid_segment" => 0,
                            "num_unauth_segment" => 0,
                            "num_past_expiration" => 0,
                            "num_inactive_segment" => 0,
                            "num_other_error" => 0,
                            "error_log_lines" => null,
                            "segment_log_lines" => null,
                            "created_on" => '2016-04-08 08:41:15',
                            "id" => 25450408,
                            "job_id" => 'iAMaO5SVpsdt42RIU7nIj4GXejfX1n1460104875',
                            "member_id" => 3847,
                            "last_modified" => '2016-04-08 08:41:19',
                            "upload_url" => null,
                        ],
                        [
                            'phase' => UploadJobStatus::PHASE_COMPLETED,
                            "start_time" => '2016-04-08 08:41:16',
                            "uploaded_time" => '2016-04-08 08:41:17',
                            "validated_time" => '2016-04-08 08:41:19',
                            "completed_time" => '2016-04-08 08:41:19',
                            "error_code" => null,
                            "time_to_process" => 0.01,
                            "percent_complete" => 100,
                            "num_valid" => 88529,
                            "num_valid_user" => 0,
                            "num_invalid_format" => 0,
                            "num_invalid_user" => 0,
                            "num_invalid_segment" => 0,
                            "num_unauth_segment" => 0,
                            "num_past_expiration" => 0,
                            "num_inactive_segment" => 0,
                            "num_other_error" => 0,
                            "error_log_lines" => null,
                            "segment_log_lines" => null,
                            "created_on" => '2016-04-08 08:41:15',
                            "id" => 25450408,
                            "job_id" => 'iAMaO5SVpsdt42RIU7nIj4GXejfX1n1460104875',
                            "member_id" => 3847,
                            "last_modified" => '2016-04-08 08:41:19',
                            "upload_url" => null,
                        ],
                    ],
                ],
            ]
        );
    }

}
