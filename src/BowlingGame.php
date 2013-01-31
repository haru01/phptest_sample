<?
class BowlingGame {
  private $pins;

  public function __construct($pins) {
    $this->pins = $pins;
  }

  public function score() {
    $pins = $this->pins;
    $first = 0;

    return __::chain(range(0, 9))
      ->map(function($i) use($pins, &$first) {
        if($pins[$first] == 10) {
          $result = array($pins[$first], $pins[$first+1], $pins[$first+2]);
          $first = $first + 1;
        } elseif($pins[$first] + $pins[$first+1] == 10) {
          $result = array($pins[$first], $pins[$first+1], $pins[$first+2]);
          $first = $first + 2;
        } else {
          $result = array($pins[$first], $pins[$first+1]);
          $first = $first + 2;
        }
        return $result;
      })
      ->flatten()
      ->reduce(function($memo, $n) {
        return $memo + $n;
      }, 0)
      ->value();
  }
}

