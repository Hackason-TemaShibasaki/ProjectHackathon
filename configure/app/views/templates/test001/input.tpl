<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html lang="ja">

<head>
<meta content="ja" http-equiv="Content-Language" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>勤怠報告</title>
</head>

<body>

<form action="http://localhost/garoon_api/Comfirm/display" name="dummy" method="post" enctype="multipart/form-data">
<h1>勤怠報告</h1>
<table>
<tr>
<td>日付：</td>
<td><input name="Date" type="text" /></td>
</tr>
<tr>
<td>勤怠：</td>
<td><select name="Attendance" style="width: 105px">
<option>全休</option>
<option>AM休</option>
<option>PM休</option>
<option>電車遅延</option>
<option>Flex</option>
</select></td>
</tr>
<tr>
<td style="height: 34px">理由：</td>
<td style="height: 34px"><textarea cols="20" name="Reason" rows="2"></textarea></td>
</tr>
</table>

<input type="hidden" name="loginName" value="{$loginInfo.loginName}" />
<input type="hidden" name="userId" value="{$loginInfo.userId}" />
<input type="hidden" name="userName" value="{$loginInfo.userName}" />

  <br />

<br>
<table>
<tr>
<td>宛先：</td>
<td><select multiple="multiple" name="Destination" style="width: 173px">
<option value = "10">柴崎稚人</option>
<option>本多竜馬</option>
<option>杉田翔太郎</option>
<option>尾嵜文信</option>
<option>荒川良明</option>
</select></td>
</tr>
</table>
  <br />
  <input name="Button1" type="submit" value="確認" onclick="location.href='confirm.html'" /></form>

</body>

</html>
