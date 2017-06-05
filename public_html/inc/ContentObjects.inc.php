<?php

function usercustomRow($intUserID, $intCurUserID, &$aryRow)
{
	if($intUserID == $intCurUserID)
	{
		foreach($aryRow AS $strCurKey => $strCurRow)
		{
			$aryRow[$strCurKey] = "<span class='me'>".$strCurRow."</span>"; 
		}		
	}	
}

function addEmail($strName, $strDomain)
{
	$strHTMLLink = "<script TYPE='text/javascript'>\n";
	$strHTMLLink .= "<!--\n";
	$strHTMLLink .= "function shred(name, domain)\n";
	$strHTMLLink .= "{\n";
	$strHTMLLink .= "var se = name;\n";
	$strHTMLLink .= "var cr = \"@\";\n";
	$strHTMLLink .= "var et = domain;\n";
	$strHTMLLink .= "return (se + cr + et);\n";
	$strHTMLLink .= "}\n";
	$strHTMLLink .= "document.write('<a class=\"mail\" href=\"mailto:' + shred(\"".$strName."\",\"".$strDomain."\") + '\">' + shred(\"".$strName."\",\"".$strDomain."\") + '</a>')\n";
	$strHTMLLink .= "//-->\n";
	$strHTMLLink .= "</script>\n";
	return $strHTMLLink;	
}


function wtf($sText, $iCharsCount, $sCutWith = "...") 
{
       $sTextLength = strlen($sText);

       if($sTextLength <= $iCharsCount) {
           return $sText;
       }

       if($sTextLength+strlen($sCutWith) > $iCharsCount) {
           return substr($sText, 0, $iCharsCount-strlen($sCutWith)).$sCutWith;
       }

       $iLeftOverChars = strlen($sCutWith) - ($sTextLength - $iCharsCount);

       return substr($sText, 0, $sTextLength-$iLeftOverChars).$sCutWith;
 }



if(!function_exists('http_build_query')) 
{
	function http_build_query( $formdata, $numeric_prefix = null, $key = null ) 
	{
    	$res = array();
       	foreach ((array)$formdata as $k=>$v) 
		{
        	$tmp_key = urlencode(is_int($k) ? $numeric_prefix.$k : $k);
           	if ($key) $tmp_key = $key.'['.$tmp_key.']';
           	if ( is_array($v) || is_object($v) ) 
			{
            	$res[] = http_build_query($v, null /* or $numeric_prefix if you want to add numeric_prefix to all indexes in array*/, $tmp_key);
			} 
			else 
			{
            	$res[] = $tmp_key."=".urlencode($v);
			}
           	/*
           	If you want, you can write this as one string:
           	$res[] = ( ( is_array($v) || is_object($v) ) ? http_build_query($v, null, $tmp_key) : $tmp_key."=".urlencode($v) );
			*/
		}
       $separator = ini_get('arg_separator.output');
       return implode($separator, $res);
	}
}


class CO
{
	var $mixWidth = "90%";
	var $strAlign = "center";
	var $strStyle = "margin-top:5px;";
	var $strContentAlign = "center";
	
	function addStyle($strStyle)
	{
		$this->strStyle .= $strStyle.";";
	}
}


class boxed_CO extends CO
{

	var $strHeader = "";
	var $strContent= "";
	var $intTextWidth = 60;	
  	
	function addContent( $strNewContent)
	{
		$this->strContent .= $strNewContent;
	}
	
	function setHeader($strNewHeader)
	{
		$this->strHeader = $strNewHeader;
	}
	
