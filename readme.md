[TOC]

定时服务 与 后台服务
-
* 定时任务
`su apache`
`crontab -e`编辑任务列表添加   
`* * * * * php /toodo/work.release/ToodoBackup/artisan schedule:run >> /dev/null 2>&1`
`* * * * * . /etc/profile;php /toodo/work.release/ToodoBackup/artisan schedule:run >> /dev/null 2>&1`
如果php命令出错，php命令用全路径 或引入系统环境变量 


* 后台服务
`su root`.
`vi /etc/supervisord.conf` 编辑 添加后台监控程序
如果php命令出错，php命令用全路径
```
[program:tdsrvfrs-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /toodo/work.release/ToodoBackup/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=apache
numprocs=8
redirect_stderr=true
stdout_logfile=/toodo/work.release/ToodoBackup/storage/logs/worker.log
```
* `supervisorctl reload`
* `supervisorctl start tdsrvfrs-worker`
  - `supervisord -c /etc/supervisord.conf`
  - `supervisorctl -c /etc/supervisord.conf`

  - `echo_supervisord_conf > /etc/supervisord.conf`   生成 .conf文件


* `ls -l /toodo/tt_web/html/ | grep tdsrv | awk '{print $11}'`
* `php artisan queue:restart`


已有项目部署
-
* `php artisan down`
* `git pull`
* `php artisan clear-compiled`
* `php artisan optimize`
* `php artisan up`


新项目部署
-
安装依赖项（可联网）
 `git clone -b master git@code.aliyun.com:toodo/ToodoService.git`
 `composer install --optimize-autoloader --no-dev`
 `composer dump-autoload --optimize`
 `php artisan clear-compiled`
 `php artisan optimiz`
安装依赖项（不可联网）
 请把本地电脑 ToodoService 项目内的 vendor 文件夹，上传到目标服务器 ToodoService 项目内
 `tar xvf vendor.tar -C ./tdbak.unicom/`

生成应用密匙
* `cp .env.example .env`
* `php artisan key:generate`

创建mysql数据库 toodo_service
* `cd database & sh createdb.sh & cd ..`
* `php artisan migrate`
导入初始数据信息
* `cd database & sh importdb.sh & cd ..`
* `php artisan db:seed`


ln -s /toodo/work.release/ToodoService/public tdsrv


项目基于laravel框架
-
* 创建项目工程
  * `composer create-project --prefer-dist laravel/laravel ToodoService`

* 编译前端资源
  * `cnpm install --no-bin-links`
替换 package.json里面 node_modules/cross-env/bin/cross-env.js => node_modules/cross-env/dist/bin/cross-env.js
  * `cnpm run dev`

* 让phpStorm支持laravel智能提示
  * `php artisan ide-helper:generate`

* 服务提供者
  * `php artisan make:provider XXXXServiceProvider`

* 定义中间件
  * `php artisan make:middleware CheckAge`

* 资源控制器
  * `php artisan make:controller XXXXAssetController --resource`
指定资源模型 Photo
  * `php artisan make:controller PhotoController --resource --model=Photo`

* 路由缓存
  * `php artisan route:cache`
  * `php artisan route:clear`

* 数据库版本控制
  * `php artisan make:migration create_users_table --create=users`
  * `php artisan make:migration add_votes_to_users_table --table=users`
添加 修改数据库列的依赖库
  * `composer require doctrine/dbal`

* 用户认证
  * `php artisan make:auth`

* 跨域保护csrf **在html header加上**
  ```html
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <script>
          window.Laravel = {csrfToken: '{{ csrf_token() }}'};
     </script>
  ```
 
* 自定义函数引用
  * `composer dump-autoload`