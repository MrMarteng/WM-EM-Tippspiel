<?php

session_start();

//print_r($_SESSION);

class userSession
{
	var $strSessionKey = "";
	var $intUserID = 0;
	var $blnValid = FALSE;
	
	function userSession()
	{
		$this->strSessionKey = md5("sessionkey");
		if(session_is_registered("sessionkey"))
		{
			if($_SESSION['sessionkey'] == $this->strSessionKey)
			{
				/* session valid */
				$this->blnValid = TRUE;
							
				/* recover session */
				if(session_is_registered("userid"))$this->intUserID = $_SESSION['userid'];
				
				/* save query-string to return after submitting the tip */
				if(!count($_POST))$this->setVar('refurl', $_SERVER['HTTP_REFERER']);
			}
		}
		
		
	}
	
	function authUser($intUserID)
	{
		session_register("userid");
		$_SESSION['userid'] = $intUserID;
		$this->intUserID = $intUserID;
		$this->validateSession();
	}
	
	function getUserID()
	{
		return $this->intUserID;
	}
	
	function validateSession()
	{
		session_register("sessionkey");
		$_SESSION['sessionkey'] = $this->strSessionKey;
		$this->blnValid = TRUE;
	}
	
	function sessionValid()
	{
		return $this->blnValid;
	}
	
	function setVar($strVarName, $mixValue)
	{
		if($this->blnValid)
		{
			if( !session_is_registered($strVarName) )session_register($strVarName);	
			$_SESSION[$strVarName] = $mixValue;
		}
	}
	
	function getVar($strVarName)
	{
		if($this->blnValid)
		{
			if(session_is_registered($strVarName)) return $_SESSION[$strVarName];
			else return -1;
		}
	}
	
	function logout()
	{
		session_destroy();
		$this->intUserID = 0;
		$this->blnValid = FALSE;
	}
}
	

	
	
	
?>