	function generateCO()
	{
		$strFirstRowStyleLeft = "border-color:$707070; border-style:solid; border-width:0px; border-top-width:1px; padding:0px; border-left-width:1px";
		$strFirstRowStyleRight = "border-color:$707070; border-style:solid; border-width:0px; border-top-width:1px; padding:0px; border-right-width:1px";
		$strSecondRowStyle = "border-color:$707070; border-width:1px; border-style:solid; border-top-style:none; padding:5px;";
		$strHTML = "";
		
		$strHTML .= "\n<table width='".$this->mixWidth."' align='".$this->strAlign."' cellspacing=0 cellpadding=0 border=0 ".$this->strStyle.">";
		$strHTML .= "\n<tr>";
		$strHTML .= "\n<td width=10 style='padding:0px;'><img src='pics/clear.gif' width=10 height=10 style='margin:0px;'></td>";
		$strHTML .= "\n<td width=".$this->intTextWidth." rowspan=2 class='box_header'>".$this->strHeader."</td>";
		$strHTML .= "\n<td width=".($this->intWidth - $this->intTextWidth)." style='margin:0px;'><img src='pics/clear.gif' height=10 style='margin:0px;'></td>";
		$strHTML .= "\n</tr>";	
		$strHTML .= "\n<tr>";
		$strHTML .= "\n<td height=10 style='".$strFirstRowStyleLeft."'><img src='pics/clear.gif' height=10 style='margin:0px;'></td>";		
		$strHTML .= "\n<td style='".$strFirstRowStyleRight."'><img src='pics/clear.gif' height=10 style='margin:0px;'></td>";
		$strHTML .="\n</tr>";
		$strHTML .= "\n<tr>";
		$strHTML .= "\n<td style='".$strSecondRowStyle."'colspan=3>".$this->strContent."</td></tr>";
		$strHTML .= "\n</table>\n\n";
		return $strHTML;
	}
}


class std_CO extends CO
{
	var $strHeader = "";
	var $strContent = "";
			
	function addContent( $strNewContent)
	{
		$this->strContent .= $strNewContent;
	}
	
	function setHeader($strNewHeader)
	{
		$this->strHeader = $strNewHeader;
	}
	
	function generateCO()
	{
	  $strHTML = "";
	  
	  $strHTML .= "\n<table width='".$this->mixWidth."' align='".$this->strAlign."' ".$this->strStyle." cellspacing=2 cellpadding=2 border=0>";
		$strHTML .= "\n<tr><td class='header'>".$this->strHeader."</td></tr>";
		$strHTML .= "\n<tr><td style='text-align:".$this->strContentAlign."'>".$this->strContent."</td></tr>";
		$strHTML .= "\n</table>\n\n";
		return $strHTML;		
	}

}

class formular extends CO
{
	var $strAction = "?";
	var $strMethod = "POST";
	var $strForm = "";
	var $strFormName = "";	
	
	function formular($strName, $strAction)
	{
		$this->strFormName = $strName;
		$this->setAction($strAction);				
	}
	
	function setAction($strAction)
	{
		$this->strAction = $strAction;
	}
	
	function insertKat($strKat)
	{
		$this->strForm .= "\n<tr><td colspan=3 style='padding-top:5px;'><b>".$strKat."<b></td></tr>";
	}
	
	function insertInput($strDescription, $strAttr)
	{
		$this->strForm .= "\n<tr><td>&nbsp;</td><td>".$strDescription.":</td><td><input ".$strAttr."></td><tr>";
	}
	
	function insertHidden($strName, $strValue)
	{
		$this->strForm .= "\n<input type='hidden' name='".$strName."' value='".$strValue."'></td><tr>";
	}
	
	function insertCheckbox($strDescription, $strName)
	{
		$this->strForm .= "\n<tr><td>&nbsp;</td><td colspan=2>".$strDescription."</td></tr>";
	}
	
	function insertBlank()
	{
		$this->strForm .= "\n<tr><td colspan=3>&nbsp</td></tr>";
	}
	
	function getMethod()
	{
		if($this->strMethod == "POST") return $_POST;
		elseif($this->strMethod == "GET") return $_GET;
	}
	
	function insertSingleSelection($strName, &$arySelectItems, $strSelected, $blnSubitOnChange)
	{
		/* something selected */
		foreach($arySelectItems AS $aryCurrentItem)
		{
			if(array_key_exists($strName, $this->getMethod()))         
			{	
				$aryFormVars = $this->getMethod();
				$strSelected = $aryFormVars[$strName];				
			}			
		}		
		
		$this->strForm .= "\n<tr><td colspan=3 style='text-align:center;'>\n<select name=".$strName;
		if($blnSubitOnChange) $this->strForm .= " onchange=\"document.".$this->strFormName.".submit()\" ";
		$this->strForm .= ">";
		foreach($arySelectItems AS $strSelectionKey => $strSelectionName)
		{
			$this->strForm .= "<option value=".$strSelectionKey;
			
			if($strSelected == $strSelectionKey) $this->strForm .= " selected";
			$this->strForm .= " >".$strSelectionName."</option>";
		}
		$this->strForm .= "</select>\n</td></tr>";
	}
	
	function insertSubmit($strValue, $strName)
	{
		$this->strForm .= "\n<tr><td colspan=3 style='text-align:center;'><input class='submit' type='submit' name='".$strName."' value='".$strValue."'></td><tr>";
	}
	
