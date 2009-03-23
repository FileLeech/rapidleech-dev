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

define ( "HSTEMPLATE_DISPLAY_MAIN", "HSTEMPLATE_MAIN" );

/*
display_1 -> template_1 -> var_1
                        -> var_2
                        
          -> template_2 -> var_1
                        -> var_2
                        -> var_3

*/

class HSTemplateDisplay {

	/**
	 * HSTemplate
	 *
	 * @var HSTemplate
	 */
	var $_HSTemplate;

	/**
	 * display name
	 *
	 * @var string
	 */
	var $_name;

	/**
	 * cache status
	 *
	 * @var bool
	 */
	var $_caching = false;

	/**
	 * cache lifetime
	 *
	 * @var integer
	 */
	var $_caching_lifetime = 3600;

	/**
	 * cache id
	 *
	 * @var integer
	 */
	var $_caching_id = null;

	/**
	 * options array
	 *
	 * @var array
	 */
	var $_options = array ();

	/**
	 * templates array
	 *
	 * @var array
	 */
	var $_templates = array ();

	/**
	 * var for templates
	 *
	 * @var array
	 */
	var $_vars = array ();

	/**
	 * Constructor of HSTemplate
	 *
	 * @param   HSTemplate $aHSTemplate
	 * @access  public
	 */
	function HSTemplateDisplay(&$aHSTemplate, $aName) {
		$this->_HSTemplate = & $aHSTemplate;
		$this->_name = $aName;
	}

	/**
	 * Destructor of HSTemplate 
	 *
	 * @access  public
	 */
	function _HSTemplateDisplay() {

	}

	/**
	 * addTemplate
	 *
	 * add template to stack
	 *
	 * @author  dark
	 * @class   HSTemplateDisplay
	 * @access  public
	 * @param   string     $aTemplateName  template name
	 * @param   string     $aTemplateFile  template file
	 * @param   string     $aTemplatePath  template path
	 * @return  void
	 */
	function addTemplate($aTemplateName, $aTemplateFile, $aTemplatePath = null) {
		if (! $aTemplatePath) {
			$aTemplatePath = $this->_HSTemplate->_options ['template_path'] . DIRECTORY_SEPARATOR . $this->_name . DIRECTORY_SEPARATOR;
		}
		
		if (isset ( $this->_templates [$aTemplateName] )) {
			$this->_HSTemplate->setError ( HSTEMPLATE_DISPLAY_ERROR_TEMPLATE_DEFINED, $aTemplateName );
			return false;
		} elseif (! file_exists ( $aTemplatePath . $aTemplateFile )) {
			$this->_HSTemplate->setError ( HSTEMPLATE_DISPLAY_ERROR_TEMPLATE_DEFINED, $aTemplatePath . $aTemplateFile );
			return false;
		} else {
			$this->_templates [$aTemplateName] = $aTemplatePath . $aTemplateFile;
			if (! isset ( $this->_vars [$aTemplateName] )) {
				$this->_vars [$aTemplateName] = array ();
			}
			return true;
		}
	}

	/**
	 * set cache
	 *
	 * @author  dark
	 * @class   HSTemplateDisplay
	 * @access  public
	 * @param   mixed      $aId            
	 * @param   integer    $aTime
	 * @return  void
	 */
	function setCache($aId, $aTime = 3600) {
		$this->_caching = true;
		$this->_caching_lifetime = $aTime;
		
		$this->setCacheId ( $aId );
		
		if ($this->_caching) {
			if (is_dir ( $this->_HSTemplate->_options ['cache_path'] . DIRECTORY_SEPARATOR . $this->_name )) {
				if (! is_writable ( $this->_HSTemplate->_options ['cache_path'] . DIRECTORY_SEPARATOR . $this->_name )) {
					trigger_error ( 'HSTemplate Error: Cannot write directory "' . $this->_HSTemplate->_options ['cache_path'] . DIRECTORY_SEPARATOR . $this->_name . '"', E_USER_ERROR );
				}
			} else {
				if (! @mkdir ( $this->_HSTemplate->_options ['cache_path'] . DIRECTORY_SEPARATOR . $this->_name )) {
					trigger_error ( 'HSTemplate Error: Cannot create directory "' . $this->_HSTemplate->_options ['cache_path'] . DIRECTORY_SEPARATOR . $this->_name . '"', E_USER_ERROR );
				}
			}
		}
		
		return true;
	}

