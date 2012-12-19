<?php

class SudokuTest extends PHPUnit_Framework_TestCase {

  // public function test残り１つを行で埋められること() {
  //   $target = new Sudoku($this->rowSample());
  //   $target->solve();
  //   $this->assertEquals(3, $target->getNum(1, 2));
  // }

  public function test問題を初期設定できること() {
    $target = new Sudoku($this->colSample());
    $this->assertEquals("9", $target->getNum(9, 9));
  }

  public function colSample() {
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

  public function rowSample() {
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

class Sudoku {
  private $cells;

  public function __construct($quiz) {
    $cells = array();
    $rows = split("\n", $quiz);
    __::each($rows, function($row, $row_i) use(&$cells) {
      $nums = split(",", $row);
      __::each($nums, function($num, $col_i) use(&$cells, $row_i) {
        // toto
        array_push($cells, new Cell($num, $row_i + 1, $col_i + 1));
      });
    });
    $this->cells = $cells;
  }

  public function solve() {

  }

  public function getNum($row, $col) {
    return __::find($this->cells, function($cell) use($row, $col) {
      return $cell->getRow() == $row && $cell->getCol() == $col;
    })->getNum();
  }
}

class Cell {
  private $num;
  private $row;
  private $col;

  public function __construct($num, $row, $col) {
    $this->num = $num;
    $this->row = $row;
    $this->col = $col;
  }

  public function getNum() {
    return $this->num;
  }

  public function getRow() {
    return $this->row;
  }

  public function getCol() {
    return $this->col;
  }
}

?>
