<?php

namespace OCA\Bookmarks\Service;

use \OCP\IConfig;

class SettingsService {

	private $appname;
	private $config;
	private $userId;

	public function __construct($AppName, IConfig $config, $userId) {
		$this->appname = $AppName;
		$this->config = $config;
		$this->userId = $userId;
	}

	public function set($key, $value) {
		$this->config->setUserValue($this->userId, $this->appname, $key, $value);
	}

	public function get($key) {
		return $this->config->getUserValue($this->userId, $this->appname, $key);
	}

	public function getSortBy() {
		return $this->config->getUserValue($this->userId, $this->appname, 'sort-by', 'lastmodified');
	}

	public function getConfirmDelete() {
		return $this->config->getUserValue($this->userId, $this->appname, 'confirm-delete', '1');
	}

	public function getall() {
		return array(
			'sort-by'        => $this->getSortBy(),
			'confirm-delete' => $this->getConfirmDelete()
		);
	}

}
