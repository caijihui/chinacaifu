### 项目介绍

背景：17年毕业设计
使用`ThinkPHP3.1.2`  编写的一个简易毕设-p2p理财系统。

1. 代码准备
    ```sh
    git clone https://github.com/caijihui/chinacaifu.git  
    ```
2. 数据库准备
    导入数据库
    ```sql
        source  /pwd/chinacaifu.sql
    ```
3. 环境准备（`php56`）
4. nginx 配置虚拟站点
5. 在浏览器打开站点-ok
6. 初步上线（参考地址：[点击这里（历史比较久的经历）](http://blog.sina.com.cn/s/blog_ac47d6b30102xkj9.html)
###  mac 如何切换php 版本

> 此处以 mac 开发为例,安装了php7及以上版本
> 切换可以用php56运行项目
> php 核心在于`fastcig_pass`,这里修改成9009端口，
> 以后nginx 配置项目时可以运行多php版本 

```sh
    cd /etc/
    cp php-fpm.conf.default php-fpm.conf
    ## 修改里面监听的 listen 端口和error_log 地址
    vim php-fpm.conf  (listen = 127.0.0.1:9009)
    ## 查看php-fpp 地址
    which php-fpm 
    ## 启动php-fpm
    php-fpm -D 
```


