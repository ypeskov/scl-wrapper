<?php
/**
 * The wrapper for SounCloud SDK.
 *
 * @package SclWrapper
 * @author Yuriy Peskov <yuriy.peskov@gmail.com>
 */


namespace SclWrapper;

use Soundcloud\Service;
use Soundcloud\Exception\InvalidHttpResponseCodeException;

/**
 * This class is a wrapper over a SoundCloud PHP SDK for integration with it.
 *
 * Class SclWrapper
 * @package SclWrapper
 */
class SclWrapper {
    /**
     * Stores config for creating the instance with parameters of SoundCloud app.
     * @var Array
     */
    protected $config = array();

    /**
     * Store auth url for calback redirect.
     * @var String
     */
    protected $authUrl;

    /**
     * SoundCloud SDK Class.
     *
     * @var \Soundcloud\Service
     */
    protected $SclService;

    /**
     * @param $config Array
     * @throws \Exception
     */
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
            /**
             * @TODO make something :) May be process more specialized exceptions.
             */
            throw new \Exception($e);
        }
    }

    /**
     * Returns an array of accessToken according to the code of auth from Service
     *
     * @param string $code
     * @return array
     */
    public function getAccessToken($code) {
        $accessToken = null;

        if ( isset($this->accessToken) ) {
            $accessToken = $this->accessToken;
        } else {
            $accessToken = $this->SclService->accessToken($code);
        }

        return $accessToken;
    }

    /**
     * Sets the access token for using the previous identification.
     *
     * @param string $token
     * @return $this
     */
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

    /**
     * Return all uploaded tracks for users in $permalinks array.
     *
     * @param array $permalinks
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function searchTracksByPermalinks($permalinks, $limit=200, $offset=0) {
        $iterator = new \ArrayIterator($permalinks);
        $tracks = [];

        //walk through all permalinks for users and get tracks for each of them
        foreach($iterator as $permalink) {
            //first let's get user's info. If a permalink is wrong - skip it
            try {
                $user = $this->getUserByPermalink($permalink);
            } catch(InvalidHttpResponseCodeException $e) {
                continue;
            }

            //get all tracks by a user. in case of error skip the user
            try {
                $userTracks = $this->getUserTracks($user, $limit, $offset);
                $tracks = array_merge($tracks, $userTracks);
            } catch(InvalidHttpResponseCodeException $e) {
                continue;
            }
        }

        return $tracks;
    }

    /**
     * Return all uploaded tracks of a user.
     *
     * @param stdClass $user
     * @param int $limit
     * @param int $offset
     * @return array
     */
    protected function getUserTracks($user, $limit=50, $offset=0) {
        $queryPath = 'users/' . $user->id . '/tracks';
        $userTracks = json_decode($this
            ->SclService
            ->get($queryPath, ['limit' => $limit, 'offset' => $offset]));

        return $userTracks;
    }

    /**
     * Return info about a user according to his permalink.
     *
     * @param string $permalink
     * @return stdClass
     * @throws InvalidHttpResponseCodeException
     */
    protected function getUserByPermalink($permalink) {
        $url    = $this->config['sclUrl'] . $permalink;

        try {
            $user   = $this->resolveResource($url);

            if ( $user->kind !== 'user' ) {
                throw new InvalidHttpResponseCodeException('Resource is not a user', 'Not a user', 404);
            }
        } catch(InvalidHttpResponseCodeException $e) {
            throw $e;
        }

        return $user;
    }

    /**
     * Trying to get information about requested resource.
     *
     * @param string $url
     * @return stdClass | Array
     * @throws InvalidHttpResponseCodeException
     */
    public function resolveResource($url) {
        $resource = $this
            ->SclService
            ->get('resolve', ['url' => $url,]);

        return json_decode($resource);
    }

    /**
     * Returns authentication callback url on SC Service.
     *
     * @return string
     */
    public function getAuthUrl() {
        $this->authUrl = $this->SclService->getAuthorizeUrl();

        return $this->authUrl;
    }

    public function createPlayList($listName, $tracks) {
        $playlist = 'playlist[title]=' . $listName;

        $trackURI = '';
        foreach($tracks as $track) {
            try {
                $trackInfo = $this->getTrackInfoByPermalink($track);
                $trackURI .= '&playlist[tracks][][id]=' . $trackInfo->id;
            } catch(InvalidHttpResponseCodeException $e) {
                continue;
            }
        }

        $finalURI = $playlist . $trackURI;

        $response = json_decode($this->SclService->post('playlists', $finalURI));

        return $response;
    }

    /**
     * Returns info about a track according to its permalink.
     *
     * @param $trackPermalink
     * @return Array|stdClass
     * @throws InvalidHttpResponseCodeException
     */
    protected function getTrackInfoByPermalink($trackPermalink) {
        $track = $this->resolveResource($trackPermalink);

        return $track;
    }

    /**
     * Builds and returns HTML from the template.
     *
     * @param string $tplName
     * @param array $vars
     * @return string
     */
    public function getHtml($tplName, $vars=[]) {
        extract($vars);

        ob_start();
        include($tplName);
        $html = ob_get_clean();

        return $html;
    }
}