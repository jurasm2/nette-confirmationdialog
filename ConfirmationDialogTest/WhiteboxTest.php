<?php

namespace ConfirmationDialogTest;

require_once __DIR__ . '/../ConfirmationDialogDemo/libs/nette.min.php';
require_once __DIR__ . '/SessionMock.inc';
require_once __DIR__ . '/../ConfirmationDialogDemo/app/presenters/DefaultPresenter.php';

class WhiteboxTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var \SystemContainer
	 */
	private $container;
	
	/**
	 * @var SessionMock
	 */
	private $session;

	public function setUp()
	{
		// Bootstrap
		if (!class_exists('SystemContainer')) {
			$configurator = new \Nette\Config\Configurator;
			$configurator->enableDebugger(__DIR__ . '/../ConfirmationDialogDemo/log');
			if (!file_exists(__DIR__ . '/../ConfirmationDialogDemo/temp/tests')) {
				mkdir(__DIR__ . '/../ConfirmationDialogDemo/temp/tests');
			}
			$configurator->setTempDirectory(__DIR__ . '/../ConfirmationDialogDemo/temp/tests');
			$container = $configurator->createContainer();
		}
		
		$container = new \SystemContainer;
		\Nette\Environment::setContext($container);
		$container->router[] = new \Nette\Application\Routers\SimpleRouter('Default:default');
		$this->container = $container;
		$this->session = $container->session = new SessionMock($this);
	}
	
	private function clickEnableAndGetTheToken()
	{
		// Click the enable link
		$request = new \Nette\Application\Request(
				'Default',
				'get',
				array('action' => 'default', 'nonajaxForm-id' => '10', 'do' => 'nonajaxForm-confirmEnable')
				);
		$presenter = new \DefaultPresenter($this->container);
		$presenter->autoCanonicalize = false;
		$response = $presenter->run($request);

		// Get the token
		ob_start();
		$presenter->getComponent('nonajaxForm-form')->render();
		$htmlSource = ob_get_clean();
		preg_match('~value="([0-9a-z]{15,})"~', $htmlSource, $matches);
		$token = $matches[1];
		
		return $token;
	}
	
	public function testClickingEnableAndConfirmingLeadsToSuccess()
	{
		$token = $this->clickEnableAndGetTheToken();

		// Click the Yes button
		$request = new \Nette\Application\Request(
				'Default',
				'post',
				array('action' => 'default', 'do' => 'nonajaxForm-form-submit'),
				array('yes' => 'Yes', 'token' => $token)
				);
		$presenter = new \DefaultPresenter($this->container);
		$presenter->autoCanonicalize = false;
		$response = $presenter->run($request);
		$flashSession = $presenter->getFlashSession();
		$flash = $flashSession->flash[0]->message;

		$this->assertInstanceOf('Nette\\Application\\Responses\\RedirectResponse', $response);
		$this->assertEquals('User enabled.', $flash);
	}
	
	public function testClickingEnableAndAbortingLeadsToAbort()
	{
		$token = $this->clickEnableAndGetTheToken();

		// Click the No button
		$request = new \Nette\Application\Request(
				'Default',
				'post',
				array('action' => 'default', 'do' => 'nonajaxForm-form-submit'),
				array('no' => 'No', 'token' => $token)
				);
		$presenter = new \DefaultPresenter($this->container);
		$presenter->autoCanonicalize = false;
		$response = $presenter->run($request);
		$flashSession = $presenter->getFlashSession();
		
		$this->assertInstanceOf('Nette\\Application\\Responses\\RedirectResponse', $response);
		$this->assertEquals($this->container->httpRequest->url->baseUrl, $response->url);
		$this->assertEmpty($flashSession->flash);
	}
}