<?php
if(!defined('IN_ADMINCP')) exit();
?>
<!DOCTYPE html>
<html>
<head>
<title>管理中心 - 贴吧签到助手</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="HandheldFriendly" content="true" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="author" content="kookxiang" />
<meta name="copyright" content="KK's Laboratory" />
<link rel="shortcut icon" href="favicon.ico" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="renderer" content="webkit">
<link rel="stylesheet" href="./style/main.css?version=<?php echo VERSION; ?>" type="text/css" />
<link rel="stylesheet" href="./style/custom.css" type="text/css" />
</head>
<body>
<div class="wrapper" id="page_index">
<div id="append_parent"><div class="loading-icon"><img src="style/loading.gif" /> 载入中...</div></div>
<div class="main-box clearfix">
<h1>贴吧签到助手 - 管理中心</h1>
<div class="menubtn"><p>-</p><p>-</p><p>-</p></div>
<div class="main-wrapper">
<div class="sidebar">
<ul id="menu" class="menu">
<li id="menu_user"><a href="#user">用户管理</a></li>
<li id="menu_stat"><a href="#stat">用户签到统计</a></li>
<li id="menu_plugin"><a href="#plugin">插件管理</a></li>
<li id="menu_setting"><a href="#setting">系统设置</a></li>
<li id="menu_mail"><a href="#mail">邮件群发</a></li>
<li id="menu_updater"><a href="http://update.kookxiang.com/gateway.php?id=tieba_sign&version=<?php echo VERSION; ?>" target="_blank" onclick="return show_updater_win(this.href)">检查更新</a></li>
<li><a href="./">返回前台</a></li>
</ul>
</div>
<div class="main-content">
<div id="content-user" class="hidden">
<h2>用户管理</h2>
<table>
<thead><tr><td style="width: 40px">UID</td><td>用户名</td><td>邮箱</td><td>操作</td></tr></thead>
<tbody></tbody>
</table>
</div>
<div id="content-stat" class="hidden">
<h2>用户签到统计</h2>
<table>
<thead><tr><td style="width: 40px">UID</td><td>用户名</td><td>已成功</td><td>已跳过</td><td>待签到</td><td>待重试</td><td>不支持</td></tr></thead>
<tbody></tbody>
</table>
</div>
<div id="content-setting" class="hidden">
<h2>系统设置</h2>
<form method="post" action="admin.php?action=save_setting" id="setting_form" onsubmit="return post_win(this.action, this.id)">
<p>功能增强:</p>
<input type="hidden" name="formhash" value="<?php echo $formhash; ?>">
<p><label><input type="checkbox" id="account_switch" name="account_switch" /> 允许多用户切换</label></p>
<p><label><input type="checkbox" id="autoupdate" name="autoupdate" /> 每天自动更新用户喜欢的贴吧 (稍占服务器资源)</label></p>
<p>功能限制:</p>
<p>
<select name="max_tieba" id="max_tieba">
<option value="0" selected>不限制单用户的最大喜欢贴吧数量</option>
<option value="50">每个用户最多喜欢 50 个贴吧</option>
<option value="80">每个用户最多喜欢 80 个贴吧</option>
<option value="100">每个用户最多喜欢 100 个贴吧</option>
<option value="120">每个用户最多喜欢 120 个贴吧</option>
<option value="180">每个用户最多喜欢 180 个贴吧</option>
<option value="250">每个用户最多喜欢 250 个贴吧</option>
</select>
</p>
<p>防恶意注册:</p>
<p><label><input type="checkbox" id="block_register" name="block_register" /> 彻底关闭新用户注册功能</label></p>
<p><label><input type="checkbox" id="register_check" name="register_check" /> 启用内置的简单防恶意注册系统 (可能会导致无法注册)</label></p>
<p><label><input type="checkbox" id="register_limit" name="register_limit" /> 限制并发注册 (开启后可限制注册机注册频率)</label></p>
<p><input type="text" name="invite_code" id="invite_code" placeholder="邀请码 (留空为不需要)" /></p>
<p>jQuery 加载方式:</p>
<p><label><input type="radio" id="jquery_1" name="jquery_mode" value="1" /> 从 Google API 提供的 CDN 加载 (默认, 推荐)</label></p>
<p><label><input type="radio" id="jquery_2" name="jquery_mode" value="2" /> 从 Sina App Engine 提供的 CDN 加载</label></p>
<p><label><input type="radio" id="jquery_3" name="jquery_mode" value="3" /> 从 Baidu App Engine 提供的 CDN 加载 (不支持 SSL)</label></p>
<p><label><input type="radio" id="jquery_4" name="jquery_mode" value="4" /> 使用程序自带的 jQuery 类库 (推荐)</label></p>
<p>网站备案编号:</p>
<p><input type="text" id="beian_no" name="beian_no" placeholder="未备案的不需要填写" /></p>
<p>自定义统计代码:</p>
<p><textarea name="stat_code" id="stat_code" rows="3" style="width: 300px; max-width: 100%;"></textarea></p>
<p><input type="submit" value="保存设置" /></p>
</form>
<br>
<p>邮件发送方式:</p>
<form method="post" action="admin.php?action=mail_setting" id="mail_setting" onsubmit="return post_win(this.action, this.id)">
<input type="hidden" name="formhash" value="<?php echo $formhash; ?>">
<?php
foreach($classes as $id=>$obj){
	$desc = $obj->description ? ' - '.$obj->description : '';
	if(!$obj->isAvailable()) $desc = ' (当前服务器环境不支持)';
	echo '<p><label><input type="radio" name="mail_sender" value="'.$id.'"'.($obj->isAvailable() ? '' : ' disabled').($id == getSetting('mail_class') ? ' checked' : '').' /> '.$obj->name.$desc.'</label></p>';
}
?>
<p>
<input type="submit" value="保存设置" />
 &nbsp; <a href="javascript:;" class="btn" id="mail_advanced_config">高级设置</a>
 &nbsp; <a href="admin.php?action=mail_test&formhash=<?php echo $formhash; ?>" class="btn" onclick="return msg_win_action(this.href)">发送测试</a>
