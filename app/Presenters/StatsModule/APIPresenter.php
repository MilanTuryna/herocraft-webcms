<?php

namespace App\Presenters\StatsModule;

use App\Model\DI\API;
use App\Model\DI\GoogleAnalytics;
use App\Model\Panel\MojangRepository;
use App\Model\Responses\PrettyJsonResponse;
use App\Model\Stats\CachedAPIRepository;
use App\Presenters\BasePresenter;
use Nette\Application\AbortException;
use Throwable;

/**
 * Class APIPresenter
 * @package App\Presenters\StatsModule
 */
class APIPresenter extends BasePresenter {

    private CachedAPIRepository $cachedAPIRepository;
    private MojangRepository $mojangRepository;
    private API $api;

    /**
     * APIPresenter constructor.
     * @param CachedAPIRepository $cachedAPIRepository
     * @param MojangRepository $mojangRepository
     * @param API $api
     */
    public function __construct(CachedAPIRepository $cachedAPIRepository, MojangRepository $mojangRepository, API $api)
    {
        parent::__construct(GoogleAnalytics::disabled(), false);

        $this->cachedAPIRepository = $cachedAPIRepository;
        $this->mojangRepository = $mojangRepository;
        $this->api = $api;
    }

    /**
     * @param $json
     * @throws AbortException
     */
    public function sendResponseJson($json) {
        $this->sendResponse(new PrettyJsonResponse($json));
    }

    /**
     * @throws AbortException
     * @throws Throwable
     */
    public function actionServerView() {
        $serverStarted = $this->translator->translate('front.customStatsRow.value') ?: 0;
        $response = [];
        $http = [
            'code' => 200,
            'requestTime' => time()*1000,
            'url' => $this->getHttpRequest()->getUrl(),
            'method' => $this->getHttpRequest()->getMethod(),
            'ip' => $this->getHttpRequest()->getRemoteAddress(),
        ];
        $response = [
            'updateTime' => $this->api->getExpireTime(),
            'http' => $http,
            'stats' => [
                "registerCount" => $this->cachedAPIRepository->getRegisterCount(),
                "timesPlayed" => $this->cachedAPIRepository->getTimesPlayed()/60, // minutes to hours -> seconds/60 = minutes
                "serverStarted" => [
                    "timestamp" => $serverStarted !== 0 ? strtotime($serverStarted) : 0,
                    "string" => $serverStarted
                    ]
             ],
            'czechCraft' => [
                'server' => $this->cachedAPIRepository->getCzechCraftServer(),
                'topVoters' => $this->cachedAPIRepository->getTopVoters()
            ]
        ];

        $this->sendResponseJson($response);
    }

    /**
     * @param $name
     * @throws AbortException
     * @throws Throwable
     */
    public function actionView($name) {
        $user = $this->cachedAPIRepository->getUser($name);
        $uuid = $this->mojangRepository->getUUID($name);
        $response = [];
        $http = [
            'code' => 200,
            'requestTime' => time()*1000,
            'url' => $this->getHttpRequest()->getUrl(),
            'method' => $this->getHttpRequest()->getMethod(),
            'ip' => $this->getHttpRequest()->getRemoteAddress(),
        ];

        if($user && $uuid) {
            $response = [
                'updateTime' => $this->api->getExpireTime(),
                'http' => $http,
                'player' => [
                    'exists' => true,
                    'isBanned' => $this->cachedAPIRepository->isBanned($name),
                    'nickname' => $name,
                    'playertime' => null,
                    'uuid' => $uuid,
                    'originalUuid' => $this->mojangRepository->getMojangUUID($name),
                    'czechCraft' => $this->cachedAPIRepository->getCzechCraftPlayer($name),
                    'headImageURL' => "https://minotar.net/avatar/{$name}.png",
                    'perms' => [
                        'groups' => $this->cachedAPIRepository->getPermGroups($uuid)
                    ], 'auth' => [
                        'userID' => $user->id,
                        'regtime' => $user->regdate,
                    ], 'servers' => [
                        'games' => [
                            'events' => $this->cachedAPIRepository->getPlayerEventsRecords($name),
                            'hideAndSeek' => $this->cachedAPIRepository->getHideAndSeekRow($name),
                            'spleef' => $this->cachedAPIRepository->getSpleefStatsByUUID($uuid)
                        ], 'senior' => [
                            'economy' => [
                                "balance" => $this->cachedAPIRepository->getSeniorEconomy($name)
                            ],
                        ], 'classic' => [
                            'economy' => [
                                "balance" => $this->cachedAPIRepository->getClassicEconomy($name)
                            ]
                        ]
                    ]
                ]
            ];
        } else {
            $response = [
                'http' => $http,
                'reason' => 'UUID or user not found',
                'player' => [
                    'exists' => false
                ]
            ];
        }


        $this->sendResponseJson($response);
    }
}