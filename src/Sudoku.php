<?
namespace sudoku;
use __;

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
