<?php

class Config {

	private $config = [
		'tumblr' => [
			'identifier' => 'sample.tumblr.com',
			'consumerKey' => 'YOUR CONSUMER KEY (your OAuth Consumer Key as your api_key)',
			'consumerSecret' => 'YOUR CONSUMER SECRET',
		],
	];


	public function get($name) {
		return (isset(self::$config[$name])) ? (object) self::$config[$name] : null;
	}

}