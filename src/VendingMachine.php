<?
namespace vendingmachine;
use __;

class VendingMachine {

  private $refundCounter;
  private $drinkStocker;
  private $cashier;

  public function __construct() {
    $this->refundCounter = new DefalutRefundCounter();
    $this->drinkStocker = new DrinkStocker();
    $this->cashier = new Cashier();
  }

  public function setRefundCounter($counter) {
    $this->refundCounter = $counter;
  }

  public function buy($name) {
    $drink = $this->drinkStocker->buy($name);
    if($drink != null) {
      $this->cashier->appendAmount($drink["price"]);
    }
  }

  public function totalAmount() {
    return $this->cashier->totalAmount();
  }

  public function insert($money) {
    if(!$this->cashier->validMoney($money)) {
      $this->refundCounter->out($money);
    } else {
      $this->cashier->append($money);
    }
  }

  public function total() {
    return $this->cashier->insertTotal();
  }

  public function refund() {
    $this->refundCounter->out($this->cashier->insertTotal());
    $this->cashier->clear();
  }

  public function getDrinkInfos() {
    return $this->drinkStocker->toString();
  }

  public function addDrink($name, $quantity, $price = null) {
    $this->drinkStocker->add($name, $quantity, $price);
  }

  public function buyableList() {
    return $this->drinkStocker->buyableList($this->cashier->insertTotal());
  }
}

class Cashier {
  private $totalAmount = 0;
  private $validMoney = array(10, 50, 100, 500, 1000);
  private $insertTotal;
  public function validMoney($money) {
    return __::includ($this->validMoney, $money);
  }

  // TODO naming
  public function appendAmount($money) {
    $this->totalAmount += $money;
    $this->insertTotal -= $money;
  }

  public function totalAmount() {
    return $this->totalAmount;
  }

  public function append($money) {
    $this->insertTotal += $money;
  }

  public function insertTotal() {
    return $this->insertTotal;
  }

  public function clear() {
    $this->insertTotal = 0;
  }
}

// TODO naming
class DefalutRefundCounter {
  public function out($money) {
    print($money);
  }
}

class DrinkStocker {
  private $drinks;

  public function __construct() {
    // TODO ドメインのクラス化
    $this->drinks = array(array("name"=>"Coke", "price"=>120, "quantity"=>5));
  }


  public function toString() {
    return json_encode($this->drinks);
  }

  public function buy($name) {
    foreach ($this->drinks as $index => $value) {
      if($value["name"] == $name) {
        // TODO 金額が足らない、在庫がない
        $this->drinks[$index]["quantity"] -= 1;
        return $this->drinks[$index];
      }
    }
    return null;
  }


  public function add($name, $quantity, $price) {
    foreach ($this->drinks as $index => $value) {
      if($value["name"] == $name) {
        if($price != null) {
          $this->drinks[$index]["price"] = $price;
        }
        $this->drinks[$index]["quantity"] += $quantity;
      } else {
        $this->drinks[] = array("name"=>$name, "price"=>$price, "quantity"=>$quantity);
      }
    }
  }

  public function buyableList($insertTotal) {
    $names = array();
    foreach ($this->drinks as $index => $node) {
      if($insertTotal >= $node['price'] && $node['quantity'] != 0) {
        $names[] = $node['name'];
      }
    }
    return $names;
  }
}
