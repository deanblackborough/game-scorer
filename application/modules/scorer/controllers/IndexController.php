<?php

/**
 * Root controller for module
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright G3D Development Limited
 */
class Scorer_IndexController extends Zend_Controller_Action
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
        $limit = 10;
        $matches_model = new Scorer_Model_Match();

        $this->view->title = 'Scrabble scorer';
        $this->view->limit = $limit;
        $this->view->matches = $matches_model->recent('scrabble', $limit);
    }
}
