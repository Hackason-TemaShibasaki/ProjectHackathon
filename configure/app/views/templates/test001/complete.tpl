<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html lang="ja">

<head>
<meta content="ja" http-equiv="Content-Language" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>勤怠報告</title>
</head>

<body>

<form action="http://localhost/garoon_api/Test001/input" name="dummy" method="post" enctype="multipart/form-data">
<h1>送信完了</h1>
<table>
<tr>
<td>勤怠報告を送信しました。</td>
</tr>
</table>

  <br />

<input type="hidden" name="loginName" value="{$loginInfo.loginName}" />
<input type="hidden" name="userId" value="{$loginInfo.userId}" />
<input type="hidden" name="userName" value="{$loginInfo.userName}" />

  <br />
  <input name="Button1" type="submit" value="戻る"  />
  </form>

</body>

</html>

