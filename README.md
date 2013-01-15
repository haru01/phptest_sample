
## install

install composer: https://github.com/composer/composer

execute

    cd <php_sample>
    composer install

## run test
    bin/phpunit --colors test

## testrunner
### setup
    bin/testrunner compile -p vendor/autoload.php

### run auto test
    bin/testrunner phpunit -p vendor/autoload.php -a test

## run Behat
### $PATHに firefoxを通しておく
### seleniumの起動

    java -jar selenium-server-standalone-2.28.0.jar

### 実行

    bin/behat features

## see also
- http://www.phpunit.de/manual/3.8/ja/index.html
- http://phake.digitalsandwich.com/docs/html/
- http://behat.org/
- http://mink.behat.org/
- https://github.com/Behat/MinkExtension-example
- http://michaelheap.com/behat-selenium2-webdriver-with-minkextension/

## todo
- デフォルトでついてくるMatcherの確認
- モック／スタブを使ったテストの方法
- テストデータのセットアップの方法の確認
- CI環境の動作確認
- SahiDriverを試す

## memo

### behatで 使えるステップ一覧を表示したい場合

    bin/behat -di --lang ja

### behatで 使えるストーリーシンタックスを知りたい場合

    bin/behat --story-syntax --lang ja
