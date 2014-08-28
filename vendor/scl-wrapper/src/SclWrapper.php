<?php
/**
 *
 * @package SclWrapper
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
    protected $config = array();
    protected $authUrl;
    protected $SclService;
    protected $myInfo;
    protected $accessToken;

    const CONFIG_PATH = 'config/config.php';

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

    public function searchTracks($userIDs, $limit=50, $offset=0) {

        $iterator = new \ArrayIterator($userIDs);
        $tracks = [];
        foreach($iterator as $userId) {
            $userTracks = json_decode($this
                                        ->SclService
                                        ->get('tracks', array(
                                            'user_id'   => $userId,
                                            'limit'     => $limit,
                                            'offset'    => $offset,
                            )));
            $tracks = array_merge($tracks, $userTracks);
        }

        return $tracks;
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