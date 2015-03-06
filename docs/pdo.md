也可参见http://www.oschina.net/translate/php-pdo-how-to
```php
try {
$dsn = 'mysql:host=localhost;dbname=DB;charset=UTF8';
$username = 'dbuser';
$password = 'dbpass';
$driver_options = array(
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//	PDO::ATTR_CASE			=》PDO::CASE_NATURAL
//	PDO::ATTR_ORACLE_NULLS		=>PDO::NULL_TO_STRING
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	PDO::ATTR_TIMEOUT		=>int sec
	);
$dbhd = new PDO ( string $dsn [, string $username [, string $password 
		[, array $driver_options ]]] )
$rows = $dbhd->exec ( string $statement );//对BLOB不适用，不安全，不返回数据
//（要和string PDO::quote ( string $string [, int $parameter_type = PDO::PARAM_STR ] )配合）
$stmt = $dbhd->prepare ( string $statement [, array $driver_options = array() ] );//SQLITE防止transaction冲突
$bool = $dbhd->beginTransaction ( void );//开启事务，也就是关闭自动提交
$stmt = $dbhd->query ( string $statement );//小数据更快，可选参数int $PDO::FETCH_COLUMN（后接int $colno）
//可选参数int $PDO::FETCH_CLASS（后接string $classname , array $ctorargs）int $PDO::FETCH_INTO（后接object $object）
//PDO::FETCH_ASSOC/PDO::FETCH_NUM?
$stmt = $dbhd->prepare ( string $statement [, array $driver_options = array() ] );//不可以拿表名列名作placeholder，不替换引号内占位符
$rows = $dbh->execute(array());//返回affected_rows(>=0)或false
$str = $dbh->lastInsertId ([ string $name = NULL ] );//序列对象的名称？，事务提交前使用
$bool = $dbh->commit ( void );//正常，提交并结束事务，DDL语句可能会自动提交或出错
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();var_dump($arr = $e->errorInfo);
	echo $dbhd->errorInfo ( void );//不返回（prepare和query生成的）PDOstatement导致的错误
	echo $stmt->errorInfo ( void );//返回（prepare和query生成的）PDOstatement导致的错误
	//errorInfo = array(mixed PDO/PDOStatement::errorCode(), typeUnknown DrvCode，string Message）
	$bool = $dbh->rollBack();//出错，回滚并结束事务
}
bool PDO::inTransaction (void) //PHP5.3.3

closeCursor();
}
```
======================================================
```php
$bool = bindParam ( mixed $parameter , mixed &$variable 
	[, int $data_type = PDO::PARAM_STR [, int $length [, mixed $driver_options ]]] )
$bool = bindValue ( mixed $parameter , mixed $value [, int $data_type = PDO::PARAM_STR ] )
//$parameter可以是":name"(mysql支持)或从1开始的整数(对应匿名占位符"?")，
//SQL中不能把占位符放在引号中，必要时应先对$keyword = "%".$keyword."%";即使如此_和%也很危险
//强制转换$data_type并不提供安全类型转换，locale会改变句号小数点为逗号小数点
//使用引用绑定（foreach ($params as $key => &$val)$sth->bindParam($key, $val);），
//可能导致$variable被改变，bindValue更安全，用bindValue(":$key",$value,$typeArray[$key]);
$bool = execute ([ array $input_parameters ] )//如有参数bind被清空，?可接受数组，:可接受字典，接受NULL不接受空数组
$bool = debugDumpParams ( void )//无效
$cols = columnCount ( void )//未execute或结果为空时返回0
$rows = rowCount ( void )//DIU有效，S取决于数据库驱动，INSERT ... ON DUPLICATE KEY UPDATE为I返回1为U返回2
//U在mysql中返回0，除非new PDO设置array(PDO::MYSQL_ATTR_FOUND_ROWS => true)
//SELECT COUNT(id)和SELECT FOUND_ROWS()（是否结合SQL_CALC_FOUND_ROWS？）可用于S
$arrs =  getColumnMeta ( int $column )//实验性，随时可能变更
//arraynative_type,driver:decl_type,flags,name,table（PHP5.2.3）,len(-1),precision(0),pdo_type
$bool = bindColumn ( mixed $column , mixed &$param [, int $type [, int $maxlen [, mixed $driverdata ]]] )//只有Warning，绑定方式与bindParam相同，PgSQL注意
$bool = setFetchMode ( int $mode )//( int $PDO::FETCH_COLUMN , int $colno )( int $PDO::FETCH_CLASS , string $classname , array $ctorargs )( int $PDO::FETCH_INTO , object $object )
//PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE使constructor先行不至于覆盖，FETCH_CLASS|PDO::FETCH_CLASSTYPE只能用在fetch中？

PDO::FETCH_ASSOC
+PDO::FETCH_NUM
=PDO::FETCH_BOTH(default)
+PDO::FETCH_OBJ
=PDO::FETCH_LAZY
PDO::FETCH_INTO
PDO::FETCH_BOUND
PDO::FETCH_CLASS|PDO::FETCH_CLASSTYPE

PDO::CURSOR_SCROLL
PDO::FETCH_ORI_ABS
PDO::FETCH_ORI_REL
PDO::FETCH_ORI_PRIOR
PDO::FETCH_ORI_NEXT(default,mysql/sqlite仅支持这个，mysql忽略其他)
PDO::FETCH_ORI_LAST

mixed fetch ([ int $fetch_style [, int $cursor_orientation = PDO::FETCH_ORI_NEXT [, int $cursor_offset = 0 ]]] )
//无值并不报错，而是返回false/NULL？另外BLOB需要绑定不能直接fetch
//$count = current($db->query("select count(*) from table")->fetch());
//FETCH_COLUMN？
//fetch必须closeCursor，即使只有一行


array fetchAll ([ int $fetch_style [, mixed $fetch_argument [, array $ctor_args = array() ]]] )
//遇空返回空数组
//非常heavy，建议数据库预先WHERE/ORDER BY
//OUTER LEFT JOIN表现特殊
PDO::ATTR_DEFAULT_FETCH_MODE === PDO::FETCH_BOTH 
PDO::FETCH_COLUMN
PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE
PDO::FETCH_COLUMN|PDO::FETCH_GROUP
PDO::FETCH_GROUP|PDO::FETCH_ASSOC可以把主键变成key
PDO::FETCH_GROUP|PDO::FETCH_UNIQUE
PDO::FETCH_ASSOC

fetch_argument在以下情况下表现不同，默认返回剩余的行。
PDO::FETCH_COLUMN（0开始的索引$sth->fetchAll(PDO::FETCH_COLUMN, 0)）
PDO::FETCH_CLASS（指定类名，ctor_args为构造函数参数，可以在随后执行的__construct中转换属性的类型）
PDO::FETCH_FUNC（指定函数）
PDO::FETCH_OBJ即使未定义类也能自主生成对象
string fetchColumn ([ int $column_number = 0 ] )//在下一行找第0列开始的值，每执行一次换一次行，适合于SELECT COUNT(id)
mixed fetchObject ([ string $class_name = "stdClass" [, array $ctor_args ]] )//配合PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE使用，避免constructor初始化覆盖它
bool nextRowset ( void )//貌似不是给mysql用的？do {$rowset = $stmt->fetchAll(PDO::FETCH_NUM);} while ($stmt->nextRowset());
bool closeCursor ( void )//fetch剩余，使连接释放，对于无返回语句建议unset obj

readonly string $queryString;

```
============http://www.oschina.net/translate/php-pdo-how-to===========
```php
$dsn = 'mysql:dbname=demo;host=localhost;port=3306';
$username = 'root';
$password = 'password_here';
try {
    $db = new PDO($dsn, $username, $passwordarray (
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
)); // also allows an extra parameter of configuration
} catch(PDOException $e) {
    die('Could not connect to the database:<br/>' . $e);
}
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$statement = <<<SQL
    SELECT *
    FROM `foods`
    WHERE `healthy` = 0
SQL;
 
$foods = $db->query($statement);
echo $foods->rowCount();
foreach($foods->FetchAll() as $food) {
    echo $food['name'] . '<br />';
}
$db->quote($input):
$statement = <<<SQL
    DELETE FROM `foods`
    WHERE `healthy` = 1;
SQL;
 
echo $db->exec($statement); // outputs number of deleted rows
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$statement = $db->prepare('SELECT * FROM foods WHERE `name`=? AND `healthy`=?');
$statement2 = $db->prepare('SELECT * FROM foods WHERE `name`=:name AND `healthy`=:healthy)';
$statement->bindValue(1, 'Cake');
$statement->bindValue(2, true);
 
$statement2->bindValue(':name', 'Pie');
$statement2->bindValue(':healthy', false);

$statement->execute();
$statement2->execute();
 
// Get our results:
$cake = $statement->Fetch();
$pie  = $statement2->Fetch();
$statement->execute(array(1 => 'Cake', 2 => true));
$statement2->execute(array(':name' => 'Pie', ':healthy' => false));

$db->beginTransaction();
 
$db->inTransaction(); // true!
$db->commit();

$options = array($option1 => $value1, $option[..]);
$db = new PDO($dsn, $username, $password, $options);
```
============[不完全总结] 初学 PDO 遇见的 45 个陷阱=============
首先大声高呼：PHP是最好的语言！
刚开始学PDO，花了三天看PHP的Manual，然后我就DUANG了……
下面是从php.net上总结的45个坑，求指教，求吐槽，求补充：
========以下总结错误重重，望方家指正========