	/**
	 * set cache id
	 *
	 * @author  dark
	 * @class   HSTemplateDisplay
	 * @access  public
	 * @param   mixed       $aId  
	 * @return  void
	 */
	function setCacheId($aId) {
		if (is_array ( $aId )) {
			$this->_caching_id = implode ( "::", $aId );
		} else {
			$this->_caching_id = $aId;
		}
		
		return true;
	}

	/**
	 * Check cache
	 *
	 * @author  dark
	 * @class   HSTemplateDisplay
	 * @access  public
	 * @return  void
	 */
	function isCached() {
		if ($this->_caching_id === null) {
			return false;
		}
		
		$aFileName = $this->_getCacheFile ();
		
		if (! file_exists ( $aFileName )) {
			return false;
		} else {
			
			if ((filemtime ( $aFileName ) + $this->_caching_lifetime) < time ()) {
				return false;
			} else {
				return true;
			}
		}
	}

	/**
	 * create cache file
	 *
	 * @author  dark
	 * @class   HSTemplateDisplay
	 * @access  private
	 * @param   string     $aContent       content for file
	 * @return  void
	 */
	function _createCache($aContent = '') {
		$aFileName = $this->_getCacheFile ();
		
		@unlink ( $aFileName );
		
		// create file with cache
		$fp = fopen ( $aFileName, "at" ) or die ( trigger_error ( 'HSTemplate Error: Cannot create file "' . $aFileName . '"', E_USER_ERROR ) );
		
		flock ( $fp, LOCK_EX ); // lock file
		rewind ( $fp ); // rewind the position of a file pointer
		fwrite ( $fp, $aContent ); // write data
		flock ( $fp, LOCK_UN ); // release the lock
		fclose ( $fp ); // close file
	}

	/**
	 * get cache file name
	 *
	 * @author  dark
	 * @class   HSTemplateDisplay
	 * @access  private
	 * @return  string
	 */
	function _getCacheFile() {
		return $this->_HSTemplate->_options ['cache_path'] . DIRECTORY_SEPARATOR . $this->_name . DIRECTORY_SEPARATOR . md5 ( $this->_name . '#' . $this->_caching_id ) . '.html';
	}

	/**
	 * assign
	 *
	 * assign variable to all displays and templates
	 *
	 * @access  public
	 * @param   string     $aName   variable name
	 * @param   mixed      $aValue  variable value
	 * @param   string     $aTemplate template name
	 * @return  rettype  return
	 */
	function assign($aName, $aValue, $aTemplate = HSTEMPLATE_DISPLAY_MAIN) {
		$this->_vars [$aTemplate] [$aName] = & $aValue;
	}

	/**
	 * clear
	 *
	 * assign variable to all displays and templates
	 *
	 * @access  public
	 * @param   string     $aName   variable name
	 * @param   string     $aTemplate template name
	 * @return  rettype  return
	 */
	function clear($aName, $aTemplate = HSTEMPLATE_DISPLAY_MAIN) {
		if (isset ( $this->_vars [$aTemplate] [$aName] )) {
			unset ( $this->_vars [$aTemplate] [$aName] );
		}
	}

	/**
	 * clearAll
	 *
	 * assign variable to all displays and templates
	 *
	 * @access  public
	 * @param   string     $aName   variable name
	 * @return  rettype  return
	 */
	function clearAll($aName) {
		$this->_vars = array ();
	}

