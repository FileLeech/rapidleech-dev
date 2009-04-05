<?php
/**
 * Class HSTemplate
 *
 * High Speed Template
 *
 * @author   AntonShevchuk (AntonShevchuk@gmail.com)
 * @access   public
 * @package  HSTemplate
 * @version  0.1
 * @created  Fri Mar 30 10:48:31 EEST 2007
 */
define ( 'HSTEMPLATE_DISPLAY_ERROR_TEMPLATE_DEFINED', 201 );
define ( 'HSTEMPLATE_DISPLAY_ERROR_TEMPLATE_NOT_DEFINED', 202 );
define ( 'HSTEMPLATE_DISPLAY_ERROR_TEMPLATE_NOT_EXIST', 203 );
/*
display_1 -> template_1 -> var_1
                        -> var_2
                        
          -> template_2 -> var_1
                        -> var_2
                        -> var_3

*/
include_once ('HSTemplateDisplay.php');

class HSTemplate {

	/**
	 * options array
	 * 
	 * template_path - path to template
	 * cache_path    - path to cache
	 * debug         - debug enabled error reporting
	 *
	 * @var array
	 */
	var $_options = array ();

	/**
	 * display array
	 *
	 * @var array
	 */
	var $_displays = array ();

	/**
	 * var for templates
	 *
	 * @var array
	 */
	var $_vars = array ();

	/**
	 * error stack
	 *
	 * @var array
	 */
	var $_errors;

	/**
	 * Constructor of HSTemplate
	 *
	 * @access  public
	 */
	function HSTemplate($aOptions) {
		$this->_options = $aOptions;
	}

	/**
	 * Destructor of HSTemplate 
	 *
	 * @access  public
	 */
	function _HSTemplate() {

	}

	/**
	 * setError
	 *
	 * set error to stack
	 *
	 * @class   HSTemplate
	 * @access  public
	 * @param   string     $aError  error code
	 * @param   string     $aMessage  error message
	 * @return  rettype  return
	 */
	function setError($aError, $aMessage = "") {
		$this->_errors [$aError] = $aMessage;
	}

	/**
	 * isError
	 *
	 * check errors
	 *
	 * @class   HSTemplate
	 * @access  public
	 * @return  rettype  return
	 */
	function isError() {
		if (sizeof ( $this->_errors ) > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * getDisplay
	 *
	 * return link to display object
	 *
	 * @access  public
	 * @param   string     $aName  display name
	 * @param   bool       $aSeparate (if you need use display w/out HSTemplate
	 * @return  HSTemplateDisplay  $TemplateDisplay
	 */
	function &getDisplay($aName, $aSeparate = false) {
		if ($aSeparate) {
			$HSTemplateDisplay = new HSTemplateDisplay ( $this, $aName );
			return $HSTemplateDisplay;
		} else {
			
			if (isset ( $this->_displays [$aName] )) {
				return $this->_displays [$aName];
			} else {
				$this->_displays [$aName] = new HSTemplateDisplay ( $this, $aName );
				return $this->_displays [$aName];
			}
		}
	}

	/**
	 * assignGlobal
	 *
	 * assign variable to all displays and templates
	 *
	 * @access  public
	 * @param   string     $aName   variable name
	 * @param   mixed      $aValue  variable value
	 * @return  rettype  return
	 */
	function assignGlobal($aName, $aValue) {
		$this->_vars [$aName] = & $aValue;
	}

	/**
	 * clearGlobal
	 *
	 * assign variable to all displays and templates
	 *
	 * @access  public
	 * @param   string     $aName   variable name
	 * @return  rettype  return
	 */
	function clearGlobal($aName) {
		if (isset ( $this->_vars [$aName] )) {
			unset ( $this->_vars [$aName] );
		}
	}

	/**
	 * clearAllGlobal
	 *
	 * assign variable to all displays and templates
	 *
	 * @access  public
	 * @param   string     $aName   variable name
	 * @return  rettype  return
	 */
	function clearAllGlobal($aName) {
		$this->_vars = array ();
	}

	/**
	 * display
	 *
	 * display all (or selected) 'display'
	 *
	 * @access  public
	 * @param   string     $aDisplay  display name
	 * @return  mixed  
	 */
	function display($aDisplay = null) {
		if ($aDisplay) {
			if (isset ( $this->_displays [$aDisplay] )) {
				$this->_displays [$aDisplay]->display;
			} else {
				return false;
			}
		} else {
			foreach ( $this->_displays as $aDisplayName => $aDisplayObject ) {
				$aDisplayObject->display ();
			}
		}
	}
}
?>