1. $driver_options手册不完整
2. PDO::exec,PDOStatement却用execute
3. PDO::exec对BLOB不适用
4. SQLITE为防止transaction冲突需要在开启事务前prepare
5. PDO::query接受手册里没有的参数
6. prepare不接受表名/列名做placeholder，不替换引号内的placeholder(好吧这是好事)
7. PDO::exec,PDOStatement返回被影响的列，而rowCount只对Delete和Insert有效，update和select不一定
8. execute接受NULL做参数，但不接受空数组
9. lastInsertId会在commit之后清除，要获取需要趁早
10. commit DDL语句有的会导致事务错误，有的却自动提交事务
11. PDO::errorInfo不返回prepare和query生成的错误，需要PDOStatement::errorInfo
12. PDOStatement::errorInfo是否返回PDO::errorInfo的错误呢？不知道
13. PDO::errorCode是mixed，PDOStatement::errorCode却是string，没有找到解释
14. PDOException手册里没有解释属性和方法的返回值，一点也没有
15. 我到现在都不清楚当一个方法（比如$bool = $dbh->query ( $string );）抛出错误并且返回一个false时，返回值是否会赋给等号的左边，还是会导致等号左边未定义
如果是Error，那false返回值有什么用？如果有Warning，那多不一致
16. errorInfo返回值是个数组，第1个元素（0,1,2三个元素）的类型没有解释
17. Prepare Like语句不可以把%放在语句里面，要手动修改绑定值
18. debugDumpParams据社区反馈无效
19. setAttribute介绍的参数不完整，PDOStatement::setFetchMode也不系统
20. getAttribute直接告诉你不存在通用的属性，只有驱动特定的属性
21. prepare还区分在PHP的伪prepare和在SQL的真prepare
由PDO::ATTR_EMULATE_PREPARES决定
22. PDO::ATTR_CURSOR 只介绍了PDO::CURSOR_SCROLL没介绍PDO::CURSOR_FWDONLY
23. 给execute参数，会erase之前bind的值（如果评论用词准确，那么是erase而不是overwrite）
24. bindValue的$data_type转换据说用起来不怎么安全
25. locale设置会导致绑定的小数点使用逗号
26. bindParam是引用绑定，因此有时会改变绑定的值（这好像是某些数据库的功能，而非故障）
27. columnCount在未execute时返回0，这回你怎么不返回false了？
28. rowCount对update返回0，但是可以设置PDO::MYSQL_ATTR_FOUND_ROWS => true
29. 这个手册上好像也没写
30. 这个写的是Mysql（MySQL限定？），还有某个名为Oracle的参数对所有数据库都有效的（忘了是什么）
31. getColumnMeta是实验性的，手册告诉你我随时会变
32. bindColumn没有Error只有Warning，你设置也没用
33. fetch的时候先赋值后调用constructor，那么你的constructor中如果初始化这些属性会覆盖这些值
需要PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE
不过如果你想在constructor里面处理变量（比如类型转换），那么不能加这个参数
34. FETCH_CLASS|PDO::FETCH_CLASSTYPE把首行当作列名
35. mysql/sqlite仅支持PDO::FETCH_ORI_NEXT，别的不清楚
36. mysql忽略PDO::FETCH_ORI_NEXT之外的参数，sqlite不清楚
37. 当fetch到空值，有人说返回NULL，有人说返回false
38. 总之不会按SQL-92的要求返回errorcode 20
39. fetch返回什么不知道，fetchAll返回空数组，又是一种新情况
40. fetchAll非常heavy，不建议
41. fetchAll的参数如何按位或导致什么结果手册解释不完整
42. OUTER LEFT JOIN有坑，评论说是要多加一个字段才能正常PDO::FETCH_ASSOC，我是没看明白
43. nextRowset意味不明，这个是我没看明白的问题
44. fetch必须closeCursor，即使只有一行
45. 对于不返回数据的语句，需要替换closeCursor，改用unset object

========以上理解错误重重，望方家指正========
