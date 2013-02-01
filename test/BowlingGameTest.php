<?php
require_once 'src/BowlingGame.php';
use BowlingGame;

class BowlingGameTest extends PHPUnit_Framework_TestCase {

  public function testすべてガーター計算できること() {
    $target = new BowlingGame(array(0,0, 0,0, 0,0, 0,0, 0,0,
                                    0,0, 0,0, 0,0, 0,0, 0,0));
    assertThat($target->score(), equalTo(0));
  }

  public function testすべて1ピン計算できること() {
    $target = new BowlingGame(array(1,1, 1,1, 1,1, 1,1, 1,1,
                                    1,1, 1,1, 1,1, 1,1, 1,1));
    assertThat($target->score(), equalTo(20));
  }

  public function test一フレーム目がスペア計算できること() {
    $target = new BowlingGame(array(6,4, 1,0, 0,0, 0,0, 0,0,
                                    0,0, 0,0, 0,0, 0,0, 0,0));
    assertThat($target->score(), equalTo((6+4+1)+(1+0)));
  }

  public function test一フレーム目がストライク計算できること() {
    $target = new BowlingGame(array(10,  2,3, 0,0, 0,0, 0,0,
                                    0,0, 0,0, 0,0, 0,0, 0,0));
    assertThat($target->score(), equalTo((10+2+3)+(2+3)));
  }

  public function testパーフェクトゲームの計算できること() {
    $target = new BowlingGame(array(10, 10, 10, 10, 10,
                                    10, 10, 10, 10, 10, 10, 10));
    assertThat($target->score(), equalTo(300));
  }

  public function testラストフレーム目がスペア計算できること() {
    $target = new BowlingGame(array(0,0, 0,0, 0,0, 0,0, 0,0,
                                    0,0, 0,0, 0,0, 0,0, 6,4, 1));
    assertThat($target->score(), equalTo((6+4+1)));
  }

  public function testサンプルスコア計算できること() {
    $target = new BowlingGame(array(1,4, 4,5, 6,4, 5,5, 10,
                                    0,1, 7,3, 6,4, 10,  2,8,6));
    assertThat($target->score(), equalTo(133));
  }
}
