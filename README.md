mingdaoyun-PHP-SDK
===========

针对mingdaoyun的API封装的PHP-SDK包

## Features

- [x] 封装基础API
- [x] 固定数据的CRUD
- [ ] to_sql功能

## Installing

```shell
$ composer require lany/mingdaoyun:"1.2.*"
```

## Usage  

 - Laravel (>=5.5)  `1.2.0开始支持`
 
   发布配置文件
```shell
$ php artisan vendor:publish --provider="Lany\MingDaoYun\Provider\ServiceProvider"
```    
  修改`.env`,添加配置 
  ```php
MINGDAOYUN_APP_KEY=xxx
MINGDAOYUN_SIGN=xxx
MINGDAOYUN_HOST=http://xxx.com
  ```  
 示例代码  
 ```php
use Lany\MingDaoYun\MingDaoYun;
 class IndexController extends Controller 
{
    //依赖注入方式
    public function index(MingDaoYun $mdy)
    {
        $data = $mdy->table('60efbf797b786d8a492bfcee')->get();
        dd($data);
    }
    //实例化服务方式
    public function index1()
    {
         $data = app('mdy')->table('60efbf797b786d8a492bfcee')->get();
         dd($data);
    }
}
   
 ```
  
 - ThinkPHP (>=5.1)   `1.2.0开始支持`  
 > `5.1`请手动 `use Lany\MingDaoYun\Facade\MingDaoYun;` 使用, `6.0以上`可以使用下面的方式
 
   修改`.env`,添加配置 
  ```php
 MINGDAOYUN_APP_KEY=xxx
 MINGDAOYUN_SIGN=xxx
 MINGDAOYUN_HOST=http://xxx.com
   ```     

  示例代码 
 ```php
class Index extends BaseController
{

    public function hello($name = 'ThinkPHP6')
    {
        $data = app('mdy')->table('60efbf797b786d8a492bfcee')->get();
        var_dump($data);
    }
}

```
 - Other
