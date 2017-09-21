# atphp 框架基本使用配置 
#### github 地址 : https://github.com/caostian/atphp_frame

* 用了composer,用本框架之前,请执行,composer update /install ,不然缺少必要组件
* 本框架可以同时建立多个项目,如home/admin等等
* 本框架使用twig模板引擎,可以直接查看官方文档
* 建立新项目目前不是自动建立相关文件夹,请手动建立相关文件夹

## 注:项目基本目录

|--web
|  |--app
|  |  |--config 项目公共配置,如果具体项目有同名配置,此配置会被覆盖
|  |  |--home 网站主目录文件夹,如果需要建立多个项目如:admin 可以参照这个建立文件夹
|  |  |  |--config 主目录的相关配置
|  |  |  |--controller 控制器文件夹 命名规范:NameController.php
|  |  |  |--model  模型文件夹 命名规范:NameModel.php
|  |  |  |--service  其他服务模型,区别model, model每次实例化都需要连接数据库,尽管有单例,但是并不需要的时候,可以用这个,参照Java编写方式 命名规范:NameService.php
|  |  |  |--view  视图文件夹,每个控制器都有单独的文件夹,如IndexController 建立对应的文件夹是Index,操作模板用的是twig引擎
|  |  |  |--lib  项目库文件夹,一般感觉不会用到的样子
|  |  |--lib 公共库文件夹 ,建议每个单独的库建一个单独的文件夹区别
|  |  |--model 公共模型文件夹
|  |  |--runtime 运行时的缓存目录,包括视图缓存,和系统错误日志
|  |  |--service 公共service文件夹
|  |--atphp --项目核心文件夹
|  |  |--common 一些项目普通方法
|  |  |--core  核心文件夹
|  |--vender 外部组件的文件夹,自动composer update生成
|  |--www  项目入口文件夹
|  |  |--home  网站主目录入口
|  |  |  |--public


* 其他具体的使用,感觉直接看下相关核心代码就可以了,懒得写文档!!!