<?php

namespace App\Presenters\StatsModule;

use App\Model\Panel\MojangRepository;
use App\Model\Responses\PrettyJsonResponse;
use App\Model\Stats\CachedAPIRepository;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;

/**
 * Class APIPresenter
 * @package App\Presenters\StatsModule
 */
class APIPresenter extends Presenter {

    private CachedAPIRepository $cachedAPIRepository;
    private MojangRepository $mojangRepository;

    /**
     * APIPresenter constructor.
     * @param CachedAPIRepository $cachedAPIRepository
     * @param MojangRepository $mojangRepository
     */
    public function __construct(CachedAPIRepository $cachedAPIRepository, MojangRepository $mojangRepository)
    {
        parent::__construct();

        $this->cachedAPIRepository = $cachedAPIRepository;
        $this->mojangRepository = $mojangRepository;
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
                    'headImageURL' => "https://minotar.net/avatar/{$name}.png",
                    'auth' => [
                        'userID' => $user->id,
                        'regtime' => $user->regdate,
                        'lastlogin' => strtotime($fastLogin->LastLogin->date)*1000,
                    ], 'friends' => [
                        'count' => $this->cachedAPIRepository->getCountFriends($name),
                        'list' => $this->cachedAPIRepository->getFriendsList($name),
                    ], 'tokens' =>  $this->cachedAPIRepository->getTokenManager($name)->tokens,
                ],
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