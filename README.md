embrr, a fork from embr
========
This is a fork from embr `r91`, (partially) updated to Twitter REST API `v1.1`.

----
As I know almost nothing about PHP and twitter API, there could be bugs everywhere.

All helps are welcomed!

----

配置说明
========

依赖 `php-bcmath` 组件。

如果出现 Fav, DM 等页面无法翻页的问题，尝试更改 `php.ini` 的配置：

    ; The number of significant digits displayed in floating point numbers.
    ; http://php.net/precision
    precision = 24

其中 `precision` 的值大于18即可。