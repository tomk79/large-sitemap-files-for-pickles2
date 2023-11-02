# TEST: large sitemap files for Pickles 2

[Pickles2](http://pickles2.com/) に大きいサイズのサイトマップを処理する負荷テストのための、`sitemap.csv` を生成するツールです。

## Usage

### サイトマップCSVを生成する

```bash
$ php generateSitemapCsv.php "/?max=10000&depth=6&bros=5"
```

`/dist` の中にCSVファイルが出力されます。

### ブログCSVを生成する

```bash
$ php generateBlogmapCsv.php "/?max=10000"
```
