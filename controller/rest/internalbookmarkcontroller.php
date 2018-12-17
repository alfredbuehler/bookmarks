<?php

/**
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Stefan Klemm <mail@stefan-klemm.de>
 * @copyright Stefan Klemm 2014
 */

namespace OCA\Bookmarks\Controller\Rest;

use \OC\User\Manager;
use \OCA\Bookmarks\Controller\Lib\Bookmarks;
use \OCA\Bookmarks\Service\SettingsService;
use \OCP\AppFramework\ApiController;
use \OCP\AppFramework\Http;
use \OCP\AppFramework\Http\JSONResponse;
use \OCP\IConfig;
use \OCP\IDBConnection;
use \OCP\IL10N;
use \OCP\IRequest;

class InternalBookmarkController extends ApiController {

	private $publicController;
    private $config;
    private $appname;
    private $userid;

	public function __construct($appName,
                                IRequest $request,
                                $userId,
                                IDBConnection $db,
                                IL10N $l10n,
                                Bookmarks $bookmarks,
                                Manager $userManager,
                                IConfig $config) {
        parent::__construct($appName, $request);
        $this->appname = $appName;
        $this->userid = $userId;
        $this->publicController = new BookmarkController($appName, $request, $userId, $db, $l10n, $bookmarks, $userManager);
		$this->config = new SettingsService($appName, $config, $userId);
	}

	/**
	 * @param string $type
	 * @param string $tag
	 * @param int $page
	 * @param string $sort
	 * @param string user
	 * @param array tags
	 * @param string conjunction
	 * @param string sortby
	 * @param array search
	 * @return JSONResponse
	 *
	 * @NoAdminRequired
	 */
	public function getBookmarks(
		$type = "bookmark",
		$tag = '', // legacy
		$page = 0,
		$sort = "bookmarks_sorting_recent", // legacy
		$user = null,
		$tags = array(),
		$conjunction = "or",
		$sortby = "",
		$search = array()
	) {
		$sortby = $this->config->get('sort-by');
		return $this->publicController->getBookmarks($type, $tag, $page, $sort, $user, $tags, $conjunction, $sortby, $search);
	}

	/**
	 * @param string $url
	 * @param array $item
	 * @param string $title
	 * @param bool $is_public
	 * @param string $description
	 * @return JSONResponse
	 *
	 * @NoAdminRequired
	 */
	public function newBookmark($url = "", $item = array(), $title = "", $is_public = false, $description = "") {
		return $this->publicController->newBookmark($url, $item, $title, $is_public, $description);
	}

	/**
	 * @param int $id
	 * @param string $url
	 * @param array $item
	 * @param string $title
	 * @param bool $is_public Description
	 * @param null $record_id
	 * @param string $description
	 * @return Http\TemplateResponse
	 *
	 * @NoAdminRequired
	 */
	public function legacyEditBookmark($id = null, $url = "", $item = array(), $title = "", $is_public = false, $record_id = null, $description = "") {
		return $this->publicController->legacyEditBookmark($id, $url, $item, $title, $is_public, $record_id, $description);
	}

	/**
	 * @param int $id
	 * @param string $url
	 * @param array $item
	 * @param string $title
	 * @param bool $is_public Description
	 * @param null $record_id
	 * @param string $description
	 * @return JSONResponse
	 *
	 * @NoAdminRequired
	 */
	public function editBookmark($id = null, $url = "", $item = array(), $title = "", $is_public = false, $record_id = null, $description = "") {
		return $this->publicController->editBookmark($id, $url, $item, $title, $is_public, $record_id, $description);
	}

	/**
	 * @param int $id
	 * @return JSONResponse
	 *
	 * @NoAdminRequired
	 */
	public function legacyDeleteBookmark($id = -1) {
		return $this->legacyDeleteBookmark($id);
	}

	/**
	 * @param int $id
	 * @return \OCP\AppFramework\Http\JSONResponse
	 *
	 * @NoAdminRequired
	 */
	public function deleteBookmark($id = -1) {
		return $this->publicController->deleteBookmark($id);
	}

	/**
	 *
	 * @param string $url
	 * @return \OCP\AppFramework\Http\JSONResponse
	 * @NoAdminRequired
	 */
	public function clickBookmark($url = "") {
		return $this->publicController->clickBookmark($url);
	}

	/**
	 *
	 * @return \OCP\AppFramework\Http\JSONResponse
	 * @NoAdminRequired
	 */
	public function importBookmark() {
		return $this->publicController->importBookmark();
	}

	/**
	 *
	 * @return \OCP\AppFramework\Http\Response
	 * @NoAdminRequired
	 */
	public function exportBookmark() {
		return $this->publicController->exportBookmark();
	}

    /**
	 * @NoAdminRequired
	 */
    public function config($key, $value) {
		$this->config->set($key, $value);
	}

}
