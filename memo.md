# 学習記録のメモ

## 文字コード
- BOM(Byte Order Mark)：Unicode形式テキストの先頭に付与される数バイトのデータ
- BOM利用することで、そのテキストがUnicodeで記述されていること、その種類を識別できる。
- PHPではBOM付きのファイルを正しく処理できない場合がある。拡張子がphpのファイルを保存する場合には、必ずBOMなしのUTF-8として保存する。


## httpd.conf/.htacsess
- Apacheの標準的な設定ファイル

## データベースへの接続
1. ```mariadb -u root -p```を入力
2. パスワードを入力