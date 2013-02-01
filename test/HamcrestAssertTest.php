<?php

class BiscuitTest extends PHPUnit_Framework_TestCase {
    public function testEquals() {
        assertThat("hoge", equalTo("hoge"));
        assertThat("hoge", is("hoge"));
        assertThat("hoge", is(equalTo("hoge")));
    }

    public function testAllOf() {
        assertThat("hoge", allOf(startsWith("ho"), endsWith("ge")));
        assertThat("hoge", not(allOf(startsWith("batstart"), anything())));
    }

    public function testAnyOf() {
        assertThat("hoge", anyOf(startsWith("batstart"), anything()));
        assertThat("hoge", not(anyOf(startsWith("batstart"), startsWith("batstart"))));
    }
}
