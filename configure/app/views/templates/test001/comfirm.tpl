<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html lang="ja">

<head>
<meta content="ja" http-equiv="Content-Language" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>確認画面</title>
<style type="text/css">
.auto-style1 {
	border: 2px solid #808080;
}
.auto-style2 {
	border: 1px solid #808080;
}
</style>
</head>

<body>
<form action="http://localhost/garoon_api/Test001/sendmessage" name="dummy" method="post" enctype="multipart/form-data">

<h1>確認画面</h1>
<form action="" method="post">
<table class="auto-style1">
<tr>
<td class="auto-style2">タイトル</td>
<td class="auto-style2" name="title">{$title}</td>
</tr>
<tr>
<td class="auto-style2">本文</td>
<td class="auto-style2" name="main" >{$main|nl2br}<br>
</td>
</tr>
<tr>
<td class="auto-style2">宛先</td>
<td class="auto-style2" name="Destination">{$Destination}</td>
</tr>
</table>

<input type="hidden" name="bodyText" value="{$bodyText}" />
<input type="hidden" name="sendAddres" value="{$sendAddres}" />
<input type="hidden" name="userId" value="{$userId}" />
<input type="hidden" name="subjectText" value="{$subjectText}" />
<input type="hidden" name="userName" value="{$userName}" />


	<br />
	<input name="Button1" type="submit" value="送信"></form>

</body>

</html>

