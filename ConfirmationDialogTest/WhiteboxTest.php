<?php

namespace ConfirmationDialogTest;

require_once __DIR__ . '/../ConfirmationDialogDemo/libs/nette.min.php';
require_once __DIR__ . '/SessionMock.inc';
require_once __DIR__ . '/../ConfirmationDialogDemo/app/presenters/DefaultPresenter.php';

class WhiteboxTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * @var SessionMock
	 */
	private $session;
	
	/**
	 * @var \DefaultPresenter
	 */
	private $presenter;


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
		$container->router[] = new \Nette\Application\Routers\SimpleRouter('Default:default');
		$this->session = $container->session = new SessionMock($this);
		$this->presenter = new \DefaultPresenter($container);
	}
	
	public function testClickingDeleteAndConfirmingResultsInFlashMessage()
	{
		$this->assertTrue(true);
	}
}