	function insertProtected($strDescription, $strValue)
	{	
		$this->strForm .= "\n<tr><td>&nbsp;</td><td>".$strDescription.":</td><td>".$strValue."</td><tr>";
	}
	
	function insertSubmitReset($strValue, $strName)
	{
		$this->strForm .= "\n<tr><td colspan=3 style='text-align:center;'><input type='submit' name='".$strName."' value='".$strValue."'></td><tr>";
	}
	
	function insertDoubleInput($strDescription, $strAttr1, $strAttr2)
	{
		$this->strForm .= "\n<tr><td>&nbsp;</td><td>".$strDescription.":</td><tr>";
		$this->strForm .= "<input ".$strAttr1.">&nbsp;";
		$this->strForm .= "<input ".$strAttr2.">";
		$this->strForm .= "</td><tr>";
	}
	
	function generateForm()
	{	
		$strFinalForm = "\n<form action='".$this->strAction."' name='".$this->strFormName."' method='".$this->strMethod."'>";
		$strFinalForm .= "\n<table align='".$this->strAlign."' cellspacing=2 cellpadding=2 border=0 width='".$this->mixWidth."' style='".$this->strStyle."'>";
		$strFinalForm .= $this->strForm;
		$strFinalForm .= "\n</table>\n\n";
		$strFinalForm .= "\n</form>";
		return $strFinalForm;
	}
}



/******************************************************************************/
/* class table                                                                */
/*============================================================================*/
/* generates a table for listings, like user ranking                          */
/******************************************************************************/
class table extends CO
{
  var $strOrder = "ASC";
  var $strNewOrder = "";
  var $strOrderBy = "";
  var $aryOrderImage = array('DESC' => "pics/orderDESC.gif", 'ASC' => "pics/orderASC.gif");
  var $aryRows = array();
  var $aryRowTargets = array();
  var $intCellspacing = 0;
  var $intCellpadding = 4;   
  var $intRowStyle = 1;
  var $strColrStyle = "";
  var $aryHeaders = array();
  var $blnToggleRowStyle = FALSE;
  var $blnSorting = FALSE;
  var $aryColAlignments = array();  
  var $intRowOffset = 0;  
  var $intMaxRows = 0;                /* Maximum of rows to display */
  var $intAllRowCount = 0;            /* Count of all rows from th corresponding DB-query */
  var $intRowCount = 0;               /* Counter, that counts the inserted rows */
  var $strHTMLPageSplitBar = "";
  var $strHTMLContentTable = "";
  var $strHTMLTable = "";  
  var $objSession;
  var $blnTableCreated = FALSE;
  var $aryColWidths = array();
      
  function table($aryHeaders, $strDefaultOrderBy, $intMaxRows, $blnSorting)
  {
    if(is_array($aryHeaders))$this->aryHeaders = $aryHeaders;
    
    $this->intMaxRows = $intMaxRows;    
    
    if(array_key_exists("order", $_GET))$this->strOrder = $_GET['order'];
    else $this->strOrder = "ASC";
    
    if(array_key_exists("orderby", $_GET)) $this->strOrderBy = $_GET['orderby'];
    else $this->strOrderBy = $strDefaultOrderBy;
    
    if(array_key_exists("offset", $_GET)) $this->intRowOffset = $_GET['offset'];
    //else $this->intRowOffset = 0;
    
    /* sorting */
    $this->blnSorting = $blnSorting;       
    
    /* get session */
    require_once("sessionManagement.inc.php");
    $this->objSession = new userSession();
  }  
  
  function setColWidth( $intKey , $aryColWidth )
  {  	
  	$this->aryColWidths[$intKey] = $aryColWidth;
  }
  
