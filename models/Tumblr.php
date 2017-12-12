<?php

use \GuzzleHttp\Client;

class Tumblr {

	private $client = null;

	private $tumblr = null;

	private $identifier = null;


	function __construct() {
		$this->client = new Client();
		$this->tumblr = Config::get('tumblr');
		$this->identifier = $this->tumblr->identifier;
	}


	/**
	 * https://www.tumblr.com/docs/en/api/v2#posts
	 *
	 * @return array
	 */
	public function get($option = []) {

		$identifier = (isset($option['identifier'])) ? $option['identifier'] : $this->identifier;
		$type       = (isset($option['type']))       ? $option['type']       : '';
		$tag        = (isset($option['tag']))        ? $option['tag']        : '';
		$id         = (isset($option['id']))         ? (int) $option['id']   : 0;

		$id = (empty($id)) ? null : $id;

		$url = "http://api.tumblr.com/v2/blog/{$identifier}/posts/{$type}";

		$response = $this->client->request('GET', $url, ['query' => [
			'tag' => $tag,
			'id' => $id,
			'api_key' => $this->tumblr->consumerKey,
		]]);
		// $response = $this->client->request('POST', $url, ['form_params' => [
		// 	'tag'     => $tag,
		// 	'api_key' => $this->tumblr->consumerKey,
		// ]]);
		return (!empty($response)) ? self::builds($response) : [];
	}


	/**
	 * 取得したデータを使いやすいように統一、整理する
	 *
	 * @param Client->request() $response
	 * @return array
	 */
	private static function builds($response) {
		$obj = $response->getBody();
		$obj = json_decode($obj);

		if ($obj->meta->status !== 200) {
			return [];
		}

		$res = [];

		foreach ($obj->response->posts as $i => $obj) {
			if (!$list = self::buildByType($obj)) {
				continue;
			}
			$res[] = $list;
		}

		return $res;
	}


	/**
	 * type によって振り分ける
	 *
	 * @param object $obj
	 * @return array(key => value)|null
	 */
	private static function buildByType($obj) {
		switch ($obj->type) {
			case 'text':
				return self::buildText($obj);
			case 'photo':
				return self::buildPhoto($obj);
			default:
				return null;
		}
	}


	/**
	 * POST type is text
	 *
	 * @param object $obj
	 * @return array(key => value)
	 */
	private static function buildText($obj) {
		return [
			'id'        => $obj->id,
			'type'      => $obj->type,
			'createdAt' => $obj->timestamp,
			'title'     => $obj->title,
			'body'      => $obj->body,
			'images'    => [],
		];
	}


	/**
	 * POST type is photo
	 *
	 * @param object $obj
	 * @return array(key => value)
	 */
	private static function buildPhoto($obj) {

		$images = [];

		foreach ($obj->photos as $photo) {
			$images[] = $photo->alt_sizes;
		}

		return [
			'id'        => $obj->id,
			'type'      => $obj->type,
			'createdAt' => $obj->timestamp,
			'title'     => $obj->summary,
			'body'      => $obj->caption,
			'images'    => $images,
		];
	}

}