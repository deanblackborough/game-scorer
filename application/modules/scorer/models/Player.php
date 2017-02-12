<?php

/**
 * Player model
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright G3D Development Limited
 */
class Scorer_Model_Player extends Zend_Db_Table_Abstract
{
    /**
     * Fetch profile
     *
     * @param integer $id
     * @return array|false
     */
    public function profile($id)
    {
        $sql = "SELECT 
                    `players`.`forename`,
                    `players`.`surname`, 
                    `players`.`contact_no`, 
                    `players`.`email`
                FROM 
                    `players` 
                WHERE 
                    `players`.`id` = :player_id";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindValue(':player_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Fetch player stats
     *
     * @param string $game
     * @param integer $id
     *
     * @return array|false
     */
    public function stats($game, $id)
    {
        $sql = "SELECT 
                    COUNT(`results`.`id`) AS `played`,
                    ROUND((SUM(IF(`results`.`player_id` = :player_id, `results`.`player_score`, 0)) + 
                    SUM(IF(`results`.`opponent_id` = :player_id, `results`.`opponent_score`, 0))) / COUNT(`results`.`id`), 0) AS `average`,
                    MAX(IF(`results`.`player_id` = :player_id, `results`.`player_score`, 0)) `home_max`,
                    MAX(IF(`results`.`opponent_id` = :player_id, `results`.`opponent_score`, 0)) `away_max`,
                    MIN(IF(`results`.`player_id` = :player_id, `results`.`player_score`, NULL)) `home_min`,
                    MIN(IF(`results`.`opponent_id` = :player_id, `results`.`opponent_score`, NULL)) `away_min`,
                    SUM(IF(`results`.`player_id` = :player_id AND `results`.`player_score` > `results`.`opponent_score`, 1, 0)) `home_wins`,
                    SUM(IF(`results`.`player_id` = :player_id AND `results`.`player_score` < `results`.`opponent_score`, 1, 0)) `home_losses`, 
                    SUM(IF(`results`.`opponent_id` = :player_id AND `results`.`opponent_score` > `results`.`player_score`, 1, 0)) `away_wins`, 
                    SUM(IF(`results`.`opponent_id` = :player_id AND `results`.`opponent_score` < `results`.`player_score`, 1, 0)) `away_losses` 
                FROM 
                    `results` 
                INNER JOIN 
                    `matches` ON 
                        `results`.`match_id` = `matches`.`id`
                INNER JOIN 
                    `games` ON 
                        `matches`.`game_id` = `games`.`id` AND 
                        `games`.`name` = :game
                WHERE 
                    `results`.`player_id` = :player_id OR 
                    `results`.`opponent_id` = :player_id";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindValue(':player_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':game', $game, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Games by score
     *
     * @param string $game
     * @param integer $score
     * @param integer $id Player id
     * @return array
     */
    public function gamesByScore($game, $score, $id)
    {
        $sql = "SELECT 
                    `player`.`forename` AS `player_forename`, 
                    `player`.`surname` AS `player_surname`, 
                    `opponent`.`forename` AS `opponent_forename`, 
                    `opponent`.`surname` AS `opponent_surname`, 
                    `matches`.`date`, 
                    `locations`.`name` AS `location`, 
                    `results`.`player_score`, 
                    `results`.`opponent_score`
                FROM 
                    `results` 
                INNER JOIN 
                    `players` `player` ON 
                        `results`.`player_id` = `player`.`id`
                INNER JOIN 
                    `players` `opponent` ON 
                        `results`.`opponent_id` = `opponent`.`id`
                INNER JOIN 
                    `matches` ON 
                        `results`.`match_id` = `matches`.`id` 
                INNER JOIN 
                    `games` ON 
                        `matches`.`game_id` = `games`.`id` AND 
                        `games`.`name` = :game
                INNER JOIN 
                    `locations` ON 
                        `matches`.`location_id` = `locations`.`id`
                WHERE 
                    (`results`.`player_id` = :player_id OR `results`.`opponent_id` = :player_id) AND 
                    (`results`.`player_score` = :score OR `results`.`opponent_score` = :score)";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindValue(':game', $game, PDO::PARAM_STR);
        $stmt->bindValue(':score', $score, PDO::PARAM_INT);
        $stmt->bindValue(':player_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * List of players
     *
     * @return array
     */
    public function all()
    {
        $sql = "SELECT 
                    `id`, 
                    `forename`, 
                    `surname`, 
                    `contact_no`, 
                    `email`
                FROM 
                    `players` 
                ORDER BY 
                    `surname`, `forename`";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
