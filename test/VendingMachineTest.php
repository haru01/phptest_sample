<?
require_once('src/VendingMachine.php');

use vendingmachine\VendingMachine;

class VendingMachineTest extends PHPUnit_Framework_TestCase {
  public function test_10_50_500_1000円が複数回投入でき合計が取得できること() {
    $vendingMachine = new VendingMachine();
    $refundCounter = $this->getMock('RefundCounter', array('out'));
    $vendingMachine->setRefundCounter($refundCounter);

    $refundCounter->expects($this->never())
                  ->method('out');

    $vendingMachine->insert(10);
    $vendingMachine->insert(50);
    $vendingMachine->insert(100);
    $vendingMachine->insert(500);
    $vendingMachine->insert(1000);

    assertThat($vendingMachine->total(), equalTo(1660));
  }

  public function test払い戻しが出力できること() {
    $refundCounter = $this->getMock('RefundCounter', array('out'));
    $vendingMachine = new VendingMachine();
    $vendingMachine->setRefundCounter($refundCounter);
    $vendingMachine->insert(10);
    $vendingMachine->insert(50);

    $refundCounter->expects($this->once())
                  ->method('out')
                  ->with($this->equalTo(60));

    $vendingMachine->refund();

    assertThat($vendingMachine->total(), equalTo(0));
  }

  public function test扱えないお金はつり銭として出力できる() {
    foreach (array(1, 5, 2000, 10000) as $invalid) {
      $refundCounter = $this->getMock('RefundCounter', array('out'));
      $vendingMachine = new VendingMachine();
      $vendingMachine->setRefundCounter($refundCounter);

      $refundCounter->expects($this->once())
                    ->method('out')
                    ->with($this->equalTo($invalid));

      $vendingMachine->insert($invalid);
    }
  }

  public function tests初期状態で120円コーラが5本格納されている() {
    $vendingMachine = new VendingMachine();

    assertThat($vendingMachine->getDrinkInfos(),
              equalTo('[{"name":"Coke","price":120,"quantity":5}]'));
  }

  public function test既存ストックにジュースを追加できる() {
    $vendingMachine = new VendingMachine();

    $vendingMachine->addDrink('Coke', 2);

    assertThat($vendingMachine->getDrinkInfos(),
              equalTo('[{"name":"Coke","price":120,"quantity":7}]'));
  }

  public function test既存ストックに値段を変えてジュースを追加できる() {
    $vendingMachine = new VendingMachine();

    $vendingMachine->addDrink('Coke', 3, 130);

    assertThat($vendingMachine->getDrinkInfos(),
              equalTo('[{"name":"Coke","price":130,"quantity":8}]'));
  }

  public function test新しいドリンクを追加できる() {
    $vendingMachine = new VendingMachine();

    $vendingMachine->addDrink('Red Pull', 2, 200);

    assertThat($vendingMachine->getDrinkInfos(),
              equalTo('[{"name":"Coke","price":120,"quantity":5},{"name":"Red Pull","price":200,"quantity":2}]'));
  }

  // TODO テスト分解
  public function test投入金額と在庫で購入可能なドリンクのリストを取得できる() {
    $vendingMachine = new VendingMachine();
    // default [{"name":"Coke","price":120,"quantity":5}]

    $vendingMachine->addDrink('Red Pull', 2, 200);
    $vendingMachine->addDrink('Water',    0, 100);


    $vendingMachine->insert(100);
    $vendingMachine->insert(10);

    assertThat("Waterは在庫不足で買えない",
              $vendingMachine->buyableList(),
              equalTo(array()));

    $vendingMachine->insert(10);

    assertThat("金額を満たし在庫のある Cokeが候補",
              $vendingMachine->buyableList(),
              equalTo(array('Coke')));

    $vendingMachine->insert(100);

    assertThat("金額を満たし在庫のある Coke, Red Pullが複数候補",
              $vendingMachine->buyableList(),
              equalTo(array('Coke', 'Red Pull')));

  }

  public function test投入金額を満たし在庫があれば購入でき在庫が減ること() {
    $vendingMachine = new VendingMachine();
    // default [{"name":"Coke","price":120,"quantity":5}]
    // 1回目
    $vendingMachine->insert(1000);

    $vendingMachine->buy("Coke");

    assertThat($vendingMachine->getDrinkInfos(),
              equalTo('[{"name":"Coke","price":120,"quantity":4}]'));
    assertThat($vendingMachine->totalAmount(),
              equalTo(120));

    // 2回目
    $vendingMachine->buy("Coke");

    assertThat($vendingMachine->getDrinkInfos(),
              equalTo('[{"name":"Coke","price":120,"quantity":3}]'));
    assertThat($vendingMachine->totalAmount(),
              equalTo(240));

  }

  public function test購入後返金が出力できること() {
    $vendingMachine = new VendingMachine();
    // default [{"name":"Coke","price":120,"quantity":5}]
    $vendingMachine->insert(1000);
    $vendingMachine->buy("Coke");
    $vendingMachine->buy("Coke");
    $refundCounter = $this->getMock('RefundCounter', array('out'));
    $vendingMachine->setRefundCounter($refundCounter);

    // expected
    $refundCounter->expects($this->once())
                  ->method('out')
                  ->with($this->equalTo(1000 - 120*2));

    // act
    $vendingMachine->refund();
  }


  // TODO 投入金額が足りない場合
  // TODO 在庫がない場合、購入操作を行っても何もしない
  // TODO http://devtesting.jp/tddbc/?TDDBC%E5%A4%A7%E9%98%AA2.0%2F%E8%AA%B2%E9%A1%8C
}
