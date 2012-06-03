<?php

namespace ConfirmationDialogTest;

/**
 * @author Václav Šír
 */
class SessionMock extends \Nette\Http\Session
{

	/**
	 * @var array
	 */
	private $sections = array();

	/**
	 * @var \PHPUnit_Framework_TestCase 
	 */
	private $testCase;

	public function __construct(\PHPUnit_Framework_TestCase $testCase)
	{
		$this->testCase = $testCase;
		$request = $testCase->getMock('Nette\\Http\\IRequest');
		$response = $testCase->getMock('Nette\\Http\\IResponse');
		parent::__construct($request, $response);
	}

	public function getSection($section, $class = 'Nette\Http\SessionSection')
	{
		if (!isset($this->sections[$section])) {
			$this->sections[$section] = new $class($this, $section);
		}
		return $this->sections[$section];
	}

	public function start()
	{
	}

}
