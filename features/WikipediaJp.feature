# language: ja
フィーチャ: Search
  In order to see a word definition
  As a website user
  I need to be able to search for a word

  シナリオ: Searching for a page that does exist
    前提 "/wiki/Main_Page" を表示している
    もし "search" フィールドに "Behavior Driven Development" と入力する
    かつ "searchButton" ボタンをクリックする
    ならば "software development process" と表示されていること
