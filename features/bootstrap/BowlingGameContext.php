<?php
require_once("src/BowlingGame.php");
require_once 'PHPUnit/Framework/Assert/Functions.php';

use Behat\Behat\Context\BehatContext;

class BowlingGameContext extends BehatContext
{
    private $target;
    public function __construct() {
    }

    /**
     * @Given /^(.+) \((.+)\) の場合$/
     */
    public function setupBowlingGame($desc, $pinsStr) {
      $this->target = new BowlingGame($this->pins($pinsStr));
    }

    /**
     * @Then /^合計は (\d+) である$/
     */
    public function totalScoreIs($score) {
      assertEquals($score, $this->target->score());
    }

    private function pins($pinsStr) {
      $tmp = explode(",", $pinsStr);
      $results = array();

      foreach ($tmp as $str) {
        array_push($results, (int)trim($str));
      }
      return $results;
    }
}
