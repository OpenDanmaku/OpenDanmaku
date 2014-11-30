<?php
/**
 * This is the PHP SDK API For SAE Storage Service.
 *
 *
 * See COPYING for license information.
 *
 * @author lazypeople
 * @copyright Copyright (c) 2013, Sina App Engine.
 * @package sae
 */
 
include_once dirname(__FILE__) . '/swiftclient.php';
 
class SaeStorage extends SaeObject 
{
    private $accessKey  = '';
    private $secretKey  = '';
    private $errMsg     = 'success';
    private $errNum     = 0;
    private $appName    = '';
    private $restUrl    = '';
    private $filePath   = '';
    private $basedomain = 'stor.sinaapp.com';
    private $cdndomain  = 'sae.sinacdn.com';
    protected $swift_conn;
    
    /**
     * Class constructor
     *
     * @param string $accessKey AccessKey of Appname
     * @param string $secretKey SecretKey of Appname
     */
    function __construct($accessKey = NULL, $secretKey = NULL)
    {
 
        if (empty($accessKey)) {
            $this->accessKey = SAE_ACCESSKEY;
        } else {
            $this->accessKey = $accessKey;
        }
        if (empty($secretKey)) {
            $this->secretKey = SAE_SECRETKEY;
        } else {
            $this->secretKey = $secretKey;
        }
        $this->appName = $_SERVER[ 'HTTP_APPNAME' ];
        $this->swift_conn = new CF_Connection($this->accessKey,$this->secretKey,$this->appName);
    }
 
    /**
     * 跨应用授权访问
     *
     * 当需要访问其他APP的数据时使用
     *
     * @param string $akey，应用的accessKey 
     * @param string $skey，应用的secretKey 
     * @param string _appName, 应用名
     * @return void 
     * @ignore
     */
    public function setAuth( $akey , $skey , $_appName = '' )
    {
        if( $_appName == '') {
            $this->appName = $_SERVER[ 'HTTP_APPNAME' ];
        } else {
            $this->appName = $_appName;
        }
        $this->accessKey = $akey;
        $this->secretKey = $skey;
        $this->swift_conn = new CF_Connection($this->accessKey,$this->secretKey,$this->appName);
    }
 
 
 
    /**
     * 获取错误信息
     * 
     * @desc
     * 
     * @access public
     * @return void 
     * @exception none
     */
    public function errmsg()
    {
        $ret = $this->errMsg." url(".$this->filePath.")";
        $this->restUrl = '';
        $this->errMsg = 'Success';
        return $ret;
    }
 
    /**
     * 获取错误码
     * 
     * @desc
     * 
     * @access public
     * @return void 
     * @exception none
     */
    public function errno()
    {
        $ret = $this->errNum;
        $this->errNum = 0;
        return $ret;
    }
 
 
    /**
     * 获取当前正在操作的应用名
     * 
     * @desc
     * 
     * @access public
     * @return void 
     * @exception none
     * @ignore
     */
    public function getAppname()
    {
        return $this->appName;
    }
 
    /**
     * 获取文件CDN 地址
     *
     * Example:
     * <code>
     * #Get a CDN url
     * $stor = new SaeStorage();
     * $cdn_url = $stor->getCDNUrl("domain","cdn_test.txt");
     * </code>
     *
     * @param string $domain Domain name
     * @param string $filename Filename you save
     * @return string. 
     */
    public function getCDNUrl( $domain, $filename ) 
    {
        $domain = strtolower(trim($domain));
        $filename = $this->formatFilename($filename);
 
        if ( SAE_CDN_ENABLED ) {
            $filePath = "http://".$this->appName.'.'.$this->cdndomain . "/.app-stor/$domain/$filename";
        } else {
            $domain = $this->getDom($domain);
            $filePath = "http://".$domain.'.'.$this->basedomain . "/$filename";
        }
        return $filePath;
    }
 
    /**
     * 获取文件storage访问地址
     *
     * Example:
     * <code>
     * #Get the url of a stored file
     * $stor = new SaeStorage();
     * $file_url = $stor->getUrl("domain","cdn_test.txt");
     * </code>
     *
     * @param string $domain Domain name
     * @param string $filename Filename you save
     * @return string. 
     */
    public function getUrl( $domain, $filename ) 
    {
        $domain = strtolower(trim($domain));
        $filename = $this->formatFilename($filename);
        $domain = $this->getDom($domain);
 
        $this->filePath = "http://".$domain.'.'.$this->basedomain . "/$filename";
        return $this->filePath;
    }
 
