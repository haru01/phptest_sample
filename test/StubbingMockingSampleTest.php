<?php

// neighbor object
interface HogeBlogServiceClient {
  public function getArticles();
}

interface LOGGER {
  public function info($message);
}

class NetworkError extends Exception { }

class Article {
  public $title;
  public $content;
  public $viewCount;

  public function __construct($title, $content, $viewCount) {
    $this->title = $title;
    $this->content = $content;
    $this->viewCount = $viewCount;
  }
}

// SUT
class ViewCounter {
  private $blogClient;
  private $prevCount;
  public function setLogger(LOGGER $logger){
    $this->logger = $logger;
  }
  public function setHogeBlogServiceClient($client) {
    $this->blogClient = $client;
  }

  public function count() {
    try {
      $this->prevCount = __::reduce($this->blogClient->getArticles(),
          function($memo, $article) {
            return $memo + $article->viewCount;
          }, 0);
    } catch(NetworkError $e) {
      $this->logger->info("ネットワークエラー発生。前回の総閲覧数を返します");
    }
    return $this->prevCount;
  }
}

class ViewCounterTest extends PHPUnit_Framework_TestCase {
  public function articlesTotalViewsAre11() {
    return array(new Article("titleA", "contentA", 3),
                 new Article("titleB", "contentB", 8));
  }

  public function setUp() {
    $this->counter = new ViewCounter();
    $this->clientStub = Phake::mock('HogeBlogServiceClient');
    $this->loggerMock = Phake::mock('LOGGER');
    $this->counter->setHogeBlogServiceClient($this->clientStub);
    $this->counter->setLogger($this->loggerMock);
  }

  // Stubbing サンプル
  public function test総閲覧数が計算できること() {
    Phake::when($this->clientStub)->getArticles()->thenReturn($this->articlesTotalViewsAre11());
    assertThat($this->counter->count(), equalTo(11));
  }

  public function test総閲覧数が計算できること_ネットワークエラーの場合は前回の総数を返す() {
    Phake::when($this->clientStub)->getArticles()->thenReturn($this->articlesTotalViewsAre11());
    $this->counter->count(); // 前回のカウントを内部で保持

    Phake::when($this->clientStub)->getArticles()->thenThrow(new NetworkError('テスト用'));
    assertThat($this->counter->count(), equalTo(11));
  }

  public function testログを出すこと_ネットワークエラーの場合() {
    Phake::when($this->clientStub)->getArticles()->thenThrow(new NetworkError('テスト用'));
    $this->counter->count();
    // Mocking サンプル
    Phake::verify($this->loggerMock)->info("ネットワークエラー発生。前回の総閲覧数を返します");
  }
}
