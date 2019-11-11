## 安装
### docker环境安装
```shell script
# 获取docker的composer镜像
docker pull composer

# 安装composer库
docker run -it -v $PWD:/app --privileged=true composer composer install
```
### web服务器环境安装
```shell script
# composer库安装
composer install
```
## 数据库
### 数据迁移
```shell script
#数据迁移库使用Phinx，请参考文档：http://docs.phinx.org/en/latest/install.html

# 创建数据库迁移
./iwan migration:create CreateAccountMigration

# 执行数据库迁移
./iwan migration:migrate

# 回滚数据库迁移
./iwan migrateion:rollback

# 创建数据库填充
./iwan seed:create seedAccount

# 执行数据库填充
./iwan seed:run
```
### 数据模型
```php
<?php
#数据操作使用Medoo，请参考文档：https://medoo.in/api/where

# 模型定义
namespace App\Models;
use Sys\Mvc\Model;
class AccountModel extends Model
{
    public function __construct()
    {
        parent::__construct('account');
    }
}
# 模型使用
$model = new \App\Models\AccountModel();
$data = $model->select('*', ['id[>]' => 12]);
```

## 控制器
### 路由控制器
```php
<?php
namespace App\Controllers;
use App\Models\AccountModel;
use Sys\Mvc\Request;
use Sys\Mvc\Response;
class Account
{
    public function login(Request $request, Response $response)
    {
        // 获取post参数
        $username = $request->request->get('username', '');
        $password = $request->request->get('password', '');

        // 获取get参数
        $type = $request->query->get('type', 'admin');

        // 输出数据到视图或json数据
        $response->assign('type', $type);
        $response->assign(['password' => $username, 'password' => $password]);
    }

    // 依赖自动注入，讲需要使用的对象写在参数列表即可直接使用，指定路由访问可携带路由参数
    public function info(int $id, Response $response, AccountModel $accountModel)
    {
        $response->assign('user', $accountModel->get('*', ['id' => $id]));
    }
}
```
### 指定访问路由
```php
<?php
namespace App;
use App\Controllers\Account;
use Sys\Mvc\Request;
class Router extends \Sys\Router
{
    public function define(Request $request)
    {
        $this->get('/info/{id:[0-9]+}', [Account::class, 'info']);
    }
}
```
### 访问路由
```php
## 获取json数据
POST http://localhost/account/login.json

## 获取html页面（若没有定义页面，返回json数据） 
GET http://localhost/info/1
```