  function createHeader()
  {   
    $this->strHTMLContentTable .= "<tr>";
        
    foreach( $this->aryHeaders AS $strCurHeaderKey => $strCurHeader )
    {
		/* create url for sorting */
		$aryGetVars = $_GET;
		if($strCurHeaderKey == $this->strOrderBy)
		{
			if( ($this->strOrder == "ASC") )$aryGetVars['order'] = "DESC";
			else $aryGetVars['order'] = "ASC";
		}
		
		$aryGetVars['orderby'] = $strCurHeaderKey ;     
		$strUrl = http_build_query($aryGetVars);					
				
		if( $this->aryHeaders[$strCurHeaderKey] == $this->aryHeaders[0] )$this->strHTMLContentTable .= "<td class='list_header' style='border-left-style:none;'>";
		else $this->strHTMLContentTable .= "<td class='list_header' >";
		
		/* enable sorting */
		if($this->blnSorting)
		{
			$this->strHTMLContentTable .= "<a class='list_header' href='?".$strUrl."'>";      
			if($this->strOrderBy == $strCurHeaderKey)$this->strHTMLContentTable .= "<img border=0 src=".$this->aryOrderImage[$this->strOrder].">&nbsp;";
			$this->strHTMLContentTable .= $strCurHeader;
			$this->strHTMLContentTable .= "</a>";
		}
		else 
		{
			$this->strHTMLContentTable .= $strCurHeader;
		}            
		$this->strHTMLContentTable .= "</td>";
		}
		$this->strHTMLContentTable .= "</tr>\n";
  }
  
  function insertRow(&$aryColContents)
  {
    array_push($this->aryRows, $aryColContents);
  }
  
  function generateContentTable()
  {    
    $this->strHTMLContentTable = "\n<table width='100%'align='center' class='list' cellspacing=".$this->intCellspacing." cellpadding=".$this->intCellpadding." border=0 style='".$this->strStyle."'>\n";
        
    /* generate Header */
    if(count($this->aryHeaders))$this->createHeader();
    
    /* generate Content Rows */
    foreach($this->aryRows AS $aryCurRow)
    {    
      /* limitate the output (necessarry when creating the table first times) */
      if($this->intRowCount < $this->intMaxRows)
      {
        /* begin new row*/
        $this->strHTMLContentTable .= "<tr ";
        
        /* add row targets */       
        if( @$this->aryRowTargets[$this->intRowCount] != "" )$this->strHTMLContentTable .= " style='cursor:pointer;' onmouseover=\"this.style.backgroundColor='#FFFFFF'\" onmouseout=\"this.style.backgroundColor=''\" onclick=\"window.location.href='".$this->aryRowTargets[$this->intRowCount]."'\" ";       
        
        /* Toggled Styles? */
        if($this->blnToggleRowStyle) $this->strHTMLContentTable .= "class='list_".$this->intRowStyle."' ";
        else $this->strHTMLContentTable .= "class='list' ";
        
        $this->strHTMLContentTable .= ">";
        
        $intColCount = 1;
        foreach($aryCurRow AS $strCurColKey => $strCurCol)
        {   
        	 
            $this->strHTMLContentTable .= "<td ";
            
            /* colwidth? */
            if( array_key_exists($intColCount, $this->aryColWidths) )$this->strHTMLContentTable .= "width=".$this->aryColWidths[$intColCount]." ";            
            /* first col? -> no left border */
            if( $intColCount != 1 )$this->strHTMLContentTable .= "class='list_bordered' ";
            
            /* ColStyle */
            $this->strHTMLContentTable .= "style='";           
            /* Col Alignment*/
            if(count($this->aryColAlignments))$this->strHTMLContentTable .= "text-align:".$this->aryColAlignments[$strCurColKey]."; ";
            else $this->strHTMLContentTable .= "text-align:center; ";
            if($this->strColStyle != "")$this->strHTMLContentTable .= $this->strColStyle;
            $this->strHTMLContentTable .= "'";
                       
                
            $this->strHTMLContentTable .=">".$strCurCol."</td>";     
            
            $intColCount++;
        }
        $this->strHTMLContentTable .= "</tr>\n";    
        if($this->intRowStyle == 1)$this->intRowStyle = 2;
        else $this->intRowStyle = 1;
        
        $this->intRowCount++; 
      }
      
    }//foreach row    
    
    $this->strHTMLContentTable .= "</table>\n";    
  }
  
  function defineColAlignments($aryAlignments)
  {
    $this->aryColAlignments = $aryAlignments;
  }
  
  function setRowTargets(&$aryTagtets)
  {
    $this->aryRowTargets = $aryTagtets; 
  }
  
