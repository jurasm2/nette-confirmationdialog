<?php

/**
 * Description of ConfirmationDialogTest
 *
 * @author Václav Šír
 */
class ConfirmationDialogDemoTest extends PHPUnit_Extensions_SeleniumTestCase
{

	protected function setUp()
	{
		$this->setBrowser("*firefox");
		$this->setBrowserUrl("http://localhost/");
	}

	public function testEnableUser()
	{
		$this->open("/confirm-dialog/ConfirmationDialogDemo/www/");
		$this->click("link=Enable user");
		sleep(1);
		$this->click("id=frmform-yes");
		sleep(1);
		$this->verifyTextPresent("User enabled.");
		$this->click("link=Enable user");
		sleep(1);
		$this->click("id=frmform-no");
		sleep(1);
		try {
			$this->assertFalse($this->isTextPresent("User enabled."));
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			array_push($this->verificationErrors, $e->toString());
		}
	}

	public function testDeleteUser()
	{
		$this->open("/confirm-dialog/ConfirmationDialogDemo/www/");
		$this->click("link=Delete user");
		sleep(1);
		$this->verifyTextPresent("Do you really want to delete user '10'?");
		$this->click("id=frmform-no");
		sleep(1);
		try {
			$this->assertFalse($this->isTextPresent("User completely deleted"));
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			array_push($this->verificationErrors, $e->toString());
		}
		$this->click("link=Delete user");
		sleep(1);
		$this->click("id=frmform-yes");
		sleep(1);
		$this->verifyTextPresent("Do you really want to");
		$this->click("id=frmform-no");
		sleep(1);
		try {
			$this->assertFalse($this->isTextPresent("User completely deleted"));
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			array_push($this->verificationErrors, $e->toString());
		}
		$this->click("link=Delete user");
		sleep(1);
		$this->verifyTextPresent("Do you really want to delete user '10'?");
		$this->click("id=frmform-yes");
		sleep(1);
		$this->verifyTextPresent("Do you really want to delete user '10' and all articles connected with him?");
		$this->click("id=frmform-yes");
		sleep(1);
		$this->verifyTextPresent("User completely deleted");
	}

	public function testInfiniteQuestion()
	{
		$this->open("/confirm-dialog/ConfirmationDialogDemo/www/");
		$this->click("link=Infinite question");
		sleep(1);
		$this->verifyTextPresent("You are at step '1'");
		$this->click("id=frmform-yes");
		sleep(1);
		$this->verifyTextPresent("You are at step '2'");
		$this->click("id=frmform-yes");
		sleep(1);
		$this->verifyTextPresent("You are at step '3'");
		$this->click("id=frmform-yes");
		sleep(1);
		$this->verifyTextPresent("You are at step '4'");
		$this->click("id=frmform-no");
		sleep(1);
		try {
			$this->assertFalse($this->isTextPresent("You are at step"));
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			array_push($this->verificationErrors, $e->toString());
		}
	}

}
