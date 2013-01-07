<?php
require_once 'PHPUnit/Framework/Assert/Functions.php';
require_once("src/Lifegame.php");


use lifegame\Space;
use lifegame\Cell;

class SpaceTest extends PHPUnit_Framework_TestCase {

  public function test_過疎_生きているセルに隣接する生きたセルが1つ以下ならば_過疎により死滅する() {
    $target = new Space(array(
                        array(0,1,0),
                        array(0,1,0),
                        array(0,0,0)));
    $target->next(); // TODO not immutable
    assertThat($target->cellStatus(1, 1), equalTo(Cell::DEAD));
  }

  public function test_過密_生きているセルに隣接する生きたセルが4つ以上ならば_過密により死滅する() {
    $target = new Space(array(
                        array(1,1,1),
                        array(0,1,1),
                        array(0,0,0)));
    $target->next();
    assertThat($target->cellStatus(1, 1), equalTo(Cell::DEAD));
  }

  public function test_依存_生きているセルに隣接する生きたセルが2つならば次の世代でも生存する() {
    $target = new Space(array(
                        array(1,1,0),
                        array(0,1,0),
                        array(0,0,0)));
    $target->next();
    assertThat($target->cellStatus(1, 1), equalTo(Cell::ALIVE));
  }

  public function test依存_生きているセルに隣接する生きたセルが3つならば次の世代でも生存する() {
    $target = new Space(array(
                        array(1,1,1),
                        array(0,1,0),
                        array(0,0,0)));
    $target->next();
    assertThat($target->cellStatus(1, 1), equalTo(Cell::ALIVE));
  }

  public function test誕生_死んでいるセルに隣接する生きたセルがちょうど3つならば次の世代で誕生する() {
    $target = new Space(array(
                        array(1,1,0),
                        array(1,0,0),
                        array(0,0,0)));
    $target->next();
    assertThat($target->cellStatus(1, 1), equalTo(Cell::ALIVE));
  }
  // TODO サンプルケースのテストを追加する。
}
?>
