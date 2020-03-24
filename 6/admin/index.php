<?php

//decode by http://www.yunlu99.com/
error_reporting(0);
header("Content-type: text/html;charset=utf-8");
include '../config.php';
include 'user.php';
include 'version.php';
function checklogin()
{
	global $username, $password;
	if ($_COOKIE['username'] !== $username || $_COOKIE['password'] !== $password) {
		header("location:index.php?sidebar=login");
	} else {
	}
}
function logout()
{
	setcookie("username", null, time() - 3600 * 24 * 365);
	setcookie("password", null, time() - 3600 * 24 * 365);
	header("location:index.php?sidebar=login");
}
function updatesettext($text)
{
	if ($text == "") {
		return "";
	}
	$text = str_replace("\"", '"', $text);
	$text = str_replace("\'", "'", $text);
	$text = str_replace("\\", "", $text);
	return trim($text);
}
function var_request($key, $default)
{
	$value = $default;
	if (isset($_GET[$key])) {
		$value = $_GET[$key];
	} else {
		if (isset($_POST[$key])) {
			$value = $_POST[$key];
		}
	}
	return $value;
}
function __mkdirs($dir, $mode = 0777)
{
	if (!is_dir($dir)) {
		__mkdirs(dirname($dir), $mode);
		return @mkdir($dir, $mode);
	}
	return true;
}
//循环删除目录和文件函数
function delDirAndFile($dirName)
{
	if ($handle = opendir("$dirName")) {
		while (false !== ($item = readdir($handle))) {
			if ($item != "." && $item != "..") {
				if (is_dir("$dirName/$item")) {
					delDirAndFile("$dirName/$item");
				} else {
					if (unlink("$dirName/$item")) {
						echo "成功删除文件： $dirName/$item\n<br />";
					}
				}
			}
		}
		closedir($handle);
		if (rmdir($dirName)) {
			echo "成功删除目录： $dirName\n<br />";
		}
	} else {
		echo "没有缓存文件可清理！";
	}
}
$regex_file = '../regex.txt';
$sidebar = var_request("sidebar", "");
$clearcache = var_request("clearcache", "");
if ($sidebar !== "login") {
	checklogin();
}
if ($sidebar == "logout") {
	logout();
}
$action = var_request("action", "");
$save = var_request("save", "");
$license_site = explode("|", $license_code);
$license_sn = $_SERVER['SERVER_NAME'];
$license_tip = "";
if ($license_sn == 'localhost' || $license_sn == '127.0.0.1') {
	$license_tip = '<font color="blue">（本地测试，高级VIP功能暂时可用，上线后将失效）</font>';
} else {
	$license = $license_site[0] . '301!@#' . $license_site[1] . '301!@#';
	$license_code_md5 = md5($license);
	if ($license_site [1] == $license_site[1] )  {
		$license_tip = '<font color="green">（当前域名已授权，高级VIP功能可用）</font>';
	} else {
		$license_tip = '<font color="red">（当前域名未授权，高级VIP功能失效）</font>';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>单域名PHP镜像克隆管理后台</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="css/jquery.gritter.css" />
<link rel="stylesheet" href="css/unicorn.main.css" />
<link rel="stylesheet" href="css/unicorn.grey.css" class="skin-color" />
<link rel="stylesheet" href="css/unicorn.login.css" />
</head>
<body>
<div id="header">
  <h1><a href="index.php">单域名PHP镜像克隆管理后台</a></h1>	
</div>
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav btn-group">
    <li class="btn btn-inverse"><a title="" href="?sidebar=10"><i class="icon icon-user"></i> <span class="text"><?php 
if ($sidebar !== "login") {
	echo $username;
} else {
	echo "登录";
}
?></span></a></li>
    <li class="btn btn-inverse"><a title="" href="/" target="_blank"><i class="icon icon-share-alt"></i> <span class="text">站点首页</span></a></li>
    <li class="btn btn-inverse"><a title="" href="http://www.phpcaiji.com/plugin.php?id=auction"><i class="icon icon-shopping-cart" target="_blank"></i> <span class="text">兑换授权</span></a></li>
    <li class="btn btn-inverse"><a title="" href="?sidebar=logout"><i class="icon icon-off"></i> <span class="text">退出</span></a></li>
  </ul>
</div>
<div id="sidebar">
  <a href="#" class="visible-phone"><i class="icon icon-th-list"></i>管理菜单</a>
  <ul>
    <li<?php 
if ($sidebar == "") {
	echo ' class="active"';
}
?>><a href="index.php"><i class="icon icon-home"></i> <span>管理首页</span></a></li>
    <li<?php 
if ($sidebar == "1") {
	echo ' class="active"';
}
?>><a href="?sidebar=1"><i class="icon icon-cog"></i> <span>网站配置</span></a></li>
    <li<?php 
if ($sidebar == "2") {
	echo ' class="active"';
}
?>><a href="?sidebar=2"><i class="icon icon-retweet"></i> <span>采集设置</span></a></li>
    <li<?php 
if ($sidebar == "3") {
	echo ' class="active"';
}
?>><a href="?sidebar=3"><i class="icon icon-random"></i> <span>站点适配</span></a></li>
    <li<?php 
if ($sidebar == "4") {
	echo ' class="active"';
}
?>><a href="?sidebar=4"><i class="icon icon-folder-open"></i> <span>缓存设置</span></a></li>
    <li<?php 
if ($sidebar == "5") {
	echo ' class="active"';
}
?>><a href="?sidebar=5"><i class="icon icon-repeat"></i> <span>外链处理</span><span class="label">VIP</span></a></li>
    <li<?php 
if ($sidebar == "6") {
	echo ' class="active"';
}
?>><a href="?sidebar=6"><i class="icon icon-resize-small"></i> <span>Gzip压缩</span></a></li>
    <li<?php 
if ($sidebar == "7") {
	echo ' class="active"';
}
?>><a href="?sidebar=7"><i class="icon icon-eye-close"></i> <span>蜘蛛屏蔽</span></a></li>
    <li<?php 
if ($sidebar == "8") {
	echo ' class="active"';
}
?>><a href="?sidebar=8"><i class="icon icon-pencil"></i> <span>内容替换</span><span class="label">VIP</span></a></li>
    <li<?php 
if ($sidebar == "9") {
	echo ' class="active"';
}
?>><a href="?sidebar=9"><i class="icon icon-check"></i> <span>授权管理</span></a></li>
    <li<?php 
if ($sidebar == "10") {
	echo ' class="active"';
}
?>><a href="?sidebar=10"><i class="icon icon-user"></i> <span>用户管理</span></a></li>
  </ul>
</div>
<?php 
if ($sidebar == "1") {
	?>
<div id="content">
  <div id="content-header">
    <h1>网站配置</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="tip-bottom">网站配置</a>
    <a href="#" class="current">填写网站信息</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon icon-cog"></i></span><h5>网站信息</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" method="post" class="form-horizontal" action="index.php?sidebar=<?php 
	echo $sidebar;
	?>&action=ok&save=ok" >
              <div class="control-group">
                <label class="control-label">我的网址：</label>
                <div class="controls">
                  <input type="text" name="txtsite_url" class="txt" value="<?php 
	echo $site_url;
	?>">
                  <span class="help-block">以http://开头，但不要以/结尾。您当前使用的域名是：<font color=red>http://<?php 
	echo $_SERVER['SERVER_NAME'];
	?></font></span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">站点名称：</label>
                <div class="controls">
                  <input type="text" name="txtsite_title" class="txt" value="<?php 
	echo $site_title;
	?>">
                  <span class="help-block">填写我的站点名称。将替换目标站的全站通用标题。</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">目标网站地址：</label>
                <div class="controls">
                  <input type="text" name="txttarget_url" class="txt" value="<?php 
	echo $target_url;
	?>">
                  <span class="help-block">以http(s)://开头，但不要以/结尾。</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">目标网站名称：</label>
                <div class="controls">
                  <input type="text" name="txttarget_title" class="txt" value="<?php 
	echo $target_title;
	?>">
                  <span class="help-block">填写目标站的全站通用标题。将会被站点名称替换，请匹配正确。</span>
                </div>
              </div>
              <!--取消
              <div class="control-group">
                <label class="control-label">目标网站编码：</label>
                <div class="controls">
                  <label><input type="radio" name="txtcharset" id="txtcharset" value="utf-8"<?php 
	echo $charset == "utf-8" ? " checked" : "";
	?>>UTF-8</label>
                  <label><input type="radio" name="txtcharset" id="txtcharset" value="gbk"<?php 
	echo $charset == "gbk" ? " checked" : "";
	?>>GBK</label>
                  <label><input type="radio" name="txtcharset" id="txtcharset" value="gb2312"<?php 
	echo $charset == "gb2312" ? " checked" : "";
	?>>gb2312</label>
                  <span class="help-block">选择目标站的网页编码，填错可能会乱码。</span>
                </div>
              </div>
              -->
              <div class="control-group">
                <label class="control-label">忽略文件后缀：</label>
                <div class="controls">
                  <input type="text" name="txtnochange" class="txt" value="<?php 
	echo $nochange;
	?>">
                  <span class="help-block">有些后缀的文件处理会出错的，请添加上去，比如压缩文件、视频文件等修改会乱码的文件，多个用|分隔</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">访问跳转：</label>
                <div class="controls">
                  <input type="text" name="txtjump" class="txt" value="<?php 
	echo $jump;
	?>">
                  <span class="help-block">可为主站引流，填写可访问的网址，留空则为不跳转。蜘蛛可正常爬取，只有正常访问才会跳转。<br />（注：<font color=blue>可能影响用户体验，请慎重使用！</font>）</span>
                </div>
              </div>
              <div class="form-actions">
                <a href="javascript:;" class="btn btn-primary" onClick="javascript:form1.submit();"><i class="icon-ok icon-white"></i>保存</a>
                <a href="javascript:;" class="btn btn-danger" onClick="javascript:form1.reset();"><i class="icon-refresh icon-white"></i>重置</a>
              </div>
            </form>
          </div>
        </div>						
      </div>
    </div>
  </div>
</div>
<?php 
} elseif ($sidebar == "2") {
	?>
<div id="content">
  <div id="content-header">
    <h1>采集设置</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="tip-bottom">采集设置</a>
    <a href="#" class="current">选择采集方式</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon icon-retweet"></i></span><h5>采集设置</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" method="post" class="form-horizontal" action="index.php?sidebar=<?php 
	echo $sidebar;
	?>&action=ok&save=ok" >
              <div class="control-group">
                <label class="control-label">采集模式：</label>
                <div class="controls">
                  <label><input type="radio" name="txtcapture_mode" id="txtcapture_mode" value="0"<?php 
	echo $capture_mode == "0" ? " checked" : "";
	?>> 默认模式</label>
                  <label><input type="radio" name="txtcapture_mode" id="txtcapture_mode" value="1"<?php 
	echo $capture_mode == "1" ? " checked" : "";
	?>> 特殊模式</label>
                  <span class="help-block">根据环境的不同，采集出错的可以切换不同的模式来进行调试。</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">破图片防盗链：</label>
                <div class="controls">
                  <label><input type="radio" name="txtanti_theft" id="txtanti_theft" value="1"<?php 
	echo $anti_theft == "1" ? " checked" : "";
	?>>开启 </label>
                  <label><input type="radio" name="txtanti_theft" id="txtanti_theft" value="0"<?php 
	echo $anti_theft == "0" ? " checked" : "";
	?>>关闭</label>
                  <span class="help-block">如目标站没有图片防盗链（即关闭后图片显示正常），关闭可减轻服务器负担。</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">采集方式：</label>
                <div class="controls">
                  <label><input type="radio" name="txtuser_curl" id="txtuser_curl" value="1"<?php 
	echo $user_curl == "1" ? " checked" : "";
	?>> Curl采集</label>
                  <label><input type="radio" name="txtuser_curl" id="txtuser_curl" value="0"<?php 
	echo $user_curl == "0" ? " checked" : "";
	?>> 普通采集</label>
                  <span class="help-block">Curl采集，速度快，可模拟蜘蛛和站点适配</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">模拟蜘蛛：</label>
                <div class="controls">
                  <label><input type="radio" name="txtuser_agent" id="txtuser_agent" value="baidu"<?php 
	echo $user_agent == "baidu" ? " checked" : "";
	?>>百度</label>
                  <label><input type="radio" name="txtuser_agent" id="txtuser_agent" value="google"<?php 
	echo $user_agent == "google" ? " checked" : "";
	?>>谷歌</label>
                  <label><input type="radio" name="txtuser_agent" id="txtuser_agent" value="bing"<?php 
	echo $user_agent == "bing" ? " checked" : "";
	?>>必应</label>
                  <span class="help-block">选择要模拟哪种蜘蛛来采集</span>
                </div>
              </div>
              <div class="form-actions">
                <a href="javascript:;" class="btn btn-primary" onClick="javascript:form1.submit();"><i class="icon-ok icon-white"></i>保存</a>
                <a href="javascript:;" class="btn btn-danger" onClick="javascript:form1.reset();"><i class="icon-refresh icon-white"></i>重置</a>
              </div>
            </form>
          </div>
        </div>						
      </div>
    </div>
  </div>
</div>
<?php 
} elseif ($sidebar == "3") {
	?>
<div id="content">
  <div id="content-header">
    <h1>站点适配</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="tip-bottom">站点适配</a>
    <a href="#" class="current">选择适配类型</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon icon-random"></i></span><h5>选择适配类型</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" method="post" class="form-horizontal" action="index.php?sidebar=<?php 
	echo $sidebar;
	?>&action=ok&save=ok" >
              <div class="control-group">
                <label class="control-label">站点适配：</label>
                <div class="controls">
                  <label><input type="radio" name="txtuser_client" id="txtuser_client" value="pc"<?php 
	echo $user_client == "pc" ? " checked" : "";
	?>>PC站</label>
                  <label><input type="radio" name="txtuser_client" id="txtuser_client" value="mobile"<?php 
	echo $user_client == "mobile" ? " checked" : "";
	?>>移动站</label>
                  <label><input type="radio" name="txtuser_client" id="txtuser_client" value="auto"<?php 
	echo $user_client == "auto" ? " checked" : "";
	?>>自适应</label>
                  <span class="help-block">目标站没有客户端自适应配置的话，将不生效。</span>
                </div>
              </div>
              <div class="form-actions">
                <a href="javascript:;" class="btn btn-primary" onClick="javascript:form1.submit();"><i class="icon-ok icon-white"></i>保存</a>
                <a href="javascript:;" class="btn btn-danger" onClick="javascript:form1.reset();"><i class="icon-refresh icon-white"></i>重置</a>
              </div>
            </form>
          </div>
        </div>						
      </div>
    </div>
  </div>
</div>
<?php 
} elseif ($sidebar == "4") {
	?>
<div id="content">
  <div id="content-header">
    <h1>缓存设置</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="tip-bottom">缓存设置</a>
    <a href="#" class="current">配置缓存</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon icon-folder-open"></i></span><h5>缓存设置</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" method="post" class="form-horizontal" action="index.php?sidebar=<?php 
	echo $sidebar;
	?>&action=ok&save=ok" >
              <div class="control-group">
                <label class="control-label">缓存目录：</label>
                <div class="controls">
                  <input type="text" name="txtcache_path" class="txt" value="<?php 
	echo $cache_path;
	?>">
                  <span class="help-block">会在根目录下自动生成该文件夹，头尾不要带/</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">缓存后缀：</label>
                <div class="controls">
                  <input type="text" name="txtcache_suffix" class="txt" value="<?php 
	echo $cache_suffix;
	?>">
                  <span class="help-block">随意文件后缀，不能用符号，点开头。</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">缓存时间：</label>
                <div class="controls">
                  <input type="text" name="txtcache_time" class="txt" value="<?php 
	echo $cache_time;
	?>">
                  <span class="help-block">单位（秒），填0则不缓存，测试时请不要缓存。</span>
                </div>
              </div>
              <div class="form-actions">
                <a href="javascript:;" class="btn btn-primary" onClick="javascript:form1.submit();"><i class="icon-ok icon-white"></i>保存</a>
                <a href="javascript:;" class="btn btn-danger" onClick="javascript:form1.reset();"><i class="icon-refresh icon-white"></i>重置</a>　　
                <script language="javascript">function delcfm() {
                  if (!confirm("确认要删除全部缓存？")) {
                    window.event.returnValue = false;
                   }
                  }
                </script>
                <a href="index.php?sidebar=4&clearcache=ok" class="btn btn-inverse" onClick="delcfm()"><i class="icon-remove icon-white"></i>清除缓存</a>
              </div>
              <?php 
	if ($clearcache == "ok") {
		?>
              <div class="control-group">
                <div class="controls">
                  <span class="help-block">
                  <?php 
		if ($cache_path == "") {
			$cache_path = "cache";
		}
		echo delDirAndFile('../' . $cache_path);
		?>
                  </span>
                </div>
              </div>
              <?php 
	}
	?>
            </form>
          </div>
        </div>						
      </div>
    </div>
  </div>
</div>
<?php 
} elseif ($sidebar == "5") {
	?>
<div id="content">
  <div id="content-header">
    <h1>外链处理</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="tip-bottom">外链处理</a>
    <a href="#" class="current">外链转换加密</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon icon-repeat"></i></span><h5>外链转换加密<?php 
	echo $license_tip;
	?></h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" method="post" class="form-horizontal" action="index.php?sidebar=<?php 
	echo $sidebar;
	?>&action=ok&save=ok" >
              <div class="control-group">
                <label class="control-label">外链转换：</label>
                <div class="controls">
                  <input type="radio" name="txtchange_link" id="txtchange_link" value="1"<?php 
	echo $change_link == "1" ? " checked" : "";
	?>>开启 
                  <input type="radio" name="txtchange_link" id="txtchange_link" value="0"<?php 
	echo $change_link == "0" ? " checked" : "";
	?>>关闭
                  <span class="help-block">转为【/link.php?url=外链网址】格式</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">文件名：</label>
                <div class="controls">
                  <input type="text" name="txtlink_name" class="txt" value="<?php 
	echo $link_name;
	?>">
                  <span class="help-block"><font color="red">同时需要修改根目录下的link.php文件名</font></span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">是否加密：</label>
                <div class="controls">
                  <input type="radio" name="txturl_encode" id="txturl_encode" value="1"<?php 
	echo $url_encode == "1" ? " checked" : "";
	?>>开启 
                  <input type="radio" name="txturl_encode" id="txturl_encode" value="0"<?php 
	echo $url_encode == "0" ? " checked" : "";
	?>>关闭
                  <span class="help-block">转为【/link.php?url=外链网址加密】格式</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">忽略的域名：</label>
                <div class="controls">
                  <input type="text" name="txtnochange_url" class="txt" value="<?php 
	echo $nochange_url;
	?>">
                  <span class="help-block">比如外部CSS、js、友链等，多个用|分隔</span>
                </div>
              </div>
              <div class="form-actions">
                <a href="javascript:;" class="btn btn-primary" onClick="javascript:form1.submit();"><i class="icon-ok icon-white"></i>保存</a>
                <a href="javascript:;" class="btn btn-danger" onClick="javascript:form1.reset();"><i class="icon-refresh icon-white"></i>重置</a>
              </div>
            </form>
          </div>
        </div>						
      </div>
    </div>
  </div>
</div>
<?php 
} elseif ($sidebar == "6") {
	?>
<div id="content">
  <div id="content-header">
    <h1>Gzip压缩</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="tip-bottom">Gzip压缩</a>
    <a href="#" class="current">配置Gzip</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon icon-resize-small"></i></span><h5>Gzip压缩</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" method="post" class="form-horizontal" action="index.php?sidebar=<?php 
	echo $sidebar;
	?>&action=ok&save=ok" >
              <div class="control-group">
                <label class="control-label">Gzip压缩：</label>
                <div class="controls">
                  <input type="radio" name="txtgzip" id="txtgzip" value="1"<?php 
	echo $gzip == "1" ? " checked" : "";
	?>>开启 
                  <input type="radio" name="txtgzip" id="txtgzip" value="0"<?php 
	echo $gzip == "0" ? " checked" : "";
	?>>关闭
                  <span class="help-block">可加速访问速度，环境如已配置Gzip,请关闭它。</span>
                </div>
              </div>
              <div class="form-actions">
                <a href="javascript:;" class="btn btn-primary" onClick="javascript:form1.submit();"><i class="icon-ok icon-white"></i>保存</a>
                <a href="javascript:;" class="btn btn-danger" onClick="javascript:form1.reset();"><i class="icon-refresh icon-white"></i>重置</a>
              </div>
            </form>
          </div>
        </div>						
      </div>
    </div>
  </div>
</div>
<?php 
} elseif ($sidebar == "7") {
	?>
<div id="content">
  <div id="content-header">
    <h1>蜘蛛屏蔽</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="tip-bottom">蜘蛛屏蔽</a>
    <a href="#" class="current">配置屏蔽</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon icon-eye-close"></i></span><h5>蜘蛛屏蔽</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" method="post" class="form-horizontal" action="index.php?sidebar=<?php 
	echo $sidebar;
	?>&action=ok&save=ok" >
              <div class="control-group">
                <label class="control-label">蜘蛛屏蔽：</label>
                <div class="controls">
                  <input type="radio" name="txtshield_spider" id="txtshield_spider" value="1"<?php 
	echo $gzip == "1" ? " checked" : "";
	?>>开启 
                  <input type="radio" name="txtshield_spider" id="txtshield_spider" value="0"<?php 
	echo $gzip == "0" ? " checked" : "";
	?>>关闭
                  <span class="help-block">比如屏蔽一些流氓蜘蛛，不会带来流量，还会影响性能，建议屏蔽。</span>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">蜘蛛名：</label>
                <div class="controls">
                  <input type="text" name="txtspider_name" class="txt" value="<?php 
	echo $spider_name;
	?>">
                  <span class="help-block">只需要填写基本特征，全部小写，多个用|隔开</span>
                </div>
              </div>
              <div class="form-actions">
                <a href="javascript:;" class="btn btn-primary" onClick="javascript:form1.submit();"><i class="icon-ok icon-white"></i>保存</a>
                <a href="javascript:;" class="btn btn-danger" onClick="javascript:form1.reset();"><i class="icon-refresh icon-white"></i>重置</a>
              </div>
            </form>
          </div>
        </div>						
      </div>
    </div>
  </div>
</div>
<?php 
} elseif ($sidebar == "8") {
	?>
<div id="content">
  <div id="content-header">
    <h1>内容替换</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="tip-bottom">内容替换</a>
    <a href="#" class="current">填写替换规则</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon icon-pencil"></i></span><h5>填写替换规则<?php 
	echo $license_tip;
	?></h5>
          </div>
          <div class="widget-content">
            <div class="alert alert-info alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">替换规则如下：</h4>
              <ol>
                <li>格式：原内容<font color="red">[to]</font>新内容，多个用<font color="red">[and]</font>隔开。但不要以<font color="red">[and]</font>开头或结尾。</li>
                <li>原内容可以用通配符<font color="red">(.*)</font>代表两字符串中间的所有字符。注意元素间的闭合。</li>
                <li>也可以用来设置近义词替换或伪原创，如：寂寞[to]孤独[and]勤奋[to]勤劳</li>
                <li>新内容可以为空，代表删除的意思。<font color="red">[and]</font>之前可以用回车换行。</li>
                <li>原则上如果多个元素一样，先替换里层，之后再替换外层。</li>
              </ol>
              <p><a href="http://www.phpcaiji.com/thread-280-1-1.html" class="btn btn-success" target="_blank"><i class="icon-hand-right icon-white"></i> 点此到论坛查看替换教程</a></p>
            </div>
            <form name="form1" method="post" class="form-horizontal" action="index.php?sidebar=<?php 
	echo $sidebar;
	?>&action=ok&save=ok" >
              <p align="center"><textarea name="txtregex" id="txtregex" cols="500" rows="30" style="width:90%;height: 540px;"><?php 
	readfile($regex_file);
	?></textarea></p>
              <div class="form-actions">
                <a href="javascript:;" class="btn btn-primary" onClick="javascript:form1.submit();"><i class="icon-ok icon-white"></i>保存</a>
                <a href="javascript:;" class="btn btn-danger" onClick="javascript:form1.reset();"><i class="icon-refresh icon-white"></i>重置</a>
                <i class="icon-info-sign"></i><font color="#000">  修改更改之前请注意备份。</font>
              </div>
            </form>
          </div>
        </div>						
      </div>
    </div>
  </div>
</div>
<?php 
} elseif ($sidebar == "9") {
	?>
<div id="content">
  <div id="content-header">
    <h1>授权管理</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="tip-bottom">授权管理</a>
    <a href="#" class="current">填写授权码</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon icon-check"></i></span><h5>填写授权码<?php 
	echo $license_tip;
	?></h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" method="post" class="form-horizontal" action="index.php?sidebar=<?php 
	echo $sidebar;
	?>&action=ok&save=ok" >
              <div class="control-group">
                <label class="control-label">授权码：</label>
                <div class="controls">
                  <input type="text" name="txtlicense_code" class="txt" value="<?php 
	echo $license_code;
	?>">
                  <span class="help-block">授权后，可使用高级VIP功能。（<a href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><font color="#0000ff">点击兑换授权码</font></a>）</span>
                </div>
              </div>
              <div class="form-actions">
                <a href="javascript:;" class="btn btn-primary" onClick="javascript:form1.submit();"><i class="icon-ok icon-white"></i>保存</a>
                <a href="javascript:;" class="btn btn-danger" onClick="javascript:form1.reset();"><i class="icon-refresh icon-white"></i>重置</a>
              </div>
            </form>
          </div>
        </div>						
      </div>
    </div>
  </div>
</div>
<?php 
} elseif ($sidebar == "10") {
	?>
<div id="content">
  <div id="content-header">
    <h1>用户管理</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="tip-bottom">用户管理</a>
    <a href="#" class="current">帐户管理</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon icon-user"></i></span><h5>帐户管理</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" method="post" class="form-horizontal" action="index.php?sidebar=<?php 
	echo $sidebar;
	?>&action=ok&save=ok" >
              <div class="control-group">
                <label class="control-label">用户：</label>
                <div class="controls">
                  <input type="text" name="txtusername" class="txt" value="<?php 
	echo $username;
	?>">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">密码：</label>
                <div class="controls">
                  <input type="text" name="txtpassword" class="txt" value="<?php 
	echo $password;
	?>">
                  
                </div>
              </div>
              <div class="form-actions">
                <a href="javascript:;" class="btn btn-primary" onClick="javascript:form1.submit();"><i class="icon-ok icon-white"></i>保存</a>
                <a href="javascript:;" class="btn btn-danger" onClick="javascript:form1.reset();"><i class="icon-refresh icon-white"></i>重置</a>
              </div>
            </form>
          </div>
        </div>						
      </div>
    </div>
  </div>
</div>
<?php 
} elseif ($sidebar == "login") {
	?>
<div id="content">
  <div id="content-header">
    <h1>管理登录</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="current">管理登录</a>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
<?php 
	if (var_request("username", "") == $username && var_request("password", "") == $password) {
		setcookie("username", $username, time() + 3600 * 24 * 1);
		setcookie("password", $password, time() + 3600 * 24 * 1);
		header("location:index.php");
	} elseif (var_request("username", "") == '' || var_request("password", "") == '') {
		$err_msg = "PHP采集网管理后台登录";
	} elseif ($username !== var_request("username", "") || $password !== var_request("password", "")) {
		$err_msg = "<font color=red>用户名或密码错误！</font>";
	} else {
		$err_msg = 'PHP采集网管理后台登录';
	}
	?>
      <div id="loginbox">
        <form id="loginform" class="form-vertical" action="?sidebar=login" method="post"/>
          <p><?php 
	echo $err_msg;
	?></p>
          <div class="control-group">
            <div class="controls">
              <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input type="text" placeholder="帐号" name="username" /></div>
            </div>
          </div>
          <div class="control-group">
            <div class="controls">
              <div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span><input type="password" placeholder="密码" name="password" /></div>
            </div>
          </div>
          <div class="form-actions">
            <span class="pull-left"><a href="javascript:;" class="flip-link" onClick="javascript:alert('帐号密码查看方法：\n\n　　打开admin文件夹下的user.php查看当前帐号和密码。')">忘记帐号密码?</a></span>
            <span class="pull-right"><input type="submit" class="btn btn-inverse" value="登录" /></span>
          </div>
        </form>
      </div>					
    </div>
  </div>
</div>

<?php 
} else {
	?>
<div id="content">
  <div id="content-header">
    <h1>用户管理</h1>
    <div class="btn-group">
      <a class="btn btn-large tip-bottom" title="联系我们" href="http://wpa.qq.com/msgrd?V=3&uin=2908686223&Site=PHP%E9%87%87%E9%9B%86%E7%BD%91&menu=yes&from=admin" target="_blank"><i class="icon-comment"></i></a>
      <a class="btn btn-large tip-bottom" title="最新下载" href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank"><i class="icon-download"></i></a>
      <a class="btn btn-large tip-bottom" title="使用帮助" href="http://www.phpcaiji.com/forum-55-1.html" target="_blank"><i class="icon-question-sign"></i>
      <a class="btn btn-large tip-bottom" title="购买授权" href="http://www.phpcaiji.com/plugin.php?id=auction" target="_blank"><i class="icon-shopping-cart"></i><span class="label label-important">VIP</span></a>
    </div>
  </div>
  <div id="breadcrumb">
    <a href="index.php" class="tip-bottom"><i class="icon-home"></i> 管理首页</a>
    <a href="#" class="current">系统信息</a>

  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"><i class="icon-home"></i></span><h5>系统信息</h5>
          </div>
          <div class="widget-content">
<?php 
	$alert = explode("/", $_SERVER["SCRIPT_NAME"]);
	if ($alert[1] == "admin") {
		?>
            <div class="alert alert-error alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">温馨提示!</h4>系统检测到默认管理目录admin未改名，建议上线后更改！
            </div>
<?php 
	}
	if ($site_url == "" || $site_title == "" || $target_url == "" || $target_title == "") {
		?>
            <div class="alert alert-info alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">温馨提示!</h4><a href="index.php?sidebar=1">网站配置还有未设置项，点击前往设置。</a>
            </div>
<?php 
	}
	if ($capture_mode == "" || $anti_theft == "" || $user_curl == "" || $user_agent == "") {
		?>
            <div class="alert alert-info alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">温馨提示!</h4><a href="index.php?sidebar=2">采集设置还有未设置项，点击前往设置。</a>
            </div>
<?php 
	}
	if ($user_client == "") {
		?>
            <div class="alert alert-info alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">温馨提示!</h4><a href="index.php?sidebar=3">站点适配还有未设置项，点击前往设置。</a>
            </div>
<?php 
	}
	if ($cache_path == "" || $cache_suffix == "" || $cache_time == "") {
		?>
            <div class="alert alert-info alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">温馨提示!</h4><a href="index.php?sidebar=4">缓存设置还有未设置项，点击前往设置。</a>
            </div>
<?php 
	}
	if ($change_link == "" || $link_name == "" || $url_encode == "" || $nochange_url == "") {
		?>
            <div class="alert alert-info alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">温馨提示!</h4><a href="index.php?sidebar=5">外链处理还有未设置项，点击前往设置。</a>
            </div>
<?php 
	}
	if ($gzip == "") {
		?>
            <div class="alert alert-info alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">温馨提示!</h4><a href="index.php?sidebar=6">Gzip压缩还有未设置项，点击前往设置。</a>
            </div>
<?php 
	}
	if ($shield_spider == "" || $spider_name == "") {
		?>
            <div class="alert alert-info alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">温馨提示!</h4><a href="index.php?sidebar=7">蜘蛛屏蔽还有未设置项，点击前往设置。</a>
            </div>
<?php 
	}
	if ($license_code == "") {
		?>
            <div class="alert alert-info alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">温馨提示!</h4><a href="index.php?sidebar=9">授权管理还有未设置项，点击前往设置。</a>
            </div>
<?php 
	}
	function show($varName)
	{
		switch ($result = get_cfg_var($varName)) {
			case 0:
				return '<font color="red">×</font>';
				break;
			case 1:
				return '<font color="green">√</font>';
				break;
			default:
				return $result;
				break;
		}
	}
	function isfun($funName = '')
	{
		if (!$funName || trim($funName) == '' || preg_match('~[^a-z0-9\_]+~i', $funName, $tmp)) {
			return '错误';
		}
		return false !== function_exists($funName) ? '<font color="green">√</font>' : '<font color="red">×</font>';
	}
	function isfun1($funName = '')
	{
		if (!$funName || trim($funName) == '' || preg_match('~[^a-z0-9\_]+~i', $funName, $tmp)) {
			return '错误';
		}
		return false !== function_exists($funName) ? '√' : '×';
	}
	?>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>描述</th>
                  <th>参数</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">当前域名</td>
                  <td><?php 
	echo $_SERVER['SERVER_NAME'];
	echo $license_tip;
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">IP：端口</td>
                  <td><?php 
	if ('/' == DIRECTORY_SEPARATOR) {
		echo $_SERVER['SERVER_ADDR'];
	} else {
		echo @gethostbyname($_SERVER['SERVER_NAME']);
	}
	?>:<?php 
	echo $_SERVER['SERVER_PORT'];
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">当前版本</td>
                  <td><?php 
	echo $version;
	?> <a href="http://www.phpcaiji.com/thread-81-1-1.html" target="_blank">查看最新版本</a></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">操作系统</td>
                  <td><?php 
	$os = explode(" ", php_uname());
	echo $os[0];
	?> &nbsp;内核版本：<?php 
	if ('/' == DIRECTORY_SEPARATOR) {
		echo $os[2];
	} else {
		echo $os[1];
	}
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">解译引擎</td>
                  <td><?php 
	echo $_SERVER['SERVER_SOFTWARE'];
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">绝对路径</td>
                  <td><?php 
	echo $_SERVER['DOCUMENT_ROOT'] ? str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) : str_replace('\\', '/', dirname(__FILE__));
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">超时时间</td>
                  <td><?php 
	echo show("max_execution_time");
	?>秒</td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">Curl支持</td>
                  <td><?php 
	echo isfun("curl_init");
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">Cookie 支持</td>
                  <td><?php 
	echo isset($_COOKIE) ? '<font color="green">√</font>' : '<font color="red">×</font>';
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">正则函数库</td>
                  <td><?php 
	echo isfun("preg_match");
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">Iconv编码转换</td>
                  <td><?php 
	echo isfun("iconv");
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">mbstring扩展</td>
                  <td><?php 
	echo isfun("mb_eregi");
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">Zend版本</td>
                  <td><?php 
	$zend_version = zend_version();
	if (empty($zend_version)) {
		echo '<font color=red>×</font>';
	} else {
		echo $zend_version;
	}
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;"><?php 
	$PHP_VERSION = PHP_VERSION;
	$PHP_VERSION = substr($PHP_VERSION, 2, 1);
	if ($PHP_VERSION > 2) {
		echo "ZendGuardLoader[启用]";
	} else {
		echo "Zend Optimizer";
	}
	?></td>
                  <td><?php 
	if ($PHP_VERSION > 2) {
		echo get_cfg_var("zend_loader.enable") ? '<font color=green>√</font>' : '<font color=red>×</font>';
	} else {
		if (function_exists('zend_optimizer_version')) {
			echo zend_optimizer_version();
		} else {
			echo get_cfg_var("zend_optimizer.optimization_level") || get_cfg_var("zend_extension_manager.optimizer_ts") || get_cfg_var("zend.ze1_compatibility_mode") || get_cfg_var("zend_extension_ts") ? '<font color=green>√</font>' : '<font color=red>×</font>';
		}
	}
	?></td>
                </tr>
                <tr>
                  <td style="text-align: right;width:30%;font-weight: bold;">memory_limit</td>
                  <td>最大内存 <?php 
	echo show("memory_limit");
	?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php 
}
?>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.ui.custom.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.gritter.min.js"></script>
<script src="js/jquery.peity.min.js"></script>
<script src="js/unicorn.js"></script>
<?php 
if ($action == "" && $save == "ok") {
	echo '
<script src="js/unicorn.interface.js"></script>
';
}
?>
</body>
</html>
<?php 
if ($sidebar !== "8" && $sidebar !== "10" && $action == "ok" && $save == "ok") {
	$ctxtsite_url = updatesettext(var_request("txtsite_url", $site_url));
	$var_site_url = trim($ctxtsite_url);
	$len_site_url = strlen($var_site_url) - 1;
	$site_url_last = $var_site_url[$len_site_url];
	//取得最后一个字符
	if ($site_url_last == '/') {
		//如果末尾是/
		$ctxtsite_url = substr($ctxtsite_url, 0, -1);
		//删除掉/
	}
	$ctxtsite_title = updatesettext(var_request("txtsite_title", $site_title));
	$ctxttarget_url = updatesettext(var_request("txttarget_url", $target_url));
	$var_target_url = trim($ctxttarget_url);
	$len_target_url = strlen($var_target_url) - 1;
	$target_url_last = $var_target_url[$len_target_url];
	//取得最后一个字符
	if ($target_url_last == '/') {
		//如果末尾是/
		$ctxttarget_url = substr($ctxttarget_url, 0, -1);
		//删除掉/
	}
	$ctxttarget_title = updatesettext(var_request("txttarget_title", $target_title));
	$ctxtcapture_mode = updatesettext(var_request("txtcapture_mode", $capture_mode));
	$ctxtanti_theft = updatesettext(var_request("txtanti_theft", $anti_theft));
	$ctxtuser_curl = updatesettext(var_request("txtuser_curl", $user_curl));
	$ctxtuser_agent = updatesettext(var_request("txtuser_agent", $user_agent));
	$ctxtuser_client = updatesettext(var_request("txtuser_client", $user_client));
	$ctxtcharset = updatesettext(var_request("txtcharset", $charset));
	$ctxtnochange = updatesettext(var_request("txtnochange", $nochange));
	$ctxtjump = updatesettext(var_request("txtjump", $jump));
	$ctxtchange_link = updatesettext(var_request("txtchange_link", $change_link));
	$ctxtlink_name = updatesettext(var_request("txtlink_name", $link_name));
	$ctxturl_encode = updatesettext(var_request("txturl_encode", $url_encode));
	$ctxtnochange_url = updatesettext(var_request("txtnochange_url", $nochange_url));
	$ctxtcache_path = updatesettext(var_request("txtcache_path", $cache_path));
	$ctxtcache_suffix = updatesettext(var_request("txtcache_suffix", $cache_suffix));
	$ctxtcache_time = updatesettext(var_request("txtcache_time", $cache_time));
	$ctxtgzip = updatesettext(var_request("txtgzip", $gzip));
	$ctxtshield_spider = updatesettext(var_request("txtshield_spider", $shield_spider));
	$ctxtspider_name = updatesettext(var_request("txtspider_name", $spider_name));
	$ctxtlicense_code = updatesettext(var_request("txtlicense_code", $license_code));
	//配置的字段
	global $pagetext;
	$pagetext = $pagetext . '$site_url="' . $ctxtsite_url . '";' . "\n";
	$pagetext = $pagetext . '$site_title="' . $ctxtsite_title . '";' . "\n";
	$pagetext = $pagetext . '$target_url="' . $ctxttarget_url . '";' . "\n";
	$pagetext = $pagetext . '$target_title="' . $ctxttarget_title . '";' . "\n";
	$pagetext = $pagetext . '$capture_mode="' . $ctxtcapture_mode . '";' . "\n";
	$pagetext = $pagetext . '$anti_theft="' . $ctxtanti_theft . '";' . "\n";
	$pagetext = $pagetext . '$user_curl="' . $ctxtuser_curl . '";' . "\n";
	$pagetext = $pagetext . '$user_agent="' . $ctxtuser_agent . '";' . "\n";
	$pagetext = $pagetext . '$user_client="' . $ctxtuser_client . '";' . "\n";
	$pagetext = $pagetext . '$charset="' . $ctxtcharset . '";' . "\n";
	$pagetext = $pagetext . '$nochange="' . $ctxtnochange . '";' . "\n";
	$pagetext = $pagetext . '$jump="' . $ctxtjump . '";' . "\n";
	$pagetext = $pagetext . '$change_link="' . $ctxtchange_link . '";' . "\n";
	$pagetext = $pagetext . '$link_name="' . $ctxtlink_name . '";' . "\n";
	$pagetext = $pagetext . '$url_encode="' . $ctxturl_encode . '";' . "\n";
	$pagetext = $pagetext . '$nochange_url="' . $ctxtnochange_url . '";' . "\n";
	$pagetext = $pagetext . '$cache_path="' . $ctxtcache_path . '";' . "\n";
	$pagetext = $pagetext . '$cache_suffix="' . $ctxtcache_suffix . '";' . "\n";
	$pagetext = $pagetext . '$cache_time="' . $ctxtcache_time . '";' . "\n";
	$pagetext = $pagetext . '$gzip="' . $ctxtgzip . '";' . "\n";
	$pagetext = $pagetext . '$shield_spider="' . $ctxtshield_spider . '";' . "\n";
	$pagetext = $pagetext . '$spider_name="' . $ctxtspider_name . '";' . "\n";
	$pagetext = $pagetext . '$license_code="' . $ctxtlicense_code . '";' . "\n";
	//写入到缓存中.
	$config_file = "../config.php";
	file_put_contents($config_file, "<?php\n" . $pagetext . "?>");
	//echo"<script>history.go(-1);</script>";
	echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\"><meta http-equiv=refresh content='0; url=index.php?sidebar=" . $sidebar . "&save=ok'>";
	//redirect_to("index.php?step=1");
	exit;
}
if ($sidebar == "8" && $action == "ok" && $save == "ok") {
	$ctxtregex = updatesettext(var_request("txtregex", readfile($regex_file)));
	//配置的字段
	global $regex;
	$regex = $regex . $ctxtregex;
	//写入到缓存中.
	file_put_contents($regex_file, $regex);
	echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\"><meta http-equiv=refresh content='0; url=index.php?sidebar=" . $sidebar . "&save=ok'>";
	exit;
}
if ($sidebar == "10" && $action == "ok" && $save == "ok") {
	$ctxtusername = updatesettext(var_request("txtusername", $username));
	$ctxtpassword = updatesettext(var_request("txtpassword", $password));
	//配置的字段
	global $user;
	$user = $user . '$username="' . $ctxtusername . '";' . "\n";
	$user = $user . '$password="' . $ctxtpassword . '";' . "\n";
	//写入到缓存中
	$user_file = "user.php";
	file_put_contents($user_file, "<?php\n" . $user . "?>");
	echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\"><meta http-equiv=refresh content='0; url=index.php?sidebar=" . $sidebar . "&save=ok'>";
	exit;
}