    /**
     * Set File url.
     *
     * @param string $domain 
     * @param string $filename The filename you wanna set
     * @return string. 
     * @ignore
     */
    private function setUrl( $domain , $filename )
    {
        $domain = strtolower(trim($domain));
        $filename = $this->formatFilename($filename);
        $this->filePath = "http://".$domain.'.'.$this->basedomain . "/$filename";
    }
 
 
     /**
     * 将数据写入存储
     *
     * Example:
     * <code>
     * # Write some content into a storage file
     * #
     * $storage = new SaeStorage();
     * $domain = 'domain';
     * $destFileName = 'write_test.txt';
     * $content = 'Hello,I am from the method of write'
     * $attr = array('encoding'=>'gzip');
     * $result = $storage->write($domain,$destFileName, $content, -1, $attr, true);
     *
     * </code>
     *
     *
     * @param string $domain Domain name
     * @param string $destFileName The destiny fileName.
     * @param string $content The content of the file
     * @param int    $size The length of file content,the overflower will be truncated and by default there is no limit.
     * @param array  $attr File attributes, set attributes refer to SaeStorage :: setFileAttr () method
     * @param boolean $compress 
     *  #Note: Whether gzip compression.
     *         If true, the file after gzip compression and then stored in Storage,
     *         often associated with $attr=array('encoding'=>'gzip') used in conjunction
     * @return mixed 
     *  #Note: If success,return the url of the file
     *         If faild;return false
     */
    public function write( $domain, $destFileName, $content, $size = -1, $attr = array(), $compress = false )
    {
        $domain = $this->parseDomain(trim($domain));
        $destFileName = $this->formatFilename($destFileName);
 
        if (empty($domain) or empty($destFileName)) {
            $this->errMsg = 'The value of parameter (domain,destFileName,content) can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        if ( $size > -1 )
            $content = substr( $content, 0, $size );
 
        $srcFileName = tempnam(SAE_TMP_PATH, 'SAE_STOR_UPLOAD');
        if ($compress) {
            file_put_contents("compress.zlib://" . $srcFileName, $content);
        } else {
            file_put_contents($srcFileName, $content);
        }
 
        $re = $this->upload($domain, $destFileName, $srcFileName, $attr);
        unlink($srcFileName);
        return $re;
    }
 
    /**
     * 将文件上传入存储
     *
     * Example:
     * <code>
     * #
     * $storage = new SaeStorage();
     * $domain = 'domain';
     * $destFileName = 'write_test.txt';
     * $srcFileName = $_FILE['tmp_name']
     * $attr = array('encoding'=>'gzip');
     * $result = $storage->upload($domain,$destFileName, $srcFileName, -1, $attr, true);
     *
     * </code>
     *
     * The `domain` must be Exist
     *
     * @param string $domain Domain name
     * @param string $destFileName The destiny fileName.
     * @param string $srcFileName The source of the uoload file
     * @param array  $attr File attributes, set attributes refer to SaeStorage :: setFileAttr () method
     * @param boolean $compress 
     *  #Note: Whether gzip compression.
     *         If true, the file after gzip compression and then stored in Storage,
     *         often associated with $attr=array('encoding'=>'gzip') used in conjunction
     * @return mixed 
     *  #Note: If success,return the url of the file
     *         If faild;return false
     */
    public function upload( $domain, $destFileName, $srcFileName, $attr = array(), $compress = false )
    {
        $domain = $this->parseDomain(trim($domain));
        $destFileName = $this->formatFilename($destFileName);
 
        if ( empty($domain) or empty($destFileName) or empty($srcFileName)) {
            $this->errMsg = 'The value of parameter (domain,destFile,srcFileName) can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        if ($compress) {
            $srcFileNew = tempnam( SAE_TMP_PATH, 'SAE_STOR_UPLOAD');
            file_put_contents("compress.zlib://" . $srcFileNew, file_get_contents($srcFileName));
            $srcFileName = $srcFileNew;
        }
        $parseAttr = $this->parseFileAttr($attr);
        $this->setUrl( $this->getDom($domain), $destFileName );
 
        try {
            $container = $this->swift_conn->get_container($domain);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -122;
            return false;
        }
        
        try {
            $object = $container->create_object($destFileName);
            $object->__getMimeType($destFileName);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -123;
            return false;
        }
        
        try {
            $result = $object->load_from_filename($srcFileName);
            if (count($attr)) {
                $this->setFileAttr($domain,$destFileName,$attr);
            }
            return $this->getUrl($domain,$destFileName);
        } catch (Exception $e) {
            $this->errMsg = sprintf('Failed to store to filesystem!(%s)',$e->getMessage());
            $this->errNum = 121;
            return false;
        }
    }
 
    /**
     * 获取指定domain下的文件名列表
     *
     * <code>
     * <?php
     * // 列出 Domain 下所有路径以photo开头的文件
     * $stor = new SaeStorage();
     *
     * $num = 0;
     * while ( $ret = $stor->getList("test", "photo", 100, $num ) ) {
     *      foreach($ret as $file) {
     *          echo "{$file}\n";
     *          $num ++;
     *      }
     * }
     * 
     * echo "\nTOTAL: {$num} files\n";
     * ?>
     * </code>
     *
     * @param string $domain    存储域,在在线管理平台.storage页面可进行管理
     * @param string $prefix    路径前缀
     * @param int $limit        返回条数,最大100条,默认10条
     * @param int $offset       起始条数。limit与offset之和最大为10000，超过此范围无法列出。
     * @return array 执行成功时返回文件列表数组，否则返回false
     */
    public function getList( $domain, $prefix=NULL, $limit=10, $offset = 0 )
    {
        $domain = $this->parseDomain(trim($domain));
        $limit += $offset;
 
        if ( $domain == '' ) {
            $this->errMsg = 'The value of parameter (domain) can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        try {
            $container = $this->swift_conn->get_container($domain);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -122;
            return false;
        }
 
        try {
            $list_detail = $container->get_objects($limit,NULL,$prefix);
            $list_detail_array = $this->std_class_object_to_array($list_detail);
            $list_detail_new = array();
            foreach($list_detail_array as $small) {
                $list_detail_new[] = $small['name']; 
            }
            $total_num = count($list_detail_new);
            $file_list = array();
            if ( $total_num < $offset ) return array();
            for ( $i = $offset; $i < $total_num; $i++) {
                $file_list[] = $list_detail_new[$i];
            }
            return $file_list;
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -110;
            return false;
        }
    }
 
    /**
     * 获取指定Domain、指定目录下的文件列表
     *
     * @param string $domain    存储域
     * @param string $path      目录地址
     * @param int $limit        单次返回数量限制，默认100，最大1000
     * @param int $offset       起始条数
     * @param int $fold         是否折叠目录
     * @return array 执行成功时返回列表，否则返回false
     */
    public function getListByPath( $domain, $path = NULL, $limit = 100, $offset = 0, $fold = true )
    {
        setlocale(LC_ALL, 'en_US.UTF8');
        $limit += $offset;
        $domain = $this->parseDomain(trim($domain));
 
        if ( $domain == '' ) {
            $this->errMsg = 'the value of parameter (domain) can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        try {
            $container = $this->swift_conn->get_container($domain);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -122;
            return false;
        }
 
        if($fold) {
            $delimiter = '/';
        } else {
            $delimiter = NULL;
        }
 
        try {
            if($path != ''){$path = $path."/";}
            $result = $container->get_objects($limit,NULL,$path,NULL,$delimiter,true);
            $file_list = array();
            $total_num = count($result);
            if ( $total_num < $offset ) return array();
            for ( $i = $offset; $i < $total_num; $i++) {
                $file_list[] = $result[$i];
            }
            $result = $file_list;
            if ($fold) {
                $list['dirNum'] = 0;
                $list['fileNum'] = 0;
                $list['dirs'] = array();
                $list['files'] = array();
                foreach ( $result as $item ) {
                    if ( isset( $item['subdir'] ) ) {
                        $list['dirs'][] = array(
                            'name' => basename($item['subdir']),
                            'fullName' => $item['subdir']
                            );
                        $list['dirNum'] ++;
                    } else {
                        $file = array(
                            'Name' => basename($item['name']),
                            'fullName' => $item['name'],
                            'length' => $item['bytes'],
                            'uploadTime' => strtotime($item['last_modified']) + 60 * 60 * 8
                            );
                        if ( isset($item['X-Sws-Object-Meta-Expires-Rule']) ) $file['expires'] = $headers['X-Sws-Object-Meta-Expires-Rule'];
                        $list['files'][] = $file;
                        $list['fileNum'] ++;
                    }
                }
            } else {
                $list = array();
                foreach ( $result as $item ) {
                    $list[] = array(
                        'Name' => basename($item['name']),
                        'fullName' => $item['name'],
                        'length' => $item['bytes'],
                        'uploadTime' => strtotime($item['last_modified'])
                        );
                }
            }
            return $list;
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -113;
            return false;
        }               
    }
 
    /**
     * 获取指定domain下的文件数量
     *
     *
     * @param string $domain    存储域,在在线管理平台.storage页面可进行管理
     * @param string $path      目录(暂没实现)
     * @return array 执行成功时返回文件数，否则返回false
     */
    public function getFilesNum( $domain, $path = NULL )
    {
        $domain = $this->parseDomain(trim($domain));
 
        if ( $domain == '' ) {
            $this->errMsg = 'the value of parameter (domain) can not be empty!';
            $this->errNum = -101;
            return false;
        }
        try {
            $info = $this->swift_conn->get_container($domain);
            $info_array = $this->std_class_object_to_array($info);
            return $info_array['object_count']; 
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -114;
            return false;
        }        
    }
 
    /**
     * 获取文件属性
     *
     * @param string $domain    存储域
     * @param string $filename  文件地址
     * @param array  $attrKey    属性值,如 array("fileName", "length")，当attrKey为空时，以关联数组方式返回该文件的所有属性。
     * @return array 执行成功以数组方式返回文件属性，否则返回false
     */
    public function getAttr( $domain, $filename, $attrKey=array() )
    {
        $domain = $this->parseDomain(trim($domain));
        $filename = $this->formatFilename($filename);
 
        if ( $domain == '' || $filename == '' )
        {
            $this->errMsg = 'the value of parameter (domain,filename) can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        try {
            $container = $this->swift_conn->get_container($domain);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -122;
            return false;
        }
 
        $this->setUrl( $this->getDom($domain), $filename );
 
        try {
            $object = $container->get_object($filename);
            $object = $this->std_class_object_to_array($object);
            if ( !empty($object['last_modified']) ) {
                $file_attr = array(
                    'fileName'=>$object['name'],
                    'datetime'=>strtotime($object['last_modified']),
                    'content_type'=>$object['content_type'],
                    'length'=>$object['content_length'],
                    'md5sum'=>$object['etag'],
                    'expires'=>array_key_exists('Expires', $object['metadata'])?$object['metadata']['Expires']:NULL
                    );
                if (count($attrKey) != 0) {
                    $tmp_array = array();
                    foreach ($attrKey as $small) {
                        $tmp_array[$small] = $file_attr[$small];
                    }
                    $file_attr = $tmp_array;
                }
            } else {
                $file_attr = false;
            }
            return $file_attr;  
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -115;
            return false;
        }              
    }
 
 
    /**
     * 检查文件是否存在
     *
     * @param string $domain    存储域
     * @param string $filename  文件地址
     * @return bool 
     */
    public function fileExists( $domain, $filename )
    {
        $domain = $this->parseDomain(trim($domain));
        $filename = $this->formatFilename($filename);
 
        if ( $domain == '' || $filename == '' )
        {
            $this->errMsg = 'the value of parameter (domain,filename) can not be empty!';
            $this->errNum = -101;
            return false;
        }
        $file_exist = $this->getAttr($domain,$filename);
        return ($file_exist === false)?false:true;
    }
 
    /**
     * 获取文件的内容
     *
     * @param string $domain 
     * @param string $filename 
     * @return string 成功时返回文件内容，否则返回false
     */
    public function read( $domain, $filename )
    {
        $domain = $this->parseDomain(trim($domain));
        $filename = $this->formatFilename($filename);
 
        if ( $domain == '' || $filename == '' )
        {
            $this->errMsg = 'the value of parameter (domain,filename) can not be empty!';
            $this->errNum = -101;
            return false;
        }
        $this->setUrl( $this->getDom($domain), $filename );
 
        try {
            $container = $this->swift_conn->get_container($domain);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -122;
            return false;
        }
 
        try {
            $object = $container->get_object($filename);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -127;
            return false;
        }
        
        try {
            $data = $object->read();
            return $data; 
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -116;
            return false;
        }
               
    }
 
    /**
     * 删除文件
     *
     * @param string $domain 
     * @param string $filename 
     * @return bool 
     */
    public function delete( $domain, $filename )
    {
        $domain = $this->parseDomain(trim($domain));
        //$filename = $this->formatFilename($filename);
 
        if ( $domain == '' || $filename == '' )
        {
            $this->errMsg = 'the value of parameter (domain,filename) can not be empty!';
            $this->errNum = -101;
            return false;
        }
        $this->setUrl( $this->getDom($domain), $filename );
 
        try {
            $container = $this->swift_conn->get_container($domain);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -122;
            return false;
        }
 
        try {
            $result = $container->delete_object($filename);
            return $result;
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -117;
            return false;
        }
                
    }
 
    /**
     * 设置文件属性
     *
     * 目前支持的文件属性
     *  - expires: 浏览器缓存超时，设置规则和domain expires的规则一致
     *  - encoding: 设置通过Web直接访问文件时，Header中的Content-Encoding。
     *  - type: 设置通过Web直接访问文件时，Header中的Content-Type。
     *  - private: 设置文件为私有，则文件不可被下载。
     *
     * <code>
     * <?php
     * $stor = new SaeStorage();
     * 
     * $attr = array('expires' => '1 y');
     * $ret = $stor->setFileAttr("test", "test.txt", $attr);
     * if ($ret === false) {
     *      var_dump($stor->errno(), $stor->errmsg());
     * }
     * ?>
     * </code>
     *
     * @param string $domain 
     * @param string $filename  文件名
     * @param array $attr       文件属性。格式：array('attr0'=>'value0', 'attr1'=>'value1', ......);
     * @return bool 
     */
    public function setFileAttr( $domain, $filename, $attr = array() )
    {
        $domain = $this->parseDomain(trim($domain));
        $filename = $this->formatFilename($filename);
 
        if ( $domain == '' || $filename == '' ) {
            $this->errMsg = 'the value of parameter domain,filename can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        $parseAttr = $this->parseFileAttr($attr);
        if ($parseAttr == false) {
            $this->errMsg = 'the value of parameter attr must be an array, and can not be empty!';
            $this->errNum = -101;
            return false;
        }
        try {
            $container = $this->swift_conn->get_container($domain);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -122;
            return false;
        }
 
        try {
            $object = $container->get_object($filename);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -127;
            return false;
        }
        
        $object->metadata = $attr;
        try {
            $result = $object->sync_metadata();
            return $result;
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -118;
            return false;
        }
        
    }
 
    /**
     * 设置Domain属性
     *
     * 目前支持的Domain属性
     *  - expires: 浏览器缓存超时
     *  说明：
     *  - expires 格式：[modified] TIME_DELTA，例如modified 1y或者1y，modified关键字用于指定expire时间相对于文件的修改时间。默认expire时间是相对于access time。如果TIME_DELTA为负， Cache-Control header会被设置为no-cache。
     *  - TIME_DELTA，TIME_DELTA是一个表示时间的字符串，例如： 1y3M 48d 5s
     *  - 目前支持s/m/h/d/w/M/y
     *  - expires_type 格式:TYPE [modified] TIME_DELTA,TYPE为文件的mimetype，例如text/html, text/plain, image/gif。多条expires-type规则之间以 , 隔开。例如：text/html 48h,image/png modified 1y
     *  - allowReferer: 根据Referer防盗链
     *  - private: 是否私有Domain
     *  - 404Redirect: 404跳转页面，只能是本应用页面，或本应用Storage中文件。例如http://appname.sinaapp.com/404.html或http://appname-domain.stor.sinaapp.com/404.png
     *  - tag: Domain简介。格式：array('tag1', 'tag2')
     * <code>
     * <?php
     * // 缓存过期设置
     * $expires = '1 d
     * ';
     *
     * // 防盗链设置
     * $allowReferer = array();
     * $allowReferer['hosts'][] = '*.elmerzhang.com';       // 允许访问的来源域名，千万不要带 http://。支持通配符*和?
     * $allowReferer['hosts'][] = 'elmer.sinaapp.com';
     * $allowReferer['hosts'][] = '?.elmer.sinaapp.com';
     * $allowReferer['redirect'] = 'http://elmer.sinaapp.com/'; // 盗链时跳转到的地址，仅允许跳转到本APP的页面，且不可使用独立域名。如果不设置或者设置错误，则直接拒绝访问。
     * //$allowReferer = false;  // 如果要关闭一个Domain的防盗链功能，直接将allowReferer设置为false即可
     * 
     * $stor = new SaeStorage();
     * 
     * $attr = array('expires'=>$expires, 'allowReferer'=>$allowReferer);
     * $ret = $stor->setDomainAttr("test", $attr);
     * if ($ret === false) {
     *      var_dump($stor->errno(), $stor->errmsg());
     * }
     *
     * ?>
     * </code>
     *
     * @param string $domain 
     * @param array $attr       Domain属性。格式：array('attr0'=>'value0', 'attr1'=>'value1', ......);
     * @return bool 
     */
    public function setDomainAttr( $domain, $attr = array() )
    {
        $domain = $this->parseDomain(trim($domain));
 
        if ( $domain == '' )
        {
            $this->errMsg = 'The value of parameter domain can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        $parseAttr = $this->parseDomainAttr($attr);
 
        if ($parseAttr == false) {
            $this->errMsg = 'The value of parameter attr must be an array, and can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        try {
            $container = $this->swift_conn->get_container($domain);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -122;
            return false;
        }
 
        $container->metadata = $attr;
        try {
            $result = $container->sync_metadata();
            return($result);
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -119;
            return false;
        }
              
    }
 
    /**
     * 获取domain所占存储的大小
     *
     * @param string $domain 
     * @return int 
     */
    public function getDomainCapacity( $domain )
    {
        $domain = $this->parseDomain(trim($domain));
        if (empty($domain)) {
            $this->errMsg = 'The value of parameter \'$domain\' can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        try {
           $info = $this->swift_conn->get_container($domain); 
        } catch (Exception $e) {
            $this->errMsg = $e->getMessage();
            $this->errNum = -120;
            return false;
        }
 
        $info_array = $this->std_class_object_to_array($info);
        return $info_array['bytes_used'];
    }
 
 
    /**
     * 创建一个domain
     * 
     * @desc
     * 
     * @access private
     * @param $domain='' 
     * @param $attr=array('private'=>false 
     * @return void 
     * @exception none
     * @ignore
     */
    public function createDomain( $domain='', $attr = array('private'=>false) )
    {
        $domain = strtolower($domain);
        if ( strlen( $domain ) > 100 || strlen( $domain ) < 5 ) {
            return array( 'errno'=>-102, 'errmsg'=>'Domain length invalid(5,100)!domain('.$domain.')' );
        }
 
        if ( Empty( $domain ) ) {
            $this->errMsg = 'The value of parameter \'domain\' can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        $domain_explode = explode("-", $domain);
        if (count($domain_explode) != 2) {
            $this->errMsg = 'The value of parameter \'domain\' is not legit!';
            $this->errNum = -101;
            return false;
        } else {
            $domain = $domain_explode[1];
        }
 
        try {
            $this->swift_conn->create_container($domain);
            try {
                if (count($attr)) {
                    $this->setDomainAttr($domain,$attr);
                }
                return true;
            } catch (Exception $e) {
                $this->errMsg = sprintf("Set domain attr failed, %s", $e->getMessage());
                $this->errNum = -107;
                return false;
            }
        } catch (Exception $e) {
            $this->errMsg = sprintf("Create domain failed, %s", $e->getMessage());
            $this->errNum = -104;
            return false;           
        }
    }
 
 
    /**
     * 获取domain列表
     * 
     * @desc
     * 
     * @access public
     * @return void 
     * @exception none
     */
    public function listDomains()
    {
        try {
            $ret = $this->swift_conn->get_containers(0);
            $ret = $this->std_class_object_to_array($ret);
            foreach ($ret as $small) {
                $retnew[] = $this->appName.'-'.$small['name'];
            }
            return $retnew;
        } catch (Exception $e) {
            $this->errNum = -108;
            $this->errMsg = $e->getMessage();
            return false;
        }
    }
 
 
    /**
     * 获取域名属性
     * 
     * @desc
     * 
     * @access public
     * @param $domain='' 
     * @return void 
     * @exception none
     */
    public function getDomainAttr( $domain='' )
    {
        $domain = strtolower($domain);
        if (empty($domain)) {
            $this->errMsg = 'The value of parameter \'domain\' can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        $domain_explode = explode("-", $domain);
        if (count($domain_explode) != 2) {
            $this->errMsg = 'The value of parameter \'domain\' is not legit!';
            $this->errNum = -101;
            return false;
        } else {
            $domain = $domain_explode[1];
        }
 
        try {
            $ret = $this->swift_conn->get_container($domain);
            $info_array = $this->std_class_object_to_array($ret);
            $retmsg = array(
                'expires'=>$info_array['metadata']['X-Sws-Container-Meta-Expires'],
                'expires_type'=>$info_array['metadata']['X-Sws-Container-Meta-Expires-Type'],
                'fileNum'=>$info_array['object_count'],
                'tag'=>json_decode($info_array['metadata']['X-Sws-Container-Meta-Tags'],true),
                'dataSize'=>(int)$info_array['bytes_used'],
                );
            if ( isset( $info_array['read'] ) ) {
                $rrules = explode(',', $info_array['read']);
                if ( ! in_array('.r:*', $rrules) ) {
                    $retmsg['allowReferer'] = array();
                    $retmsg['allowReferer']['hosts'] = array();
                    foreach ( $rrules as $rrule ) {
                        if ( substr($rrule, 0, 3) == '.r:' ) {
                            $retmsg['allowReferer']['hosts'][] = substr($rrule, 3);
                        } elseif ( substr($rrule, 0, 4) == '.rd:' ) {
                            $retmsg['allowReferer']['redirect'] = substr($rrule, 4);
                        }
                    }
                    if ( !$retmsg['allowReferer']['hosts'] ) {
                        $retmsg['private'] = true;
                    }
                } else {
                    $retmsg['private'] = false;
                }
            } else {
                $retmsg['private'] = true;
            }
            return $retmsg;
        } catch (Exception $e) {
            $this->errNum = -109;
            $this->errMsg = $e->getMessage();
            return false;
        }
        
    }
 
 
    /**
     * 删除一个domain
     * 
     * @desc
     * 
     * @access public
     * @param $domain 
     * @param $force=0 
     * @return void 
     * @exception none
     * @ignore
     */
    public function deleteDomain( $domain , $force = 0 )
    {
        $domain = strtolower($domain);
        if ( empty( $domain ) ) {
            $this->errMsg = 'The value of parameter \'domain\' can not be empty!';
            $this->errNum = -101;
            return false;
        }
 
        $domain_explode = explode("-", $domain);
        if (count($domain_explode) != 2) {
            $this->errMsg = 'The value of parameter \'domain\' is not legit!';
            $this->errNum = -101;
            return false;
        } else {
            $domain = $domain_explode[1];
        }
 
        // 循环删除container的文件
        $files = $this->getList($domain);
        while ( is_array($files) && count($files) > 0 ) {
            foreach ($files as $file) {
                $this->delete($domain, $file);
            }
            $files = $this->getList($domain);
        }
 
        try {
            $this->swift_conn->delete_container($domain);
            return true;
        } catch (Exception $e) {
            $this->errNum = -105;
            $this->errMsg = $e->getMessage();
            return false;
        }       
    }
 
    /**
     * @ignore
     */
    public function runFile( $domain,  $filename)
    {
        $this->errMsg = 'this function is discarded';
        $this->errNum = -221;
        return false;
    }
 
    /**
     * domain拼接
     * @param string $domain 
     * @param bool $concat 
     * @return string 
     * @author Elmer Zhang
     * @ignore
     */
    protected function getDom($domain, $concat = true) {
        $domain = strtolower(trim($domain));
 
        if ($concat) {
            if( strpos($domain, '-') === false ) {
                $domain = $this->appName .'-'. $domain;
            }
        } else {
            if ( ( $pos = strpos($domain, '-') ) !== false ) {
                $domain = substr($domain, $pos + 1);
            }
        }
        return $domain;
    }
 
 
    /**
     * Format Filename.
     *
     * @param string $filename 
     * @return string 
     * @ignore
     */
    private function formatFilename($filename) 
    {
        $filename = trim($filename);
        $encodings = array( 'UTF-8', 'GBK', 'BIG5' );
        $charset = mb_detect_encoding( $filename , $encodings);
        if ( $charset !='UTF-8' ) {
            $filename = mb_convert_encoding( $filename, "UTF-8", $charset);
        }
 
        $filename = preg_replace('/\/\.\//', '/', $filename);
        $filename = ltrim($filename, '/');
        $filename = preg_replace('/^\.\//', '', $filename);
        while ( preg_match('/\/\//', $filename) ) {
            $filename = preg_replace('/\/\//', '/', $filename);
        }
        return $filename;
    }
 
    /**
     * @ignore
     */
    protected function parseDomainAttr($attr) 
    {
        $parseAttr = array();
 
        if ( !is_array( $attr ) || empty( $attr ) ) {
            return false;
        }
 
        foreach ( $attr as $k => $a ) {
            switch ( strtolower( $k ) ) {
                case '404redirect':
                    if ( !empty($a) && is_string($a) ) {
                        $parseAttr['404Redirect'] = trim($a);
                    }
                    break;
                case 'private':
                    $parseAttr['private'] = $a ? true : false;
                    break;
                case 'expires':
                    $parseAttr['expires'] = $a;
                    break;
                case 'expires_type':
                    $parseAttr['expires_type'] = $a;
                    break;
                case 'allowreferer':
                    if ( isset($a['hosts']) && is_array($a['hosts']) && !empty($a['hosts']) ) {
                        $parseAttr['allowReferer'] = array();
                        $parseAttr['allowReferer']['hosts'] = $a['hosts'];
 
                        if ( isset($a['redirect']) && is_string($a['redirect']) ) {
                            $parseAttr['allowReferer']['redirect'] = $a['redirect'];
                        }
                    } else {
                        $parseAttr['allowReferer']['host'] = false;
                    }
                    break;
                case 'tag':
                    if (is_array($a) && !empty($a)) {
                        $parseAttr['tag'] = array();
                        foreach ($a as $v) {
                            $v = trim($v);
                            if (is_string($v) && !empty($v)) {
                                $parseAttr['tag'][] = $v;
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
        }
 
        return $parseAttr;
    }
 
    /**
     * @ignore
     */
    protected function parseFileAttr($attr) 
    {
        $parseAttr = array();
 
        if ( !is_array( $attr ) || empty( $attr ) ) {
            return false;
        }
 
        foreach ( $attr as $k => $a ) {
            switch ( strtolower( $k ) ) {
                case 'expires':
                    $parseAttr['expires'] = $a;
                    break;
                case 'encoding':
                    $parseAttr['encoding'] = $a;
                    break;
                case 'type':
                    $parseAttr['type'] = $a;
                    break;
                case 'private':
                    $parseAttr['private'] = intval($a);
                    break;
                default:
                    break;
            }
        }
 
        return $parseAttr;
    }
 
     /**
     * @ignore
     */
    public function parseDomain( $domain ) 
    {
        $domain = strtolower($domain);
        if (strstr($domain,'-')) {
            list($account, $container) = explode('-', $domain);
            return $container;
        } else {
            return $domain;
        }
    }
 
     /**
     * @ignore
     */
    protected function std_class_object_to_array($stdclassobject)
    {
        $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
        $array = array();
        foreach ($_array as $key => $value) {
            $value = (is_array($value) || is_object($value)) ? $this->std_class_object_to_array($value) : $value;
            $array[$key] = $value;
        }
 
        return $array;
    }
}