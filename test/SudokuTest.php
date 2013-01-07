<?php
require_once 'PHPUnit/Framework/Assert/Functions.php';

require_once("src/Sudoku.php");
use sudoku\Sudoku;

class SudokuTest extends PHPUnit_Framework_TestCase {
  public function test残り１つを行で埋められること() {
    $target = new Sudoku($this->simpleRowQuiz());
    $target->solve();
    assertThat($target->getNum(2, 1), equalTo(6));
    assertThat($target->getNum(9, 9), equalTo(9));
    assertThat($target->getNum(9, 8), equalTo(7));
  }

  public function test残り１つを列で埋められること() {
    $target = new Sudoku($this->simpleColQuiz());
    $target->solve();
    assertThat($target->getNum(1, 2), equalTo(3));
  }

  public function test残り１つをブロックで埋められること() {
    $target = new Sudoku($this->simpleColQuiz());
    $target->solve();
    assertThat($target->getNum(1, 2), equalTo(3));
  }

  public function test行列で埋められること() {
    $target = new Sudoku($this->simpleRowColQuiz());
    $target->solve();
    assertThat($target->getNum(1, 2), equalTo(3));
  }

  public function test行列ブロックで埋められること() {
    $target = new Sudoku($this->simpleRowColBlockQuiz());
    $target->solve();
    assertThat($target->getNum(9, 9), equalTo(9));
  }

  public function testサンプル問題が解けること() {
    $target = new Sudoku($this->sampleQuiz());
    $target->solve();
    assertThat($target->rowNums(1), equalTo(array("5","3","4","6","7","8","9","1","2")));
    assertThat($target->rowNums(5), equalTo(array("4","2","6","8","5","3","7","9","1")));
    assertThat($target->rowNums(9), equalTo(array("3","4","5","2","8","6","1","7","9")));
  }

  public function testブロックで要素がとれること() {
    $target = new Sudoku($this->simpleRowQuiz());
    assertThat($target->blockNums(1,7), equalTo(array("9", "1", "2", "3", "4", "8", "5", "6", "7")));
    assertThat($target->blockNums(1,8), equalTo(array("9", "1", "2", "3", "4", "8", "5", "6", "7")));
    assertThat($target->blockNums(1,9), equalTo(array("9", "1", "2", "3", "4", "8", "5", "6", "7")));
    assertThat($target->blockNums(9,9), equalTo(array("2", "8", "4", "6", "3", "5", "1", "7", "9")));
  }


  public function simpleRowQuiz() {
    return <<<EOF
-,3,4,6,7,8,9,1,2
-,7,2,1,9,5,3,4,8
-,9,8,3,4,2,5,6,7
-,5,9,7,6,1,4,2,3
-,2,6,8,5,3,7,9,1
-,1,3,9,2,4,8,5,6
-,6,1,5,3,7,2,8,4
-,8,7,4,1,9,6,3,5
-,4,5,2,8,6,1,7,9
EOF;
  }

  public function simpleColQuiz() {
    return <<<EOF
-,-,-,-,-,-,-,-,-
6,7,2,1,9,5,3,4,8
1,9,8,3,4,2,5,6,7
8,5,9,7,6,1,4,2,3
4,2,6,8,5,3,7,9,1
7,1,3,9,2,4,8,5,6
9,6,1,5,3,7,2,8,4
2,8,7,4,1,9,6,3,5
3,4,5,2,8,6,1,7,9
EOF;
  }

  public function simpleRowColQuiz() {
    return <<<EOF
-,-,4,6,7,8,9,1,2
-,-,2,1,9,5,3,4,8
1,9,8,3,4,2,5,6,7
8,5,9,7,6,1,4,2,3
4,2,6,8,5,3,7,9,1
7,1,3,9,2,4,8,5,6
9,6,1,5,3,7,2,8,4
2,8,7,4,1,9,6,3,5
3,4,5,2,8,6,1,7,9
EOF;
  }

  public function simpleRowColBlockQuiz() {
    return <<<EOF
5,3,4,6,7,8,9,1,2
6,7,2,1,9,5,3,4,8
1,9,8,3,4,2,5,6,7
8,5,9,7,6,1,4,2,3
4,2,6,8,5,3,7,9,-
7,1,3,9,2,4,8,5,-
9,6,1,5,3,7,-,8,4
2,8,7,4,1,9,6,3,5
3,4,5,2,-,-,1,-,-
EOF;
  }

  public function sampleQuiz() {
    return <<<EOF
5,3,-,-,7,-,-,-,-
6,-,-,1,9,5,-,-,-
-,9,8,-,-,-,-,6,-
8,-,-,-,6,-,-,-,-
4,-,-,8,-,3,-,-,1
7,-,-,-,2,-,-,-,6
-,6,-,-,-,-,2,8,-
-,-,-,4,1,9,-,-,5
-,-,-,-,8,-,-,7,-
EOF;
  }


  public function kaitou() {
    return <<<EOF
5,3,4,6,7,8,9,1,2
6,7,2,1,9,5,3,4,8
1,9,8,3,4,2,5,6,7
8,5,9,7,6,1,4,2,3
4,2,6,8,5,3,7,9,1
7,1,3,9,2,4,8,5,6
9,6,1,5,3,7,2,8,4
2,8,7,4,1,9,6,3,5
3,4,5,2,8,6,1,7,9
EOF;
  }
}
?>
