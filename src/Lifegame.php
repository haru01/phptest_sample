<?
namespace lifegame;
use __;

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

  public function tick() {
    $self = $this;
    $this->cells  = __::reduce($this->cells, function($new_cells, $cell) use($self) {

      $countAlive = $self->countAlive($cell->getRow(), $cell->getCol());
      // TODO ポリモーフィズム
      // 過疎
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
