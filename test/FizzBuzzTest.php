<?php
require_once 'PHPUnit/Framework/Assert/Functions.php';

class FizzBuzzTest extends PHPUnit_Framework_TestCase {
  public function testFIZZBUZZ配列を返すこと() {
    $target = new FizzBuzz();
    $expected = array(1, 2, "FIZZ", 4 , "BUZZ", "FIZZ", 7, 8, "FIZZ", "BUZZ", 11, "FIZZ", 13, 14, "FIZZBUZZ");
    assertThat($target->converts(range(1, 15)), equalTo($expected));
  }
}

class FizzBuzz {
  public function converts($numbers) {
    $self = $this;
    return __::map($numbers, function($n) use($self) {
      return $self->convert($n);
    });
  }

  public function convert($num) {
    if ($num % 15 == 0) {
      return "FIZZBUZZ";
    }
    if ($num % 3 == 0) {
      return "FIZZ";
    }
    if ($num % 5 == 0) {
      return "BUZZ";
    }
    return $num;
  }
}
?>
