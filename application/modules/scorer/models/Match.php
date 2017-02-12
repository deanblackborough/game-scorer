<?php

/**
 * Match model
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright G3D Development Limited
 */
class Scorer_Model_Match extends Zend_Db_Table_Abstract
{
    /**
     * Fetch the list of recent matches
     *
     * @param string $game
     * @param integer $limit
     * @return array
     */
    public function recent($game, $limit = 10)
    {
        $sql = "SELECT 
                    `games`.`name` AS `game`, 
                    `matches`.`date`, 
                    `locations`.`name` AS `location`, 
                    `p1`.`forename` AS p1_forename,
                    `p1`.`surname` AS p1_surname,
                    `p2`.`forename` AS p2_forename,
                    `p2`.`surname` AS p2_surname,
                    `results`.`player_score` AS `p1_score`,
                    `results`.`opponent_score` AS `p2_score`
                FROM 
                    `matches` 
                INNER JOIN 
                    `results` ON 
                        `matches`.`id` = `results`.`match_id`
                INNER JOIN
                    `players` `p1` ON 
                        `results`.`player_id` = `p1`.id 
                INNER JOIN 
                    `players` `p2` ON 
                        `results`.`opponent_id` = `p2`.`id`                  
                INNER JOIN 
                    `locations` ON 
                        `matches`.`location_id` = `locations`.`id` 
                INNER JOIN 
                    `games` ON 
                        `matches`.`game_id` = `games`.`id` AND 
                        `games`.`name` = :game
                ORDER BY 
                    `matches`.`date` DESC
                LIMIT :limit";
        $stmt = $this->_db->prepare($sql);
        $stmt->bindValue(':game', $game, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
