<?php
class BowlingGame {
  private $rolls;

  public function __construct($rolls) {
    $this->rolls = $rolls;
  }

  public function score() {
    $r = $this->rolls;
    $first = 0;

    return __::chain(range(0, 9))
    ->map(function($i) use($r, &$first) {
      if($r[$first] == 10) {
        $result = array($r[$first], $r[$first+1], $r[$first+2]);
        $first = $first + 1;
      } elseif($r[$first] + $r[$first+1] == 10) {
        $result = array($r[$first], $r[$first+1], $r[$first+2]);
        $first = $first + 2;
      } else {
        $result = array($r[$first], $r[$first+1]);
        $first = $first + 2;
      }
      return $result;
    })
    ->flatten()
    ->reduce(function($memo, $n) {
      return $memo + $n;
    }, 0)->value();
  }
}

class BowlingGameTest extends PHPUnit_Framework_TestCase {

  public function testすべてガーター計算できること() {
    $target = new BowlingGame(array(0,0, 0,0, 0,0, 0,0, 0,0,
                                    0,0, 0,0, 0,0, 0,0, 0,0));
    $this->assertEquals(0, $target->score());
  }

  public function testすべて1ピン計算できること() {
    $target = new BowlingGame(array(1,1, 1,1, 1,1, 1,1, 1,1,
                                    1,1, 1,1, 1,1, 1,1, 1,1));
    $this->assertEquals(20, $target->score());
  }

  public function test一フレーム目がスペア計算できること() {
    $target = new BowlingGame(array(6,4, 1,0, 0,0, 0,0, 0,0,
                                    0,0, 0,0, 0,0, 0,0, 0,0));
    $this->assertEquals((6+4+1) + 1, $target->score());
  }

  public function test一フレーム目がストライク計算できること() {
    $target = new BowlingGame(array(10,  2,3, 0,0, 0,0, 0,0,
                                    0,0, 0,0, 0,0, 0,0, 0,0));
    $this->assertEquals((10+2+3)+(2+3), $target->score());
  }

  public function testパーフェクトゲームの計算できること() {
    $target = new BowlingGame(array(10, 10, 10, 10, 10,
                                    10, 10, 10, 10, 10, 10, 10));
    $this->assertEquals(300, $target->score());
  }

  public function testラストフレーム目がスペア計算できること() {
    $target = new BowlingGame(array(0,0, 0,0, 0,0, 0,0, 0,0,
                                    0,0, 0,0, 0,0, 0,0, 6,4, 1));
    $this->assertEquals((6+4+1), $target->score());
  }

  public function testサンプルスコア計算できること() {
    $target = new BowlingGame(array(1,4, 4,5, 6,4, 5,5, 10,
                                    0,1, 7,3, 6,4, 10,  2,8,6));
    $this->assertEquals((133), $target->score());
  }
}
?>
