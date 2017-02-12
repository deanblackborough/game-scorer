<?php

/**
 * Player controller
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright G3D Development Limited
 */
class Scorer_PlayerController extends Zend_Controller_Action
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
     * Profile for player
     *
     * @return void
     * @throws Exception
     */
    public function profileAction()
    {
        $player_id = intval($this->getRequest()->getParam('player'));

        $model_player = new Scorer_Model_Player();
        $player = $model_player->profile($player_id);

        if ($player === false) {
            throw new Exception('Unable to find player profile');
        }

        $scrabble_stats = $model_player->stats('scrabble', $player_id);
        if ($scrabble_stats === false) {
            throw new Exception('Unable to load stats for player');
        }

        // Games where high score achieved
        $show_high_score_games = false;
        $high_score_games = array();
        $high_score = max($scrabble_stats['home_max'], $scrabble_stats['away_max']);
        if ($high_score !== null && $high_score > 0) {

            $high_score_games = $model_player->gamesByScore('scrabble', $high_score, $player_id);
            if (count($high_score_games) > 0) {
                $show_high_score_games = true;
            }

        };

        $this->view->title = 'User profile';
        $this->view->player = $player;
        $this->view->scrabble_stats = $scrabble_stats;
        $this->view->player_id = $player_id;
        $this->view->show_high_score_games = $show_high_score_games;
        $this->view->high_score_games = $high_score_games;
    }
}
