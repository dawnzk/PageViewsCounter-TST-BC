<?php
class visitorInfo
{
    //获取访客ip
    public function getIp()
    {
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!mb_eregi ("^(10│172.16│192.168).", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
    //获取网站来源
    public function getFromPage(){
        return $_SERVER['HTTP_REFERER'];
    }
}
$visitor =new visitorInfo();
//连接数据库
$conn=mysqli_connect("222.223.152.221","a0409104652","1f6e344c","a0409104652","33017");
if($conn){
    //echo "成功"."<br>";
    //echo $_SERVER['REMOTE_ADDR']."<br>";
}else{
    echo "失败\n";
    die(mysqli_connect_error());
}
//设置数据库编码方式
mysqli_set_charset($conn,"utf8") or die(mysqli_connect_error());
//记录访客的ip地址
$address=$visitor->getIp();
//查询当前访客来访的次数的sql语句
$sql="select times from wp_count where ip='$address'";
//查询结果存到$result变量
$result=mysqli_query($conn,$sql) or die(mysqli_connect_error());
if(!$row=$result->fetch_row()){
    $sql="insert into wp_count(ip, times) values('$address','1')";
}else{
    $times=$row['0']+1;
    $sql="update wp_count set times='$times' where ip ='$address'";
}
$result=mysqli_query($conn,$sql) or die(mysqli_connect_error());
//获取总的访问人数即数据表中所有ip的数量
$sql="select count(ip) from wp_count";
$result=mysqli_query($conn,$sql) or die(mysqli_connect_error());
if($row=$result->fetch_row()){
    $num=$row[0];
}
echo "document.getElementById(\"num\").innerHTML = $num;";

mysqli_close($conn);
?>