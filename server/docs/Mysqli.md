#	MYSQLI用法

mysqli提供了面向对象和面向过程两种方式来与数据库交互，分别看一下这两种方式。

##	一、面向对象
在面向对象的方式中，mysqli被封装成一个类，它的构造方法如下：
```
	__construct ([ string $host [, string $username [, string $passwd 
				[, string $dbname [, int $port [, string $socket ]]]]]] ) 
```
在上述语法中涉及到的参数说明如下：

*	host：连接的服务器地址。
*	username：连接数据库的用户名，默认值是服务器进程所有者的用户名。
*	passwd：连接数据库的密码，默认值为空。
*	dbname：连接的数据库名称。
*	port：TCP端口号。
*	socket：UNIX域socket。

要建立与MySQL的连接可以通过其构造方法实例化mysqli类。
例如下面的代码：
```
	<?php
	$db_host="localhost";			//连接的服务器地址
	$db_user="root";				//连接数据库的用户名
	$db_psw="root";				//连接数据库的密码
	$db_name="sunyang";			//连接的数据库名称
	$mysqli=new mysqli($db_host,$db_user,$db_psw,$db_name);
	?>
```
mysqli还提供了一个连接MySQL的成员方法connect()。
当实例化构造方法为空的mysqli类时，用mysqli对象调用connect()方法同样可连接MySQL。
例如，下面的代码：
```
	<?php
	$db_host="localhost";			//连接的服务器地址
	$db_user="root";				//连接数据库的用户名
	$db_psw="root";				//连接数据库的密码
	$db_name="sunyang";			//连接的数据库名称
	$mysqli=new mysqli();
	$mysqli->connect($db_host,$db_user,$db_psw,$db_name);
	?>
```
关闭与MySQL服务器的连接通过mysqli对象调用close()方法即可。
例如：
```
$mysqli->close();
```

##	二、使用mysqli存取数据

使用mysqli存取数据也包括面向对象和面向过程两种方式，在本节我们只讨论如何使用面向对象的方式来与MySQL交互。
关于mysqli扩展中使用面向过程方式这里就不再详细介绍了，有兴趣的读者可参考官方文档来获取相关的资料。

在mysqli中，执行查询使用query()方法。
该方法的语法格式如下：
```
	mixed query ( string $query [, int $resultmode ] )
```
在上述语法中涉及到的参数说明如下：

*	query：向服务器发送的SQL语句。
*	resultmode：该参数接受两个值：
*	一个是MYSQLI_STORE_RESULT，表示结果作为缓冲集合返回；
*	另一个是MYSQLI_USE_RESULT，表示结果作为非缓冲集合返回。

下面是使用query()方法执行查询的例子：
```
	<?php
	$mysqli=new mysqli("localhost","root","root","sunyang");//实例化mysqli
	$query="select * from employee";
	$result=$mysqli->query($query);
	if ($result) {
		if($result->num_rows>0){						//判断结果集中行的数目是否大于0
			while($row =$result->fetch_array() ){		//循环输出结果集中的记录
				echo ($row[0])."<br>";
				echo ($row[1])."<br>";
				echo ($row[2])."<br>";
				echo ($row[3])."<br>";
				echo "<hr>";
			}
		}
	}else {
		echo "查询失败";
	}
	$result->free();
	$mysqli->close();
	?>
```
在上面代码中：

*	num_rows为结果集的一个属性，返回结果集中行的数目。
*	方法fetch_array()将结果集中的记录放入一个数组中并将其返回。
*	最后使用free()方法将结果集中的内存释放，
*	使用close()方法将数据库连接关闭。

对于删除记录（delete）、保存记录（insert）和修改记录（update）的操作，也是使用query()方法来执行的。
下面是删除记录的例子：
```
	<?php
	$mysqli=new mysqli("localhost","root","root","sunyang");//实例化mysqli
	$query="delete from employee where emp_id=2";
	$result=$mysqli->query($query);
	if ($result){
		echo "删除操作执行成功";
	}else {
		echo "删除操作执行失败";
	}
	$mysqli->close();
	?>
```
保存记录（insert）、修改记录（update）的操作与删除记录（delete）的操作类似，将SQL语句进行相应的修改即可。

##	三、预准备语句

使用预准备语句可提高重复使用语句的性能。
在PHP中：

*	使用prepare()方法来进行预准备语句查询，
*	使用execute()方法来执行预准备语句。

