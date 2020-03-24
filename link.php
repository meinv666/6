<?php
include_once dirname(__FILE__).'/inc.php';
$url = var_request("url","");
if($url==""){
  $out_link = "/";
}else{
  $out_link = $url;
  if($url_encode=="1"){
    $out_link = url_base64_decode($out_link);
  }
  $out_link = urldecode($out_link);
}
?>
<html>
<head>
<title><?php echo $site_title;?></title>
<style>body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px;overflow: hidden;}</style>
</head>
<body>
<iframe src="<?php echo $out_link;?>" width="100%" height="100%" frameborder="0"></iframe>
</body>
</html>