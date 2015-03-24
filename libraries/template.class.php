<?php
class Template {
	
	protected $variables = array();
	protected $_controller;
	protected $_action;
	
	function __construct($controller,$action) {
		$this->_controller = $controller;
		$this->_action = $action;
	}

	/** Set Variables **/

	function set($name,$value) {
		$this->variables[$name] = $value;
	}

	function overrideController($controller) {
		$this->_controller = $controller;
	}

	function overrideAction($action) {
		$this->_action = $action;
	}

	/** Display Template **/
	
    function render() {
		
		global $path;

		extract($this->variables);

		$controller = $this->_controller;
		$action = $this->_action;
		
		if (empty($noheader)) { 
		
			
			include (ROOT . DS . 'views' . DS . 'header.php');	
			
			if (file_exists(ROOT . DS . 'views' . DS . $this->_controller . DS . 'header.php')) {
				include (ROOT . DS . 'views' . DS . $this->_controller . DS . 'header.php');
			}
		}

		if (file_exists(ROOT . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php')) {
			include (ROOT . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');		 
		}
			
		if (empty($noheader)) {
		
			if (file_exists(ROOT . DS . 'views' . DS . $this->_controller . DS . 'footer.php')) {
				include (ROOT . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
			}
			
			include (ROOT . DS . 'views' . DS . 'footer.php');
			
			
		}
		exit();
    }

}