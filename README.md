embrr, a fork from embr
========
This is a twitter web client fork from [embr](https://code.google.com/p/embr/), mainly based on `r91` and merged necessary changes until the latest revisions, updated to Twitter REST API `v1.1` and have some improved features.

You can download the zip archive of current version [here](https://github.com/AstroProfundis/embrr/zipball/master) if you don't want to use `git-clone`.

How to Install
--------
The installation of embrr is pretty much the same with embr's, you just need to get the source, rename `config.sample.php` in `/lib` to `config.php`, edit it with your own app information and upload it to your hosting directory.

And here are some tips you may want to know:

 * embrr requires `php-curl` and `php-bcmath`
 * while not necessarily required, `php-mcrypt` is recommended
 * embrr is compatible with PHP from version 5.2 to 5.5, we didn't test it on 5.1 or older versions

----

embrr, 一个修改版的 embr
========

这是一个从 [embr](https://code.google.com/p/embr/) 修改而来的 twitter 网页端，主要基于 `r91` 版本并合并了后续修订版的必要更新，升级到了 Twitter REST API `v1.1` 并有一些功能上的改进。

如果不想使用 `git-clone` 获取代码，可以点[这里](https://github.com/AstroProfundis/embrr/zipball/master)下载当前版本的 zip 压缩包。

安装方法
--------
安装 embrr 的方法和 embr 原版基本相同，只需要下载代码包，重命名 `/lib` 中的 `config.sample.php` 文件为 `config.php`, 编辑此配置文件填入适当的 app 信息，再将代码上传至网站空间即可。

有几点值得注意的地方是：

 * embrr 依赖于 `php-curl` 和 `php-bcmath` 组件
 * 虽然不是必需依赖，但建议安装 `php-mcrypt` 组件
 * embrr 兼容 PHP 5.2 至 5.5 的版本，我们没有测试过 5.1 及之前版本

----
As I'm not an expert of PHP nor twitter API, do expect bugs everywhere.

All helps are welcomed!

请做好 bug 满天飞的心理准备以及欢迎拍砖和协助修改。
