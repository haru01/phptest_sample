<?php

class Dummy {

}

class HamcrestAssertTest extends PHPUnit_Framework_TestCase {
  public function test_sugar() {
    assertThat('hoge', equalTo('hoge'));
    assertThat('hoge', is('hoge'));
    assertThat('hoge', is(equalTo('hoge')));
  }

// Core
  public function test_anything() {
    assertThat('hoge', anything());
    assertThat(null, anything());
  }

  public function test_describedAs() {
    assertThat('hoge', describedAs('失敗時の期待値の補足説明', is('hoge')));
  }

  public function test_is() {
    assertThat('hoge', is('hoge'));
  }

// Logical
  public function test_allOf() {
    assertThat('hoge', allOf(startsWith('ho'), endsWith('ge')));
    assertThat('hoge', not(allOf(startsWith('batstart'), anything())));
  }

  public function test_anyOf() {
    assertThat('hoge', anyOf(startsWith('batstart'), anything()));
    assertThat('hoge', not(anyOf(startsWith('batstart'), startsWith('batstart'))));
  }

  public function test_not() {
    assertThat('hoge', not(equalTo('fuga')));
  }

// Object
  public function test_equalTo() {
    assertThat(1, equalTo(1));
  }

  public function test_anInstanceOf() {
    assertThat(new Dummy, anInstanceOf('Dummy'));
  }

  public function test_notNullValue() {
    assertThat('', notNullValue());
  }

  public function test_sameInstance() {
    $value = new Dummy;
    assertThat($value, sameInstance($value));
    assertThat($value, identicalTo($value));
    assertThat($value, not(sameInstance(new Dummy)));
  }

// Number
  public function test_closeTo() {
    assertThat(3.1415, closeTo(3.14, 0.01));
  }

  public function test_greaterThan() {
    assertThat(3.14, greaterThan(3.13));
  }

  public function test_greaterThanOrEqualTo() {
    assertThat(3.14, greaterThanOrEqualTo(3.13));
    assertThat(3.14, greaterThanOrEqualTo(3.14));
  }

  public function test_lessThan() {
    assertThat(3.14, lessThan(3.15));
  }

  public function test_lessThanOrEqualTo() {
    assertThat(3.14, lessThanOrEqualTo(3.14));
    assertThat(3.14, lessThanOrEqualTo(3.15));
  }

  public function test_allOfGreaterLess() {
    assertThat(3.14, allOf(greaterThan(3.13), lessThan(3.15)));
  }

// Collections
  public function test_hasItem() {
    assertThat(array('aaa', 'bbb'), hasItem('aaa'));
    assertThat(array('aaa', 'bbb'), not(hasItem('zzz')));
  }

  public function test_hasItems() {
    assertThat(array('aaa', 'bbb', 'ccc'), hasItems('ccc','aaa'));
    assertThat(array('aaa', 'bbb'), not(hasItems('zzz', 'aaa')));
  }
// Text
  public function test_equalToIgnoringCase() {
    assertThat('HELLO', equalToIgnoringCase('hello'));
  }

  public function test_equalToIgnoringWhiteSpace() {
    assertThat(' hell o  ', equalToIgnoringWhiteSpace('hell o'));
  }

  public function test_containsString() {
    assertThat('hello world!', containsString('lo wo'));
  }

  public function test_startsWith() {
    assertThat('hello world!', startsWith('he'));
  }

  public function test_endsWith() {
    assertThat('hello world!', endsWith('d!'));
  }
// Combinations
  public function test_both() {
    assertThat('hello world!', both(startsWith('he'))->andAlso(endsWith('d!')));
  }

  public function test_either() {
    assertThat('hello world!', either(startsWith('he'))->orElse(startsWith('HE')));
  }
}