  function addPageSplitBar()
  { 
    /* read rowcount */       
    $this->intAllRowCount = $this->objSession->getVar("rowcount");
    
    $intMaxPages = ceil($this->intAllRowCount / $this->intMaxRows);
    $intCurPage = round(($this->intRowOffset / $this->intMaxRows) +1 );
    
    $this->strHTMLPageSplitBar = "<tr>";
    //$this->strHTMLPageSplitBar .= "<td style='text-align:left;'>";
    $this->strHTMLPageSplitBar .= "<td style='text-align:center;'>";
    
    
    /* previous-button */    
    if($this->intRowOffset)
    {
      /* create url */      
      $aryGetVars = $_GET;
      $intPrevOffset = ($this->intRowOffset - $this->intMaxRows); 
      if($intPrevOffset < 0) $intPrevOffset = 0;
      $aryGetVars['offset'] = $intPrevOffset;
      $aryGetVars['count'] = ($intPrevOffset + $this->intMaxRows);  
      /* add link */      
      $this->strHTMLPageSplitBar .= "<a style='text-decoration:none;' href='?".http_build_query($aryGetVars)."'><img src='pics/prev_arrow_act.png' border=0></a>";
    }
    else $this->strHTMLPageSplitBar .= "<span><img src='pics/prev_arrow_inact.png' border=0></span>";
    
    //$this->strHTMLPageSplitBar .= "</td>";
    //$this->strHTMLPageSplitBar .= "<td style='text-align:right;'>";
    $this->strHTMLPageSplitBar .= "&nbsp;<span style='color:#46b750; font-weight:bold;'>".$intCurPage."/".$intMaxPages."</span>&nbsp;";
    
    /* next-button */
    if( $this->intAllRowCount > ($this->intRowOffset + $this->intMaxRows) )
    {
      /* create url */      
      $aryGetVars = $_GET;
      $intPrevOffset = ($this->intRowOffset + $this->intMaxRows); 
      $aryGetVars['offset'] = $intPrevOffset;
      $aryGetVars['count'] = ($intPrevOffset + (2*$this->intMaxRows));  
      /* add link */      
      $this->strHTMLPageSplitBar .= "<a style='text-decoration:none;' href='?".http_build_query($aryGetVars)."'><img src='pics/next_arrow_act.png' border=0></a>";
    }
    else $this->strHTMLPageSplitBar .= "<span><img src='pics/next_arrow_inact.png' border=0></span>";
    
    $this->strHTMLPageSplitBar .= "</td>";
    $this->strHTMLPageSplitBar .= "</tr>\n";       
  }
  
  function enableToggledRowStyle()
  {
    $this->blnToggleRowStyle = TRUE;
  }
  
   function enableSorting()
  {
    $this->blnSorting = TRUE;
  }
  
  function generateTable()
  {    
    /* generate the ContentTable */
    $this->generateContentTable();
    
    /* generate complete table */
    $this->strHTMLTable = "\n<table width='".$this->mixWidth."'align='".$this->strAlign."' cellspacing=0 cellpadding=0 border=0>\n";
    $this->strHTMLTable .= "<tr><td>".$this->strHTMLContentTable."</td></tr>\n";
    //$this->strHTMLTable .= "<tr><td colspan=2>".$this->strHTMLContentTable."</tr></td>";
        
    /* add PageSplitBar if enabled and necessarry */  
    if( ($this->strHTMLPageSplitBar != "") AND ($this->intRowCount < $this->intAllRowCount) )$this-> strHTMLTable .= $this->strHTMLPageSplitBar;
    $this->strHTMLTable .= "</table>\n";       
    return $this->strHTMLTable;
  }
  
}


class rightBox extends CO
{
  var $strHTMLBox = "";
  var $strHeader = "";
  var $strContent = "";  

  function setHeader($strHeader)
  {
    $this->strHeader = $strHeader;
  }
  
  function insert($strContent)
  {
    $this->strContent .= $strContent;
  }
  
  function generateBox()
  {  
    $this->strHTMLBox = "<table align='".$this->strAlign."' width='".$this->mixWidth."' cellspacing=2 cellpadding=0 class='right_box'> ";
    $this->strHTMLBox .= "<TR>";
    $this->strHTMLBox .= "<TD style='text-align:center;' class='header'>".$this->strHeader."</TD>";
    $this->strHTMLBox .= "</TR>"; 
    $this->strHTMLBox .= "<td style='text-align:".$this->strContentAlign.";'>".$this->strContent."<td>";
    $this->strHTMLBox .= "<td>";
    $this->strHTMLBox .= "</tr>";
    $this->strHTMLBox .= "</table>";    
    
    return $this->strHTMLBox;
  }
}




function generateMessages($aryMessages)
{
	$strHTML = "";
	
	foreach($aryMessages AS $aryCurrentMessage)
	{
		$strHTML .= "<center class='alert'>".$aryCurrentMessage."</center><br>";	
	}
	return $strHTML;
}




?>