PHP有两种预准备语句：一种是绑定结果，另一种是绑定参数。

###	（一）绑定结果

所谓绑定结果就是把PHP脚本中的自定义变量绑定到结果集中的相应字段上，这些变量就代表着所查询的记录。
绑定结果的示例代码如下：
```
	<?php
	$mysqli=new mysqli("localhost","root","root","sunyang");//实例化mysqli
	$query="select * from employee";
	$result=$mysqli->prepare($query);		//进行预准备语句查询
	$result->execute();					//执行预准备语句
	$result->bind_result($id,$number,$name,$age);//绑定结果
	while ($result->fetch()) {
		echo $id;
		echo $number;
		echo $name;
		echo $age;
	}
	$result->close();						//关闭预准备语句
	$mysqli->close();						//关闭连接
	?>
```
在绑定结果的时候，脚本中的变量要与结果集中的字段一一对应，
绑定完以后，通过fetch()方法将绑定在结果集中的变量一一取出来，最后将预准备语句和数据库连接分别关闭。

###	（二）绑定参数

所谓绑定参数就是把PHP脚本中的自定义变量绑定到SQL语句中的参数（参数使用 “？”代替）上，
绑定参数使用bind_param()方法。
该方法的语法格式如下：
```
bool bind_param ( string $types , mixed &$var1 [, mixed &$... ] )
```
在上述语法中涉及到的参数说明如下。

*	types：绑定的变量的数据类型，它接受的字符种类包括4个，如表所示。
|字符种类|代表的数据类型	|
|-------:|:-------------|
|		I|integer		|
|		D|double		|
|		S|string		|
|		B|blob			|
参数types接受的字符的种类和绑定的变量需要一一对应。

*	var1：绑定的变量，其数量必须要与SQL语句中的参数数量保持一致。

绑定参数的示例代码如下：
```
	<?php
	$mysqli=new mysqli("localhost","root","root","sunyang");//实例化mysqli
	$query="insert into employee (emp_number,emp_name,emp_age) values (?,?,?)";
	$result=$mysqli->prepare($query);
	$result->bind_param("ssi",$number,$name,$age);		//绑定参数
	$number='sy0807';
	$name='employee7';
	$age=20;
	$result->execute();									//执行预准备语句
	$result->close();
	$mysqli->close();
	?>
```
在一个脚本中还可以同时绑定参数和绑定结果。
示例代码如下：
```
	<?php
	$mysqli=new mysqli("localhost","root","root","sunyang");//实例化mysqli
	$query="select * from employee where emp_id < ?";
	$result=$mysqli->prepare($query);
	$result->bind_param("i",$emp_id);					//绑定参数
	$emp_id=4;
	$result->execute();
	$result->bind_result($id,$number,$name,$age);		//绑定结果
	while ($result->fetch()) {
		echo $id."<br>";
		echo $number."<br>";
		echo $name."<br>";
		echo $age."<br>";
	}
	$result->close();
	$mysqli->close();
	?>
```

##	四、多个查询

mysqli扩展提供了能连续执行多个查询的multi_query()方法。
该方法的语法格式如下：
```
	bool mysqli_multi_query ( mysqli $link , string $query )
```
在执行多个查询时，除了最后一个查询语句，每个查询语句之间要用“;”分开。
执行多个查询的示例代码如下：
```
	<?php
	$mysqli=new mysqli("localhost","root","root","sunyang");//实例化mysqli
	$query = "select emp_name from employee ;";
	$query .= "select dep_name from depment ";
	if ($mysqli->multi_query($query)) {					//执行多个查询
		do {
			if ($result = $mysqli->store_result()) {
				while ($row = $result->fetch_row()) {
					echo $row[0];
					echo "<br>";
				}
				$result->close();
			}
			if ($mysqli->more_results()) {
				echo ("-----------------<br>");			//连个查询之间的分割线
			}
		} while ($mysqli->next_result());
	}
	$mysqli->close();//关闭连接
	?>
```
在上述代码中：

*	store_result()方法用于获得一个缓冲结果集；
*	fetch_row()方法的作用类似于fetch_array()方法；
*	more_results()方法用于从一个多查询中检查是否还有更多的查询结果；
*	next_result()方法用于从一个多查询中准备下一个查询结果。


> Written with [StackEdit](https://stackedit.io/).