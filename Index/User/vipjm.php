<?php
error_reporting(0);

include("../includes/common.php");
$title='PHP加密';
if($islogins==1){}else exit("<script language='javascript'>window.location.href='../login.php';</script>");

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?=$title?></title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <script src="//lib.baomitu.com/jquery/1.12.4/jquery.min.js"></script>
  <script src="//lib.baomitu.com/layer/2.3/layer.js"></script>
  <script src="//lib.baomitu.com/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../assets/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="../assets/layuiadmin/style/admin.css" media="all">
<div class="layui-fluid" id="LAY-component-timeline">
<div class="layui-row layui-col-space15">
<div class="layui-col-md12">
<div class="layui-card">
<div class="layui-card-header"><?=$title?></div>
<div class="layui-card-body">
<?php
$row=$DB->get_row("SELECT * FROM moyu_daili WHERE user='".$udata['user']."'");
if(isset($_POST['sajm'])){
$price = $conf['wd'];
if($udata['rmb']>=$price){
$extension=explode('.',$_FILES['file']['name']);
if (($length = count($extension)) > 1) {
$jie = strtolower($extension[$length - 1]);
}
if($jie=='php'){
$DB->query("update `moyu_daili` set `rmb`=`rmb`-{$price} where `id`='{$udata['id']}'");
//加密缓存开始
if(($_FILES["file"]["size"]/1024)>$conf['sizekb']){
exit("<script language='javascript'>alert('上传的PHP文件不能超过限制大小！');window.location.href='vipjm.php';</script>");
}
$app = $_SERVER['DOCUMENT_ROOT'].'/Index';
$owner=$udata['id']; //获取当前登陆用户
$file=$_FILES['file']['name']; //获取上传文件名
$time=date("Y-m-d H:i:s"); //获取当前时间
$space=md5($owner.$file.time()); //定义缓存目录名
if (!is_dir($app.'/includes/download/'.$space.'/')) mkdir($app.'/includes/download/'.$space.'/'); //创建缓存目录
$cache=$DB->query("INSERT INTO `moyu_cache` (`owner`, `file`, `space`, `type`, `upload`) VALUES ('{$owner}', '{$file}', '{$space}', 'PHP加密', '{$time}')"); //写入数据表
copy($_FILES['file']['tmp_name'],$app.'/includes/download/'.$space.'/'.$file.".txt"); //将上传文件保存到缓存目录
if($_POST['url']!=""){file_put_contents(ROOT.'/includes/download/'.$space.'/'.$file.".txt",str_replace('<?php','<?php header("Content-type:text/html;charset=utf-8"); if($_SERVER["HTTP_HOST"]!=\''.$_POST['url'].'\'){echo\''.$_POST['content'].'\';exit();}',file_get_contents(ROOT.'/includes/download/'.$space.'/'.$file.".txt"))); }
//加密缓存结束
include_once './function.php';
echo "<div style='display: none;'>";
echo "</div>";
echo <<<code
    <script type="text/javascript">
    var down = layer.confirm('加密成功点击确定下载？', {
      btn: ['确定','取消'],closeBtn:0,icon:1,
      title:'加密完成'
    }, function(){
      sajmts("$space");
      layer.close(down);
    }, function(){
      layer.msg('您也可以后续在加密记录里面下载哦！',{icon:5});
    });
    </script>
code;
}else{
echo "<script type='text/javascript'>layer.msg('请上传PHP文件！',{icon:5});</script>";
}
}else{
echo "<script type='text/javascript'>layer.msg('您的余额不足无法加密！',{icon:5});</script>";
}
}
?>
<div class="panel panel-default">
<div class="panel-body">
<h2 class="page-header" style="display:none">欢迎使用</h2>
<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief" >
<ul class="layui-tab-title">
<li class="layui-this">在线加密</li>
</ul>
<div class="layui-tab-content"></div>
</div>  
<div class="tab-pane active" id="sajm">
<form action="vipjm.php" method="POST"  enctype="multipart/form-data"  class="form-horizontal layui-form" role="form" >						 
<blockquote class="layui-elem-quote">双层加密比较特殊，运行版本支持5.6。<a rel="nofollow" href="tencent://message/?uin=<?php echo $conf["kfqq"] ?>&amp;Menu=yes" >QQ<?php echo $conf["kfqq"] ?> </a>
<br/><a target="_blank"  style="color:red">( 注意PHP加密只支持5.6 ) </a></blockquote>
<div class="layui-form-item">
<label class="layui-form-label">版本选择</label>
<div class="layui-input-block">
<input id="ver_0" name="vers[]" type="checkbox" class="checkbox" title="5.6" value="5.6" checked/>
</div>
</div>
<table class="layui-table layui-form" lay-even="" lay-skin="nob">
<div class="layer-text" style="padding:20px 0 10px;">
<fieldset class="layui-elem-field layui-field-title">
<legend>其他功能</legend>
</fieldset>
</div>
<div class="layui-form-item">
<label class="layui-form-label">版权注释</label>
<div class="layui-input-block">
<input type="text" name="zhushi" class="layui-input" placeholder="如：QQ <?php echo $udata['qq']?>" />
</div>
</div> 	
<div class="layui-form-item">
<label class="layui-form-label">域名限制</label>
<div class="layui-input-block">
<input type="text" name="url" class="layui-input" placeholder="如：http://<?php echo $_SERVER['HTTP_HOST']?>/" />
</div>
</div> 	
<div class="layui-form-item">
<label class="layui-form-label">限制内容</label>
<div class="layui-input-block">
<input type="text" name="content" class="layui-input" placeholder="域名未绑定，非法调用" />
</div>
</div> 	
<div class="layui-form-item">
<label class="layui-form-label">选择文件</label>
<div class="layui-input-block">
<input type="file" name="file" id="file"/>
</div>
</div>
</div>
<div class="layui-form-item">
<label class="layui-form-label">加密费用</label>
<div class="layui-input-block">
<div class="layui-form-mid layui-word-aux" style="color:#F60 !important;font-family: arial"><?php echo $conf['wd'] ?>金币
</div>
</div>
</div>			
<div class="layui-form-item">
<div class="layui-input-block">
<button class="layui-btn" id="wd" name="sajm" lay-submit lay-filter="formDemo">立即提交</button>
</div>
</div>
</form>
<blockquote class="layui-elem-quote layui-quote-nm">
温馨提醒：PHP加密是双层加密有一些文件不支持，遇到不支持就用其他加密。
</blockquote>
</div>
</div>
</div>
</div>
</div>	
<script>
$(function(){ ReadyDashboard.init(); });
setTimeout("document.getElementById('ts').style.display = 'none';", 2000);
function sajmts(id)
{
window.location.href='../down.php?id='+id;
}
</script>
<script src="../assets/layui/layui.js"></script>
<script src="../assets/layuiadmin/layui/layui.js"></script>  
  <script>
  layui.config({
    base: '../assets/layuiadmin/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use(['index', 'form'], function(){
    var $ = layui.$
    ,admin = layui.admin
    ,element = layui.element
    ,form = layui.form;
    
    form.render(null, 'component-form-element');
    element.render('breadcrumb', 'breadcrumb');
    
    form.on('submit(component-form-element)', function(data){
      layer.msg(JSON.stringify(data.field));
      return false;
    });
  });
  </script>
</body>
</html>