</p>
</form>
</div>
<div id="content-mail" class="hidden">
<h2>邮件群发</h2>
<p>此功能用于向本站已经注册的所有用户发送邮件公告</p>
<p>为避免用户反感，建议您不要经常发送邮件</p>
<br>
<form method="post" action="admin.php?action=send_mail" id="send_mail" onsubmit="return post_win(this.action, this.id)">
<input type="hidden" name="formhash" value="<?php echo $formhash; ?>">
<p>邮件标题：</p>
<p><input type="text" name="title" style="width: 80%" /></p>
<p>邮件内容：</p>
<p><textarea name="content" rows="10" style="width: 80%"></textarea></p>
<p><input type="submit" value="确认发送" /></p>
</form>
</div>
<div id="content-plugin" class="hidden">
<h2>插件管理</h2>
<p>安装相关插件能够增强 贴吧签到助手 的相关功能.（部分插件可能会影响系统运行效率）</p>
<p>插件的设计可以参考 Github 上的项目介绍.</p>
<p>将插件文件放到 /plugins/ 文件夹下即可在此处看到对应的插件程序.</p>
<p>如果你觉得某个插件有问题，你可以先尝试禁用它，禁用操作不会丢失数据.</p>
<p>插件下载: <a href="http://bbs.kookxiang.com/forum-addon-1.html" target="_blank">http://bbs.kookxiang.com/forum-addon-1.html</a></p>
<table>
<thead><tr><td style="width: 40px">#</td><td>插件标识符 (ID)</td><td>插件介绍</td><td>当前版本</td><td>操作</td></tr></thead>
<tbody></tbody>
</table>
</div>
</div>
</div>
</div>
<p class="copyright">当前版本：<?php echo VERSION; ?> - <a href="https://me.alipay.com/kookxiang" target="_blank">赞助开发</a><br>Designed by <a href="http://www.ikk.me" target="_blank">kookxiang</a>. 2013 &copy; <a href="http://www.kookxiang.com" target="_blank">KK's Laboratory</a><br>请勿擅自修改程序版权信息或将本程序用于商业用途！<br><?php echo DEBUG::output(); ?></p>
</div>
<script src="<?php echo jquery_path(); ?>"></script>
<script type="text/javascript">var formhash = '<?php echo $formhash; ?>';var version = '<?php echo VERSION; ?>';</script>
<script src="system/js/kk_dropdown.js?version=<?php echo VERSION; ?>"></script>
<script src="system/js/admin.js?version=<?php echo VERSION; ?>"></script>
<script src="system/js/fwin.js?version=<?php echo VERSION; ?>"></script>
</body>
</html>