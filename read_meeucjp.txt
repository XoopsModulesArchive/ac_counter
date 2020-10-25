アクセス解析＆カウンター for XOOP2(MySQL 版) Ver.0.90 (2004/06/24)
このモジュールの他に jpgraph が必要です。
( http://www.aditus.nu/jpgraph/ )
PHP 4.2.2 以上 必須。
mbstring GD 必須。
xoops@unadon_v http://u-u-club.ddo.jp/~XOOPS/
unadon　unadon@jobs.co.jp
解析は管理画面内なので見本です。
http://u-u-club.ddo.jp/~board/counter/acanalyze.php
見本サイトのアクセスがしょぼいのでパッとしませんが、ほぼ同じものです。
これを XOOPS2(MySQL 版)に移植しました。
ブロックアクセスでログを記録します。
これまでのグラフは、月間アーカイブが作成されるまで表示できません。
月間アーカイブは月替わりに前月にさかのぼった時、ファイルが無ければ作成さ
れます。
※少し時間がかかるのでガマンして待って下さい。
ページカウンタは、ログカットが発生するとそれ以上カウントアップしません。
仕様です。
AntiDos 機能を持っています。１分間に同一 IP からのアクセス数を指定し、そ
れ以上のアクセスがあれば、指定の URL へ飛ばします。ON/OFF 出来ます。
インストール方法
XOOPS2 のインストールディレクトリ/modules 以下にディレクトリ付きで展開し
て下さい。
http://www.aditus.nu/jpgraph/
上記サイトより、jpgraph-1.15.tar.gz をダウンロードし、/src/ 以下のファイ
ルを /modules/include/src/ にコピーして下さい。
jpg-config.inc を必要に応じて編集しますが、このモジュールではjpgraph 標準
フォントしか使用していません。
※日本語出ません。
/modules/include/countef_conf.php-dist を編集し、countef_conf.php として
同じ場所に保存して下さい。
【例】
//0:ログ数 1:日数 2:月数 でログカット
define("_AC_CUT_TYPE", 0);
日数でカットする場合。
define("_AC_CUT_TYPE", 1);
とします。
1.モジュール管理よりインストール
2.ブロック管理よりブロックを追加。
3.グループ管理にてゲストにモジュールアクセス権限、ブロックアクセス権限を
与えて下さい。
4.archive ディレクトリ以下を書込可能にして下さい。