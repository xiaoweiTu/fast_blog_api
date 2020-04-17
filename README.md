# fast_blog_api


基于[Hyperf](https://github.com/hyperf/hyperf.git)开发的极简博客API

[前端仓库](https://github.com/xiaoweiTu/blog_admin.git)

# 安装
```
1. 拉取项目代码

git clone https://github.com/xiaoweiTu/fast_blog_api.git

2. 进入项目

cd fast_blog_api

3. 安装依赖

composer install

4. 配置文件

cp .env.example .env

5. 执行迁移

php bin/hyperf.php migrate --seed

默认创建一个管理员
13177839316@163.com
密码
123456

6. 修改跨域中间件的指定域名

app/Middleware/CorsMiddleware.php

7. 启动项目

php bin/hyperf.php start

```

# 关于上传文件

使用了七牛云上传,需要在 config/autoload/qiniu.php 中配置

# 关于网站配置

在 config/autoload/site_settings 中配置
