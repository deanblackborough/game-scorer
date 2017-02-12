<?php
/**
* Error controller, all errors are routed to this controller
* 
* @author Dean Blackborough <dean@g3d-development.com
* @copyright G3D Development Limited
*/
class App_ErrorController extends Zend_Controller_Action
{
	/**
	* Init the controller, run any set up code required by all the actions in the controller
	* 
	* @return void
	*/
	public function init()
	{
        Zend_Layout::getMvcInstance()->setLayout('error');
	}

	/**
	* Default error action, dumps the error or exception
	* 
	* @return void
	*/
	public function errorAction()
	{
		$errors = $this->_getParam('error_handler');
		
		if (!$errors || !$errors instanceof ArrayObject) {
			$this->view->message = 'You have reached the error page';
			return;
		}
		
		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = 'Page not found';
			break;
			
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
				$this->getResponse()->setHttpResponseCode(500);
				$this->view->message = 'Application error';
			break;
			
			default:
				// application error
				$this->getResponse()->setHttpResponseCode(500);
				$this->view->message = 'Application error';
			break;
		}

		// conditionally display exceptions
		if ($this->getInvokeArg('displayExceptions') == true) {
			$this->view->exception = $errors->exception;
		}
		
		$this->view->request = $errors->request;
		$this->view->request_params = $errors->request->getParams();
	}
}
