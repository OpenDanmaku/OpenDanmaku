<?php
/**
 * SAE 验证码服务
 *
 * @package sae
 * @version $Id$
 * @author Elmer Zhang
 */
 
 
 
/**
 * SAE 验证码服务
 *
 * <code>
 * <?php
 * session_start();
 * $vcode = new SaeVCode();
 * if ($vcode === false)
 *         var_dump($vcode->errno(), $vcode->errmsg());
 *
 * $_SESSION['vcode'] = $vcode->answer();
 * $question=$vcode->question();
 * echo $question['img_html'];
 *
 * ?>
 * </code>
 *
 * 错误码参考：
 *  - errno: 0         成功
 *  - errno: 3         参数错误
 *  - errno: 500     服务内部错误
 *  - errno: 999     未知错误
 *  - errno: 403     权限不足或超出配额
 * 
 * @package sae
 * @author Elmer Zhang
 *
 */
class SaeVCode extends SaeObject
{
    private $_accesskey = "";    
    private $_secretkey = "";
    private $_errno=SAE_Success;
    private $_errmsg="OK";
    private $vcode;
 
    /**
     * @ignore
     */
    const baseurl = "http://vcode.sae.sina.com.cn/vcode.php";
    /**
     * @ignore
     */
 
    /**
     */
    function __construct($options = array()) {
        $this->_accesskey = SAE_ACCESSKEY;
        $this->_secretkey = SAE_SECRETKEY;
 
        $options = array('type'=>'image');
        $this->vcode = $this->postData($options);
    }
 
    /**
     * 取得验证码问题
     *
     * 图片验证码返回格式: array("img_url"=>"验证码图片URL", "img_html"=>"用于显示验证码图片的HTML代码")
     *
     * @return array 
     * @author Elmer Zhang
     */
    public function question() {
        return $this->vcode['question'];
    }
 
    /**
     * 取得验证码答案
     *
     * @return string 
     * @author Elmer Zhang
     */
    public function answer() {
        return $this->vcode['answer'];
    }
 
    /**
     * 取得错误码
     *
     * @return int 
     * @author Elmer Zhang
     */
    public function errno() {
        return $this->_errno;
    }
 
    /**
     * 取得错误信息
     *
     * @return string 
     * @author Elmer Zhang
     */
    public function errmsg() {
        return $this->_errmsg;
    }
 
    /**
     * 设置key
     *
     * 只有使用其他应用的key时才需要调用
     *
     * @param string $accesskey 
     * @param string $secretkey 
     * @return void 
     * @author Elmer Zhang
     * @ignore
     */
    public function setAuth( $accesskey, $secretkey) {
        $accesskey = trim($accesskey);
        $secretkey = trim($secretkey);
        $this->_accesskey = $accesskey;
        $this->_secretkey = $secretkey;
        return true;
    }
 
    private function postData($post) {
        $url = self::baseurl;
        $s = curl_init();
        curl_setopt($s,CURLOPT_URL,$url);
        curl_setopt($s,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0);
        curl_setopt($s,CURLOPT_TIMEOUT,5);
        curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($s,CURLOPT_HEADER, 1);
        curl_setopt($s,CURLINFO_HEADER_OUT, true);
        curl_setopt($s,CURLOPT_HTTPHEADER, $this->genReqestHeader($post));
        curl_setopt($s,CURLOPT_POST,true);
        curl_setopt($s,CURLOPT_POSTFIELDS,$post); 
        $ret = curl_exec($s);
        // exception handle, if error happens, set errno/errmsg, and return false
        $info = curl_getinfo($s);
        curl_close($s);
        //print_r($info);
        //echo 'abab';
        //print_r($ret);
        //echo 'abab';
        if(empty($info['http_code'])) {
            $this->_errno = SAE_ErrInternal;
            $this->_errmsg = "Verification Code service segment fault";
            return false;
        } else if($info['http_code'] != 200) {
            $this->_errno = SAE_ErrInternal;
            $this->_errmsg = "Verification Code service internal error";
            return false;
        } else {
            if($info['size_download'] == 0) { // get Error header
                $header = substr($ret, 0, $info['header_size']);
                $header = $this->extractCustomHeader("VCodeError", $header);
                if($header == false) { // not found Error header
                    $this->_errno = SAE_ErrUnknown;
                    $this->_errmsg = "unknown error";
                    return false;
                }
                $err = explode(",", $header, 2);
                $this->_errno = $err[0];
                $this->_errmsg = $err[1];
                return false;
            } else {
                $body = substr($ret, -$info['size_download']);
                $body = json_decode(trim($body), true);
                $this->_errno = $body['errno'];
                $this->_errmsg = $body['errmsg'];
                if ($body['errno'] != 0) {
                    return false;
                }
 
                return $body;
            }
        }
        return true;
    }
 
    private function genSignature($content, $secretkey) {
        $sig = base64_encode(hash_hmac('sha256',$content,$secretkey,true));
        return $sig;
    }
 
    private function genReqestHeader($post) {
        $timestamp = date('Y-m-d H:i:s');
        $cont1 = "ACCESSKEY".$this->_accesskey."TIMESTAMP".$timestamp;
        $reqhead = array("TimeStamp: $timestamp","AccessKey: ".$this->_accesskey, "Signature: " . $this->genSignature($cont1, $this->_secretkey));
        //print_r($reqhead);
        return $reqhead;
    }
 
    private function extractCustomHeader($key, $header) {
        $pattern = '/'.$key.'(.*?)'."\n/";
        if (preg_match($pattern, $header, $result)) {
            return $result[1];
        } else {
            return false;
        }
    }
 
}