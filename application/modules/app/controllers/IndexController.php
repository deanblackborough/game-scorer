<?php

/**
 * Root controller for module
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright G3D Development Limited
 */
class App_IndexController extends Zend_Controller_Action
{
    /**
     * Init the controller, run any set up code required by all the actions in the controller
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Index
     *
     * @return void
     */
    public function indexAction()
    {
        $this->view->title = 'Skeleton app';
    }
}
