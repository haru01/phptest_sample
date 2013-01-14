
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
    bin/behat features

## see also
- http://www.phpunit.de/manual/3.8/ja/index.html
- http://phake.digitalsandwich.com/docs/html/
- http://behat.org/
- http://mink.behat.org/
- http://michaelheap.com/behat-selenium2-webdriver-with-minkextension/

## TODO
- モック／スタブを使ったテストの方法
- ブラウザUI付きのテストの確認
- CI環境の動作確認
- テストデータのセットアップの方法の確認
