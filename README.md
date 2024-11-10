## 共享队列
### 简介
- 目的与功能：解决不同项目队列共享的问题
- 解决的问题：现有两个不同的laravel10项目A和B，部署在不同的服务器，使用的数据库相同，当前使用的是A项目调用B项目的接口，实现功能，如果不使用接口的形式互相调用，可以使用同一个队列，解决A项目进行队列投放，B项目进行队列消耗

### 依赖项
#### 软件与库
- 参考项目中composer.json 文件中的依赖
#### 环境要求
- 操作系统：Linux x86_64 CentOS/Ubuntu
- PHP版本：7.4以上版本，建议8.2
- MySQL：5.7.31 或者是 该版本以上
- Redis 6+
- laravel框架 v10.1.0
- 安装PHP 包管理器composer
#### nginx 配置
```
 server
    {
    listen  443  ssl http2;
    server_name test.com;
    index admin.html index.html index.php index.htm default.php default.htm default.html;
    root root/public;//后端接口域名配置路径
    
        ssl_certificate     pem_path;  # pem文件的路径
        ssl_certificate_key  key_path; # key文件的路径
        ssl_session_timeout  5m;    #缓存有效期
        ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:ECDHE:ECDH:AES:HIGH:!NULL:!aNULL:!MD5:!ADH:!RC4;
        ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
        ssl_prefer_server_ciphers on;
    
        location / {
           if (!-e $request_filename) {
            rewrite  ^(.*)$  /index.php?s=/$1  last;
            break;
          }
        }
    
    
    # PHP 支持
        location ~ \.php$ {
            try_files $uri /index.php =404;
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass v3:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }
    }

```
### 工程分类
- Constant 常量文件
- Http
    - Controllers 控制器
    - Middleware 中间件
    - Requests 拦截器
- Service
    - Impl 业务逻辑层（BLL）
    - Providers 注册服务文件目录
    - Repositories
      -Impl 数据访问层（DAL）
- Console 目录：该包含应用所有自定义的 Artisan 命令，这些命令类可以使用 make:command 命令生成。该目录下还有 Console/Kernel 类，在这里可以注册自定义的 Artisan 命令以及定义调度任务
- Exceptions 目录：该目录包含应用的异常处理器，同时还是处理应用抛出的任何异常的好地方。如果你想要自定义异常如何记录或渲染，需要编辑该目录下的 Handler 类。
- Http 目录：该目录包含了控制器、中间件以及表单请求等，几乎所有通过 Web 进入应用的请求处理都在这里进行。
- Providers 目录：该目录包含应用的所有服务提供者。服务提供者在应用启动过程中绑定服务到容器、注册事件以及执行其他任务为即将到来的请求处理做好准备工作。在新安装的 Laravel 应用中，该目录已经包含了一些服务提供者，我们可以按需添加自己的服务提供者到该目录。

### 安装与配置
- 获取代码：git clone ...
- 构建过程：cd project && composer install
- 配置文件：仓库中.env.example 修改为.env即可，如有新增变量，在.env新增即可

### 代码规范检查
- composer install的过程中会执行 post-autoload-dump ,把根目录pre-push和pre-commit拷贝到 ./.git/hooks/ 目录下,git提交的时候会自动触发检查
- 单独执行 pre-commit 脚本也可以针对代码做检查