```php
require __DIR__.'/vendor/autoload.php';

use Lany\MingDaoYun\Facade\MingDaoYun;

$appKey = "APPKEY"; //明道云APPKEY
$sign = "SIGN"; //明道云SIGN
$host = "http://xxx.xxx.com"; //私有部署域名
$mdy = MingDaoYun::init($appKey, $sign, $host);
$data = $mdy->table('worksheetId')->get();
````  

## Function

- [init](#init)
- [table](#table)
- [limit](#limit)
- [page](#page)
- [fieldMap](#fieldMap)
- [with](#with)
- [sort](#sort)
- [whereOr](#whereOr)
- [where](#where)
- [whereNull](#whereNull)
- [whereNotNull](#whereNotNull)
- [whereDate](#whereDate)
- [whereNotDate](#whereNotDate)
- [get](#get)
- [find](#find)
- [relations](#relations)
- [view](#view)
- [insert](#insert)
- [create](#create)
- [delete](#delete)
- [update](#update)
- [updateRows](#updateRows)
- [all](#all)
- [count](#count) 
- [同步数据到本地数据库](#同步数据到本地数据库)

### init

> 设置初始化参数

```php
$mdy = MingDaoYun::init('APPKEY', 'SIGN', '部署域名');
```

### table

> 设置工作表

```php
$mdy->table('worksheetId');
```  

### limit

> 要获取的数据行数

```php
$mdy->table('worksheetId')->limit(5);
```

### page

> 设置页码

```php
$mdy->table('worksheetId')->page(5);
```

### fieldMap

> 获取字段对照关系(明道云工作表结构)

```php
$mdy->table('worksheetId')->fieldMap();
```

### with

> 要获取关联记录时,设置rowId,controlId

```php
$mdy->table('worksheetId')->with('rowId', 'controlId');
```

### relations

> 获取关联记录  
>1.1.1修改为默认获取100条关联记录,`relations(true)`获取所有关联记录

```php
$mdy->table('worksheetId')->with('rowId', 'controlId')->relations();
```

### sort

> 设置排序字段,默认为升序

```php
$mdy->table('worksheetId')->sort('field', $bool);
```

### whereOr

> 设置筛选条件,以or的方式拼接下一个条件,使用whereOr时必须写在where()之前

```php
$mdy->table('worksheetId')->whereOr('field', '=', '123');
```

### where

> 设置筛选条件,以and的方式拼接下一个条件,目前支持的运算符有`contains`,`notContain`,`startWith`,`endWith`,`=`,`!=`,`>`,`>=`,`<`,`<=`

```php
$mdy->table('worksheetId')->where('field', '!=', '123');
```

### whereNull

> 字段为空

```php
$mdy->table('worksheetId')->whereNull('field');
```

### whereNotNull

> 字段为不为空

```php
$mdy->table('worksheetId')->whereNotNull('field');
```

### whereDate

> 日期是

```php
$mdy->table('worksheetId')->whereDate('field', '2022-02-22');
```

### whereNotDate

> 日期不是

```php
$mdy->table('worksheetId')->whereNotDate('field', '2022-02-22');
```

### get

> 获取工作表内容  
> `1.1.0`修改为默认最多获取1000条数据,如需获取更多数据请使用`limit`设置,获取所有数据请使用`all`

```php
$mdy->table('worksheetId')->limit(1)->page(1)->get();
```

### find

> 获取单条记录(行记录详情)

```php
$mdy->table('worksheetId')->find('rowId');
```

### view

> 设置视图ID

```php
$mdy->table('worksheetId')->view('view')->get();
```

### insert

> 新增单条记录,按明道云新增controls参数格式传入data即可

```php
$data = [
    ['controlId' => 'controlId', 'value' => 'value'],
    ['controlId' => 'controlId', 'value' => 'value'],
];
$mdy->table('worksheetId')->insert($data);
```

### create

> 批量新增记录,按明道云批量新增rows参数格式传入data即可

```php
$data = [
    [
        ['controlId' => 'controlId', 'value' => 'value'],
        ['controlId' => 'controlId', 'value' => 'value'],
    ],

];
$mdy->table('worksheetId')->create($data);
```

### delete

> 删除行记录,多条记录以`,`分隔rowId

```php
$mdy->table('worksheetId')->delete('rowId');
```

### update

> 更新单行记录

```php
$update = [
    ['controlId' => '60efbf797b786d8a492bfce2', 'value' => '波波波力'],
    ['controlId' => '60efbf797b786d8a492bfce5', 'value' => '波波波力力力力力'],
];
$mdy->table('worksheetId')->update('rowId', $update);
```

### updateRows

> 批量更新行记录,(目前明道只支持每次更新一个字段)

```php
$rowIds = [
    'rowId1', 'rowId2'
];

$update = [
    'controlId' => 'controlId', 'value' => 'value'
];
$mdy->table('worksheetId')->updateRows($rowIds, $update);
```

### all

> 获取所有数据  
> <small>此方法为`1.1.0新增`</small>

```php
$mdy->table('worksheetId')->all();
```

### count

> 统计数据行数  
><small>此方法为`1.1.0新增`</small>

```php
$mdy->table('worksheetId')->count();
```  

### 同步数据到本地数据库

> `1.2.0新增`  
> 
> Laravel和ThinkPHP5.1如果不存在数据表会自动创建(ThinkPHP6需先手动创建数据表),以明道云字段别名创建数据字段,如不存在别名则使用明道控件ID创建    

- 修改对应的`Model`文件
```php
class ProductItem extends Model implements SyncAdapter
{
    //Laravel
    use LaravelAdapter;
    //ThinkPHP
    use ThinkPHPAdapter;
}
```  
- 调用`syncToDB`  

```php
    $data = app('mdy')->table('60efbf797b786d8a492bfcee')->all();
    \App\Models\ProductItem::syncToDB($data);
    //或者传入MingDaoYun实例
     $data = app('mdy')->table('60efbf797b786d8a492bfcee')->limit(1);
    \App\Models\ProductItem::syncToDB($data);
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/lany/mingdaoyun/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/lany/mingdaoyun/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit
tests where applicable._

## License

MIT
