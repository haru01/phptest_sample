<?php

class SpaceTest extends PHPUnit_Framework_TestCase {

  public function test_過疎_生きているセルに隣接する生きたセルが1つ以下ならば_過疎により死滅する() {
    $target = new Space(array(
                        array(0,1,0),
                        array(0,1,0),
                        array(0,0,0)));
    $target->next(); // TODO not immutable
    $this->assertEquals(Cell::DEAD, $target->cellStatus(1, 1));
  }

  public function test_過密_生きているセルに隣接する生きたセルが4つ以上ならば_過密により死滅する() {
    $target = new Space(array(
                        array(1,1,1),
                        array(0,1,1),
                        array(0,0,0)));
    $target->next();
    $this->assertEquals(Cell::DEAD, $target->cellStatus(1, 1));
  }

  public function test_依存_生きているセルに隣接する生きたセルが2つならば次の世代でも生存する() {
    $target = new Space(array(
                        array(1,1,0),
                        array(0,1,0),
                        array(0,0,0)));
    $target->next();
    $this->assertEquals(Cell::ALIVE, $target->cellStatus(1, 1));
  }

  public function test依存_生きているセルに隣接する生きたセルが3つならば次の世代でも生存する() {
    $target = new Space(array(
                        array(1,1,1),
                        array(0,1,0),
                        array(0,0,0)));
    $target->next();
    $this->assertEquals(Cell::ALIVE, $target->cellStatus(1, 1));
  }

  public function test誕生_死んでいるセルに隣接する生きたセルがちょうど3つならば次の世代で誕生する() {
    $target = new Space(array(
                        array(1,1,0),
                        array(1,0,0),
                        array(0,0,0)));
    $target->next();
    $this->assertEquals(Cell::ALIVE, $target->cellStatus(1, 1));
  }
  // TODO サンプルケースのテストを追加する。
}

class Space {
  private $cells;

  public function __construct($seeds) {
    $cells = array();
    __::each($seeds, function($row, $row_i) use(&$cells) {
      __::each($row, function($status, $col_i) use(&$cells, $row_i) {
        array_push($cells, new Cell($status, $row_i, $col_i));
      });
    });
    $this->cells = $cells;
  }

  public function next() {
    $self = $this;
    $this->cells  = __::reduce($this->cells, function($new_cells, $cell) use($self) {
      // 過疎
      $countAlive = $self->countAlive($cell->getRow(), $cell->getCol());
      if ($cell->getStatus() == Cell::ALIVE && $countAlive <= 1) {
        array_push($new_cells, new Cell(Cell::DEAD, $cell->getRow(), $cell->getCol()));
      // 過密
      } elseif ($cell->getStatus() == Cell::ALIVE && $countAlive >= 4) {
        array_push($new_cells, new Cell(Cell::DEAD, $cell->getRow(), $cell->getCol()));
      // 誕生
      } elseif ($cell->getStatus() == Cell::DEAD && $countAlive == 3) {
        array_push($new_cells, new Cell(Cell::ALIVE, $cell->getRow(), $cell->getCol()));
      } else {
        array_push($new_cells, new Cell($cell->getStatus(), $cell->getRow(), $cell->getCol()));
      }
      return $new_cells;
    }, array());
  }

  public function countAlive($row, $col) {
    return __::chain($this->cells)
        ->select(function($cell) use($row, $col) {
          return $cell->getStatus() == Cell::ALIVE &&
               !($row == $cell->getRow() && $col == $cell->getCol() ) &&
                ($row-1 <= $cell->getRow() && $cell->getRow() <= $row+1) &&
                ($col-1 <= $cell->getCol() && $cell->getCol() <= $col+1); })
        ->size()
        ->value();
  }

  public function cellStatus($row, $col) {
    return __::find($this->cells, function($cell) use($row, $col) {
        return $cell->getRow() === $row && $cell->getCol() === $col;
      })->getStatus();
  }

}

class Cell {
  const ALIVE = 1;
  const DEAD = 0;
  private $status;
  private $row;
  private $col;

  public function __construct($status, $row, $col) {
    $this->status = $status;
    $this->row = $row;
    $this->col = $col;
  }

  public function getStatus() {
    return $this->status;
  }

  public function getRow() {
    return $this->row;
  }

  public function getCol() {
    return $this->col;
  }
}
?>
