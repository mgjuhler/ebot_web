<?php

/**
 * Matchs
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    PhpProject1
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Matchs extends BaseMatchs {

    const STATUS_NOT_STARTED = 0;
    const STATUS_STARTING = 1;
    const STATUS_WU_KNIFE = 2;
    const STATUS_KNIFE = 3;
    const STATUS_END_KNIFE = 4;
    const STATUS_WU_1_SIDE = 5;
    const STATUS_FIRST_SIDE = 6;
    const STATUS_WU_2_SIDE = 7;
    const STATUS_SECOND_SIDE = 8;
    const STATUS_WU_OT_1_SIDE = 9;
    const STATUS_OT_FIRST_SIDE = 10;
    const STATUS_WU_OT_2_SIDE = 11;
    const STATUS_OT_SECOND_SIDE = 12;
    const STATUS_END_MATCH = 13;
    const STATUS_ARCHIVE = 14;

    public function getNbRound() {
        return $this->score_a + $this->score_b + 1;
    }

    public function getStatusText() {
        switch ($this->getStatus()) {
            case self::STATUS_NOT_STARTED:
                return "Not started";
            case self::STATUS_STARTING:
                return "Starting";
            case self::STATUS_WU_KNIFE:
                return "Warmup Knife";
            case self::STATUS_KNIFE:
                return "Knife Round";
            case self::STATUS_END_KNIFE:
                return "Waiting choose team";
            case self::STATUS_WU_1_SIDE:
                return "Warmup first side";
            case self::STATUS_FIRST_SIDE:
                return "First side - #" . $this->getNbRound();
            case self::STATUS_WU_2_SIDE:
                return "Warmup second side";
            case self::STATUS_SECOND_SIDE:
                return "Second side - #" . $this->getNbRound();
            case self::STATUS_WU_OT_1_SIDE:
                return "Warmup first side OT";
            case self::STATUS_OT_FIRST_SIDE:
                return "First side OT - #" . $this->getNbRound();
            case self::STATUS_WU_OT_2_SIDE:
                return "Warmup second side OT";
            case self::STATUS_OT_SECOND_SIDE:
                return "Second side OT - #" . $this->getNbRound();
            case self::STATUS_END_MATCH:
                return "Finished";
            case self::STATUS_ARCHIVE:
                return "Archived";
        }
    }

    public function getRoundSummaries() {
        return RoundSummaryTable::getInstance()->createQuery()->where("match_id = ?", $this->getId())->andWhere("map_id = ?", $this->getMap()->getId())->orderBy("round_id ASC")->execute();
    }

    public function getCurrentPlayers() {
        return PlayersTable::getInstance()
                        ->createQuery()
                        ->where("match_id = ?", $this->getId())
                        ->andWhere("map_id = ?", $this->getMap()->getId())
                        ->andWhere("team IN ?", array(array("a", "b")))
                        ->orderBy("pseudo ASC")
                        ->execute();
    }

    public function getLastRound() {
        return RoundSummaryTable::getInstance()
                        ->createQuery()
                        ->where("match_id = ?", $this->getId())->andWhere("map_id = ?", $this->getMap()->getId())
                        ->orderBy("round_id DESC")
                        ->limit(3)
                        ->execute();
    }

}