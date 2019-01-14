<?php

/**
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Stefan Klemm <mail@stefan-klemm.de>
 * @copyright Stefan Klemm 2014
 */

namespace OCA\Bookmarks\Controller;

use \OCA\Bookmarks\Service\SettingsService;

use \OCA\Bookmarks\Controller\Lib\Bookmarks;
use \OCP\AppFramework\Controller;
use \OCP\AppFramework\Http\ContentSecurityPolicy;
use \OCP\AppFramework\Http\TemplateResponse;
use \OCP\IConfig;
use \OCP\IRequest;
use \OCP\IURLGenerator;

class WebViewController extends Controller {

	/** @var  string */
	private $userId;

	/** @var IURLGenerator  */
	private $urlgenerator;

	/** @var Bookmarks */
	private $bookmarks;

    private $config;

	/**
	 * WebViewController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param $userId
	 * @param IURLGenerator $urlgenerator
	 * @param Bookmarks $bookmarks
     * @param IConfig $config
	 */
	public function __construct($appName,
                                IRequest $request,
                                $userId,
                                IURLGenerator $urlgenerator,
                                Bookmarks $bookmarks,
                                IConfig $config) {
		parent::__construct($appName, $request);
		$this->userId = $userId;
		$this->urlgenerator = $urlgenerator;
		$this->bookmarks = $bookmarks;
        $this->config = new SettingsService($appName, $config, $userId);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {

		$settings = $this->config->getall();
		$bookmarkleturl = $this->urlgenerator->getAbsoluteURL('index.php/apps/bookmarks/bookmarklet');
		$params = array(
			'user' => $this->userId,
			'bookmarkleturl' => $bookmarkleturl,
			'settings' => $settings
		);

		$policy = new ContentSecurityPolicy();
		$policy->addAllowedFrameDomain("'self'");

		$response = new TemplateResponse('bookmarks', 'main', $params);
		$response->setContentSecurityPolicy($policy);
		return $response;
	}

	/**
	 * @param string $url
	 * @param string $title
	 * @return TemplateResponse
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function bookmarklet($url = "", $title = "") {
		$bookmarkExists = $this->bookmarks->bookmarkExists($url, $this->userId);
		$description = "";
        $tags = [];
		if ($bookmarkExists !== false){
			$bookmark = $this->bookmarks->findUniqueBookmark($bookmarkExists, $this->userId);
			$description = $bookmark['description'];
            $tags = $bookmark['tags'];
		}
		$params = array(
            'url'           => $url,
            'title'         => $title,
            'description'   => $description,
            'bookmarkExists'=> $bookmarkExists,
            'tags'          => $tags
        );
		return new TemplateResponse('bookmarks', 'addBookmarklet', $params);  // templates/main.php
	}

}
