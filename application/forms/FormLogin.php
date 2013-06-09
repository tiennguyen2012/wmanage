<?php
class Application_Form_FormLogin extends Zend_Form {
	
	public function __construct($options = array()){
		parent::__construct($options);
		
		$username = new Zend_Form_Element_Text('Username');
		$username->setRequired(true);
		$this->addElement($username);
		
		$password = new Zend_Form_Element_Password('Password');
		$password->setRequired(true);
		$this->addElement($password);
	}
}