<?php

namespace Test;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\authentication\SandboxStrategy;
use Audiens\AppnexusClient\facade\AppnexusFacade;
use Audiens\AppnexusClient\repository\MemberDataSharingRepository;
use Audiens\AppnexusClient\repository\SegmentBillingRepository;
use Audiens\AppnexusClient\repository\SegmentRepository;
use Audiens\AppnexusClient\service\Report;
use Audiens\AppnexusClient\service\UserUpload;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\VoidCache;
use Dotenv\Dotenv;
use GuzzleHttp\Client;

class FunctionalTestCase extends \PHPUnit\Framework\TestCase
{

    public const REQUIRED_ENV
        = [
            'USERNAME',
            'PASSWORD',
            'MEMBER_ID',
            'DATA_PROVIDER_ID',
            'DATA_CATEGORY_ID',
        ];

    protected function setUp()
    {
        if (!$this->checkEnv()) {
            $this->markTestSkipped('cannotInitialize enviroment tests will be skipped');
        }

        parent::setUp();
    }

    private function checkEnv(): bool
    {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__.'/../');
            $dotenv->load();
        } catch (\Exception $e) {
        }

        $env = true;

        foreach (self::REQUIRED_ENV as $requiredEnv) {
            if (!getenv($requiredEnv)) {
                $env = false;
            }
        }

        return $env;
    }

    protected function getAuth(bool $cacheToken = true): Auth
    {
        $cache  = $cacheToken ? new FilesystemCache('build') : null;
        $client = new Client();

        $authStrategy = new SandboxStrategy(new Client(), $cache);

        $authClient = new Auth(getenv('USERNAME'), getenv('PASSWORD'), $client, $authStrategy);

        return $authClient;
    }

    protected function getSegmentBillingRepository(bool $cacheToken = true): SegmentBillingRepository
    {
        $authClient = $this->getAuth($cacheToken);

        $segmentBillingRepository = new SegmentBillingRepository($authClient, new VoidCache(), getenv('MEMBER_ID'));
        $segmentBillingRepository->setBaseUrl(SegmentBillingRepository::SANDBOX_BASE_URL);

        return $segmentBillingRepository;
    }

    /**
     * @param bool|true $cacheToken
     *
     * @return SegmentRepository
     */
    protected function getSegmentRepository($cacheToken = true)
    {
        $authClient = $this->getAuth($cacheToken);

        $segmentRepository = new SegmentRepository($authClient, new VoidCache(), getenv('MEMBER_ID'));
        $segmentRepository->setBaseUrl(SegmentRepository::SANDBOX_BASE_URL);

        return $segmentRepository;
    }

    /**
     * @param bool|true $cacheToken
     *
     * @return MemberDataSharingRepository $mbRepository
     */
    protected function getMemberDataSharingRepository($cacheToken = true)
    {
        $authClient = $this->getAuth($cacheToken);

        $mbRepository = new MemberDataSharingRepository($authClient, new VoidCache());
        $mbRepository->setBaseUrl(MemberDataSharingRepository::SANDBOX_BASE_URL);

        return $mbRepository;
    }

    /**
     * @param bool|true $cacheToken
     *
     * @return UserUpload
     */
    protected function getUserUpload($cacheToken = true)
    {
        $authClient = $this->getAuth($cacheToken);

        $userUpload = new UserUpload($authClient, new VoidCache());
        $userUpload->setBaseUrl(UserUpload::SANDBOX_BASE_URL);

        return $userUpload;
    }

    /**
     * @param bool|true $cacheToken
     *
     * @return Report
     */
    protected function getReport($cacheToken = true)
    {
        $authClient = $this->getAuth($cacheToken);

        $userUpload = new Report($authClient, new VoidCache());
        $userUpload->setBaseUrl(Report::SANDBOX_BASE_URL);
        $userUpload->setBaseUrlDownload(Report::SANDBOX_BASE_URL_DOWNLOAD);

        return $userUpload;
    }

    /**
     * @return AppnexusFacade
     */
    protected function getFacade()
    {
        $facade = new AppnexusFacade(getenv('USERNAME'), getenv('PASSWORD'), getenv('MEMBER_ID'));

        return $facade;
    }
}
