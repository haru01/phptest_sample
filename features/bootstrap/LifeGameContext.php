<?php
require_once("src/LifeGame.php");

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use lifegame\Space;
use lifegame\Cell;

class LifeGameContext extends BehatContext
{
    private $target;
    public function __construct() {
    }

    /**
     * @Given /^初期設定する$/
     */
    public function setUpLifegame(TableNode $table) {
      $this->target = new Space($this->convertZeroOne($table));
    }

    /**
     * @When /^時間が進む$/
     */
    public function tick() {
        $this->target->tick();
    }

    /**
     * @Then /^中央セルは『(.*)』になる$/
     */
    public function shouldBeDeadOrAlive($status) {
        assertThat($this->target->cellStatus(1, 1), equalTo($this->deadOrAlive($status)));
    }

    private function deadOrAlive($deadOrAlive) {
      if ($deadOrAlive === "死") {
        return Cell::DEAD;
      } elseif ($deadOrAlive === "生") {
        return Cell::ALIVE;
      } else {
        throw new Exception("feature記述ミス:生 or 死 以外は受け付けない");
      }
    }

    private function convertZeroOne(TableNode $table) {
      $results = array();
      foreach ($table->getRows() as $rows) {
        $rowA = array();
        foreach ($rows as $status) {
          array_push($rowA, $status === "x" ? 1 :0);
        }
        array_push($results, $rowA);
      }
      return $results;
    }
}