	/**
	 * display
	 *
	 * display all (or selected) template
	 *
	 * @access  public
	 * @param   string     $aTemplate  template name
	 * @return  rettype  return
	 */
	function display($aTemplate = null) {
		$this->fetch ( $aTemplate, true );
	}

	/**
	 * fetch
	 *
	 * cetch all (or selected) template
	 *
	 * @access  public
	 * @param   string     $aTemplate  template name
	 * @param   bool       $aDisplay   template name
	 * @return  rettype  return
	 */
	function fetch($aTemplate = null, $aDisplay = false) {
		$oldErrorReporting = error_reporting ();
		
		if ($this->_HSTemplate->_options ['debug']) {
			error_reporting ( E_ALL );
		} else {
			error_reporting ( E_ALL ^ E_NOTICE );
		}
		
		if ($this->_caching && $this->isCached ()) {
			if (! $aDisplay) {
				ob_start ();
			}
			
			include_once ($this->_getCacheFile ());
			
			if (! $aDisplay) {
				$cache = ob_get_contents ();
				ob_end_clean ();
			}
		} elseif ($this->_caching && ! $this->isCached ()) {
			ob_start ();
			$this->_fetch ( $aTemplate );
			$cache = ob_get_contents ();
			ob_end_clean ();
			
			$this->_createCache ( $cache );
			// for cache system 
			if (! $aDisplay) {
				return $cache;
			} else {
				echo $cache;
			}
		} elseif (! $this->_caching) {
			if (! $aDisplay) {
				ob_start ();
			}
			
			$this->_fetch ( $aTemplate );
			
			if (! $aDisplay) {
				$cache = ob_get_contents ();
				ob_end_clean ();
			}
		}
		
		error_reporting ( $oldErrorReporting );
	
	}

	/**
	 * _fetch
	 *
	 * fetch all (or selected) template
	 *
	 * @access  public
	 * @param   string     $aTemplate  template name
	 * @return  rettype  return
	 */
	function _fetch($aTemplate = null) {
		if ($aTemplate) {
			if (! isset ( $this->_templates [$aTemplate] )) {
				$this->_HSTemplate->setError ( HSTEMPLATE_DISPLAY_ERROR_TEMPLATE_NOT_DEFINED, $aTemplate );
				return false;
			}
			$this->_display ( $aTemplate );
		} else {
			if (sizeof ( $this->_templates ) == 0) {
				$this->_HSTemplate->setError ( HSTEMPLATE_DISPLAY_ERROR_TEMPLATE_NOT_DEFINED, $aTemplate );
				return false;
			}
			foreach ( $this->_templates as $aTemplateName => $aTemplateFile ) {
				$this->_display ( $aTemplateName );
			}
		}
	}

	/**
	 * _display
	 *
	 * _display selected template
	 *
	 * @access  private
	 * @param   string     $aTemplate  template name
	 * @return  rettype  return
	 */
	function _display($aTemplate) {
		if (sizeof ( $this->_HSTemplate->_vars ) > 0) {
			extract ( $this->_HSTemplate->_vars );
		}
		
		if (sizeof ( $this->_vars [HSTEMPLATE_DISPLAY_MAIN] ) > 0) {
			extract ( $this->_vars [HSTEMPLATE_DISPLAY_MAIN] );
		}
		
		if (sizeof ( $this->_vars [$aTemplate] ) > 0) {
			extract ( $this->_vars [$aTemplate] );
		}
		
		include_once ($this->_templates [$aTemplate]);
		
		if (sizeof ( $this->_vars [HSTEMPLATE_DISPLAY_MAIN] ) > 0) foreach ( $this->_vars [HSTEMPLATE_DISPLAY_MAIN] as $aKey => $aValue ) {
			unset ( $$aKey );
		}
		
		if (sizeof ( $this->_vars [$aTemplate] ) > 0) foreach ( $this->_vars [$aTemplate] as $aKey => $aValue ) {
			unset ( $$aKey );
		}
	}
}
?>