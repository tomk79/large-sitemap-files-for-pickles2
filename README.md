# TEST: large sitemap files for Pickles 2

[Pickles2](http://pickles2.pxt.jp/) に大きいサイズのサイトマップを処理する負荷テストのための、`sitemap.csv` を生成するツールです。

## Usage

```
$ php generateCsv.php "/?max=10000&bros=5&depth=6"
```

`/dist` の中にCSVファイルが出力されます。
