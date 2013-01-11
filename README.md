
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
    bin/behat features/



## TODO
- モック／スタブを使ったテストの方法
- ブラウザUI付きのテストの確認
- CI環境の動作確認
- データのセットアップの方法の確認
-
