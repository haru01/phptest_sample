<?php

class SudokuTest extends PHPUnit_Framework_TestCase {
  public function test残り１つを行で埋められること() {
    $target = new Sudoku($this->simpleRowQuiz());
    $target->solve();
    $this->assertEquals(6, $target->getNum(2, 1));
    $this->assertEquals(9, $target->getNum(9, 9));
    $this->assertEquals(7, $target->getNum(9, 8));
  }

  public function test残り１つを列で埋められること() {
    $target = new Sudoku($this->simpleColQuiz());
    $target->solve();
    $this->assertEquals(3, $target->getNum(1, 2));
  }

  public function test残り１つをブロックで埋められること() {
    $target = new Sudoku($this->simpleColQuiz());
    $target->solve();
    $this->assertEquals(3, $target->getNum(1, 2));
  }

  public function test行列で埋められること() {
    $target = new Sudoku($this->simpleRowColQuiz());
    $target->solve();
    $this->assertEquals(3, $target->getNum(1, 2));
  }

  public function test行列ブロックで埋められること() {
    $target = new Sudoku($this->simpleRowColBlockQuiz());
    $target->solve();
    $this->assertEquals(9, $target->getNum(9, 9));
  }

  public function testサンプル問題が解けること() {
    $target = new Sudoku($this->sampleQuiz());
    $target->solve();
    $this->assertEquals(array("5","3","4","6","7","8","9","1","2"),
                $target->rowNums(1));
    $this->assertEquals(array("4","2","6","8","5","3","7","9","1"),
                $target->rowNums(5));
    $this->assertEquals(array("3","4","5","2","8","6","1","7","9"),
                $target->rowNums(9));
  }

  public function testブロックで要素がとれること() {
    $target = new Sudoku($this->simpleRowQuiz());
    $this->assertEquals(array("9", "1", "2",
                              "3", "4", "8",
                              "5", "6", "7"), $target->blockNums(1,7));
    $this->assertEquals(array("9", "1", "2",
                              "3", "4", "8",
                              "5", "6", "7"), $target->blockNums(1,8));
    $this->assertEquals(array("9", "1", "2",
                              "3", "4", "8",
                              "5", "6", "7"), $target->blockNums(1,9));
    $this->assertEquals(array("2", "8", "4",
                              "6", "3", "5",
                              "1", "7", "9"), $target->blockNums(9,9));
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

class Sudoku {
  private $cells;

  public function __construct($quiz) {
    $cells = array();
    $rows = split("\n", $quiz);
    $self = $this;
    __::each($rows, function($row, $row_i) use(&$cells, $self) {
      $nums = split(",", $row);
      __::each($nums, function($num, $col_i) use(&$cells, $row_i, $self) {
        array_push($cells, new Cell($num, $row_i + 1, $col_i + 1, $self));
      });
    });
    $this->cells = $cells;
  }

  public function solve() {
    $tmp = 0;
    while( $tmp < 99) {
      $tmp = $tmp + 1;
      __::each($this->cells, function($cell) {
        $cell->fill();
      });
    }
  }

  public function getNum($row, $col) {
    return __::find($this->cells, function($cell) use($row, $col) {
      return $cell->getRow() == $row && $cell->getCol() == $col;
    })->getNum();
  }

  public function rowNums($row) {
    return __::chain($this->cells)
        ->select(function($cell) use($row) {
          return $cell->getRow() == $row; })
        ->map(function($cell){
          return $cell->getNum(); })
        ->value();
  }

  public function colNums($col) {
    return __::chain($this->cells)
        ->select(function($cell) use($col) {
          return $cell->getCol() == $col; })
        ->map(function($cell){
          return $cell->getNum(); })
        ->value();
  }

  public function blockNums($row, $col) {
    $rowFrom = $this->blockFromNum($row);
    $rowTo = $rowFrom + 2;
    $colFrom = $this->blockFromNum($col);
    $colTo = $colFrom + 2;

    return __::chain($this->cells)
        ->select(function($cell) use($rowFrom, $rowTo, $colFrom, $colTo) {
          return ($rowFrom <= $cell->getRow() && $cell->getRow() <= $rowTo)
                    && ($colFrom <= $cell->getCol() && $cell->getCol() <= $colTo); })
        ->map(function($cell){
          return $cell->getNum(); })
        ->value();
  }

  // TODO
  private function blockFromNum($num) {
    if (1 <= $num  && $num <= 3) {
      return 1;
    }
    if (4 <= $num  && $num <= 6) {
      return 4;
    }
    if (7 <= $num  && $num <= 9) {
      return 7;
    }
  }
}

class Cell {
  private $num;
  private $row;
  private $col;
  private $sudoku;

  public function __construct($num, $row, $col, $sudoku) {
    $this->num = $num;
    $this->row = $row;
    $this->col = $col;
    $this->sudoku = $sudoku;
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

  public function fill() {
    if ($this->num != "-") {
      return;
    }

    $rowPossibleNums = __::difference($this->oneToNine(), $this->sudoku->rowNums($this->row));
    $colPossibleNums = __::difference($this->oneToNine(), $this->sudoku->colNums($this->col));
    $blockPossibleNums = __::difference($this->oneToNine(), $this->sudoku->blockNums($this->row, $this->col));

    $possibleNums = __::intersection($rowPossibleNums, $colPossibleNums, $blockPossibleNums);
    if (count($possibleNums) == 1) {
      $this->num = $possibleNums[0];
    }
  }

  private function oneToNine() {
    return array("1", "2", "3", "4", "5", "6", "7", "8", "9");
  }
}

?>
