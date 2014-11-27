#	Sina App Engine(SAE)入门教程(7)- Storage使用
##	Storage 大文件上传说明
	http://sae.sina.com.cn/?m=devcenter&catId=204&content_id=375

##	storage是什么？

	因为sae禁用了代码环境的本地读写，
	但是在网站运行的过程中，必定会出现文件的读写，附件保存问题，
	此时就该存取storage出场了，storage代替了常规的upload目录~

##	怎么在sae使用storage？

	见下面的图示：
		http://skirt-wordpress.stor.sinaapp.com/uploads/2012/10/%E5%BC%80%E5%A7%8Bstorage1.jpg
	输入domain的名字，其他的建议不需要填写，特别是防盗链那块，
	对于不熟悉的人而言，设置可能导致storage内的资源无法访问，
	可以等彻底熟悉了storage的工作原理后再设置不迟:）
	我此处创建一个名字叫lazy的domain用于教学。
		http://skirt-wordpress.stor.sinaapp.com/uploads/2012/10/%E5%BC%80%E5%A7%8Bstorage2.jpg
	到此你就已经创建完了storage，
		http://skirt-wordpress.stor.sinaapp.com/uploads/2012/10/%E5%88%9B%E5%BB%BA%E5%A5%BDstorage.jpg
	你可以在sae的管理面板就是上面图片中点击“管理” 管理storage中的文件，
	进行上传，批量上传，删除等一系列操作。

##	初实storage
	主要介绍从代码的角度使用storage，
###	写入Storage
	举一个具体的实例说明如何将用户上传的图片写到storage中。

	我们可以创建如下的脚本看看现在的storage
		<?php
		$stor = new SaeStorage();
		$domain = 'lazy';//我刚创建的domain的名称
		$filename = 'remote_file.txt';
		$content = 'hello lazy';
		$stor->write( $domain , $filename , $content );
		echo($stor->read( $domain , $filename));//获取文件的内容
		echo '<br>';
		echo($stor->getUrl($domain,$filename));//获取文件的绝对地址
		?>
	运行可以得到以下的结果(http://lazydemo.sinaapp.com/storage/know_storage.php)
		hello lazy
		http://lazydemo-lazy.stor.sinaapp.com/remote_file.txt

	经过了上面的例子相信大部分人都应该弄清楚了storage是什么了，
###	表单上传Storage
	那么下面再给一个实例讲解如果从form表单中直接上传文件写到storage中。
	index.html
		<html>
		<title>Sae Storage demo</title>
		<div>
		<form id="pic_upload" action="recieve.php" method="POST" enctype="multipart/form-data" target="_self" name="pic_upload" action-type="form" node-type="form">
		<input type="file" name="file" class="file"  id="imgfile" value="demo" />
		<input type="submit" value="Upload" class="write_weibo" name="submit" />
		</form>
		</div>
	recieve.php
		<?php
		$stor = new SaeStorage();
		$domain = 'lazy';//我刚创建的domain的名称
		$url = NULL;
		if($_FILES["file"]["tmp_name"] != NULL)
		{
		$fileDataName = $_FILES["file"]["name"];
		//添加图片上传到STORAGE
		$dumpdata = file_get_contents($_FILES["file"]["tmp_name"]);
		$dowLoadUrl = $stor->write($domain,$fileDataName,$dumpdata);//用write就行了
		$url = $stor->getUrl($domain,$fileDataName);//如果上传图片的处理地址
		echo "上传的文件:";
		echo($url);
		}
		?>

##	关于用wrapper(saestor://)操作storage
	sae提供了wrapper来操作storage，这样方便file_get_contents等函数的使用，进一步降低了学习成本。
	下面我就用一个简单的例子讲叙如何使用saestor://来操作storage。见下面的实例：
		<?php
		file_put_contents('saestor://lazy/testwrapper.txt','hello wrapper');//写一个hello wrapper到testwrapper.txt文件中
		$content = file_get_contents('saestor://lazy/testwrapper.txt');
		var_dump($content);
		?>
	访问：http://lazydemo.sinaapp.com/storage/storage_wrapper.php可以看到得到的结果是：
		string(13) "hello wrapper"
#
