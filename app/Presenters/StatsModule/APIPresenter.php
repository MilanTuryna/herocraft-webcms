<?php

namespace App\Presenters\StatsModule;

use App\Model\Panel\MojangRepository;
use App\Model\Responses\PrettyJsonResponse;
use App\Model\Stats\CachedAPIRepository;
use App\Model\Stats\CachedSurvivalRepository;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;

/**
 * Class APIPresenter
 * @package App\Presenters\StatsModule
 */
class APIPresenter extends Presenter {

    private CachedAPIRepository $cachedAPIRepository;
    private MojangRepository $mojangRepository;
    private CachedSurvivalRepository $cachedSurvivalRepository;

    /**
     * APIPresenter constructor.
     * @param CachedAPIRepository $cachedAPIRepository
     * @param MojangRepository $mojangRepository
     * @param CachedSurvivalRepository $cachedSurvivalRepository
     */
    public function __construct(CachedAPIRepository $cachedAPIRepository, MojangRepository $mojangRepository, CachedSurvivalRepository $cachedSurvivalRepository)
    {
        parent::__construct();

        $this->cachedAPIRepository = $cachedAPIRepository;
        $this->mojangRepository = $mojangRepository;
        $this->cachedSurvivalRepository = $cachedSurvivalRepository;
    }

    /**
     * @param $json
     * @throws AbortException
     */
    public function sendResponseJson($json) {
        $this->sendResponse(new PrettyJsonResponse($json));
    }

    /**
     * @param $name
     * @throws AbortException
     */
    public function actionView($name) {
        $user = $this->cachedAPIRepository->getUser($name);
        $response = [];
        $http = [
            'code' => 200,
            'requestTime' => time()*1000,
            'url' => $this->getHttpRequest()->getUrl(),
            'method' => $this->getHttpRequest()->getMethod(),
            'ip' => $this->getHttpRequest()->getRemoteAddress(),
        ];

        if($user) {
            $fastLogin = $this->cachedAPIRepository->getFastLogin($name);
            $uuid = $this->mojangRepository->getUUID($name);
            $response = [
                'updateTime' => CachedAPIRepository::EXPIRE_TIME,
                'http' => $http,
                'player' => [
                    'exists' => true,
                    'isBanned' => $this->cachedAPIRepository->isBanned($name),
                    'nickname' => $name,
                    'playertime' => null,
                    'uuid' => $uuid,
                    'originalUuid' => $this->mojangRepository->getMojangUUID($name),
                    'headImageURL' => "https://minotar.net/avatar/{$name}.png",
                    'auth' => [
                        'userID' => $user->id,
                        'regtime' => $user->regdate,
                        'lastlogin' => strtotime($fastLogin->LastLogin->date)*1000,
                    ], 'friends' => [
                        'count' => $this->cachedAPIRepository->getCountFriends($name),
                        'list' => $this->cachedAPIRepository->getFriendsList($name),
                    ], 'tokens' =>  $this->cachedAPIRepository->getTokenManager($name)->tokens,
                ], 'servers' => [
                    'survival' => [
                        'levels' => $this->cachedSurvivalRepository->getLevels($uuid),
                        'lottery' => $this->cachedSurvivalRepository->getLottery($uuid),
                        'economy' => $this->cachedSurvivalRepository->getEconomy($name)
                    ],
                ]
            ];
        } else {
            $response = [
              'http' => $http,
              'player' => [
                  'exists' => false
              ]
            ];
        }

        $this->sendResponseJson($response);
    }
}