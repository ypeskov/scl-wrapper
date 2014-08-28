<?php
/**
 * The wrapper for SounCloud SDK.
 *
 * @package SclWrapper
 * @author Yuriy Peskov <yuriy.peskov@gmail.com>
 */


namespace SclWrapper;

use Soundcloud\Service;

/**
 * This class is a wrapper over a SoundCloud PHP SDK for integration with it.
 *
 * Class SclWrapper
 * @package SclWrapper
 */
class SclWrapper {
    /**
     * Stores config for creating the instance with parameters of SoundCloud app.
     *
     * @var array
     */
    protected $config = array();

    protected $authUrl;
    protected $SclService;
    protected $myInfo;
    protected $accessToken;

    const CONFIG_PATH = 'config/config.php';
    const SERVICE_URL = 'http://soundcloud.com/';

    public function __construct($config) {
        $this->config = $config;

        try {
            $this->SclService = new Service(
                $this->config['clientId'],
                $this->config['clientSecret'],
                $this->config['redirectURL'],
                $this->config['development']
            );
        } catch(Exception $e) {
            var_dump($e);
        }

        $this->getAuthUrl();
    }

    public function getAccessToken($code) {
        $accessToken = null;

        if ( isset($this->accessToken) ) {
            $accessToken = $this->accessToken;
        } else {
            $accessToken = $this->SclService->accessToken($code);
        }

        return $accessToken;
    }

    public function setAccessToken($token) {
        $this->SclService->setAccessToken($token);

        return $this;
    }

    public function getMyInfo() {
        if ( empty($this->myInfo) ) {
            $this->myInfo = json_decode($this->SclService->get('me'));
        }

        return $this->myInfo;
    }

    public function searchTracks($permalinks, $limit=200, $offset=0) {
        $iterator = new \ArrayIterator($permalinks);
        $tracks = [];
        foreach($iterator as $permalink) {
            //first let's get user's info
            $url    = self::SERVICE_URL . $permalink;
            $user   = $this->resolveResource($url);

            //get all tracks by a user
            $queryPath = 'users/' . $user->id . '/tracks';
            $userTracks = json_decode($this
                                        ->SclService
                                        ->get($queryPath, ['limit' => $limit, 'offset' => $offset]));

            $tracks = array_merge($tracks, $userTracks);
        }

        return $tracks;
    }

    public function resolveResource($url) {
        $resource = $this
            ->SclService
            ->get('resolve', ['url' => $url,]);

        return json_decode($resource);
    }

    public function getAuthUrl() {
        $this->authUrl = $this->SclService->getAuthorizeUrl();

        return $this->authUrl;
    }

    public function getHtml($tplName, $vars=[]) {
        extract($vars);

        ob_start();
        include($tplName);
        $html = ob_get_clean();

        return $html;
    }
}