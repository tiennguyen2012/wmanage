<?php
class UserController extends Vts_Controller_AbstractController {
	
	public function init(){
		parent::init();
		
		$this->_helper->layout->disableLayout();
	}
	
	/**
	 * Action to user login. User login need is management
	 * @author tien.nguyen
	 */
	public function loginAction(){
		$form = new Application_Form_FormLogin();
	
		if($this->_request->isPost()){
			$data = $this->_request->getPost();
			if($form->isValid($data)){
				$vtsUser = new Vts_User();
				if($vtsUser->login($data['Username'], $data['Password'])){
					$this->_redirect($this->view->url(array('controller' => 'index',
							'action' => 'index'), null, true));
				}
			}
		}
	
		$this->view->form = $form;
	}
	
	/**
	 * Logout for system. call logout from library
	 * Go to login page if you login successfull
	 * @author tien.nguyen
	 */
	public function logoutAction(){
		$vtsUser = new Vts_User();
		$res = $vtsUser->logout();
		$this->_redirect('/user/login');
	}
}