
# 路线图



# 运行环境
* PHP7.4
* 扩展需求： （核心扩展是指PHP的核心扩展，在编译安装是会默认编译，编译后请检查，如未编译，按照指引另行安装）
    * openssl  （核心扩展）
    * xml （核心扩展）
    * ctype （核心扩展）
    * curl（核心扩展）
    * date （核心扩展） 
    * dom （核心扩展）
    * fileinfo （核心扩展）
    * filter （核心扩展）
    * ftp （核心扩展）
    * hash （核心扩展）
    * iconv （核心扩展）
    * json （核心扩展）
    * libxml （核心扩展）
    * mbstring （核心扩展，字符串处理扩展）
    * bcmath （核心扩展，高精度计算）
    * pcre （核心扩展，正则处理）
    * Phar （核心扩展，Phar）
    * posix （核心扩展，posix）
    * readline （核心扩展，readline）
    * Reflection （核心扩展，类反射）
    * session （核心扩展，会话处理）
    * SimpleXML （核心扩展，Xml转对象）
    * sockets （核心扩展，sockets通讯）
    * sodium （核心扩展）
    * SPL （核心扩展，PHP标准库）
    * tokenizer （核心扩展）
    * xml （核心扩展，xml 对象解析）
    * xmlreader （核心扩展）
    * xmlwriter （核心扩展）
    * zip （核心扩展）
    * zlib （核心扩展）
    * swoole  （架构核心依赖扩展）
    * pdo （数据库抽象对象，链接数据库）
    * pdo_mysql （PDO-mysql，链接Mysql数据库）
    * zip  （压缩扩展）
    * mysqli （MySQL处理高级版）
    * redis NoSQL数据库（异步队列，缓存）
    * GD （图片处理库，phpexecl 依赖）

* ini 配置

```ini
memory_limit = 2048M;

[swoole]
swoole.use_shortname = off


```
* 运行步骤
    * 确认 `runtime`目录存在
    * 确认 `runtime/execl` 目录已做持久化储存处理
    * 复制`.env.example`到`.env`,并填写内容
    * composer 安装扩展`composer install`
    * composer 启动`composer start`
     












