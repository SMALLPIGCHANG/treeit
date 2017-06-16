<?php
	//pclose(popen('usr/bin/php script.php &', 'r'));	
	//header("location: http://www.yahoo.com.tw");
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>測試用</title>
<?php
set_time_limit(0);
//post傳值
/*for($i=1 ; $i<45 ; $i++)
{
	$ans[$i] = $_POST["Q$i"];
}
for($i=1 ; $i<45 ; $i++)
{
	echo "Q".$i.":";
	if($ans[$i]==1)
		echo "非常同意</br></br>";
	else if($ans[$i]==2)
		echo "同意</br></br>";
	else if($ans[$i]==3)
		echo "普通</br></br>";
	else if($ans[$i]==4)
		echo "不同意</br></br>";
	else if($ans[$i]==5)
		echo "非常不同意</br></br>";
}*/

$y=$_GET['year'];
$m=$_GET['month'];
$d=$_GET['day'];
$hour=$_GET['hour'];
$min=$_GET['min'];
$sec=$_GET['sec'];
$aloha=$_GET['t'];
$char=$_GET['char'];

//取得特定時間的unix時間
$hi = mktime($hour,$min,$sec,$m,$d,$y);
if($y!=null)
	echo date("Y/m/d H:i:s",$hi)."<---------->".$hi."</br></br>";
else
	echo date("Y/m/d H:i:s",time())."<---------->".time()."</br></br>";
echo "<form method='get'  action='123.php'>
		<input type='text' name='year' size='4'>年
		<input type='text' name='month' size='2'>月
		<input type='text' name='day' size='2'>日
		<input type='text' name='hour' size='2'>:
		<input type='text' name='min' size='2'>:
		<input type='text' name='sec' size='2'>
		<input type='submit' value='go' />
		</form>";


//unix轉時間
if($aloha!=null)
	echo $aloha."<---------->".date("Y/m/d H:i:s",$aloha)."</br></br>";
else
	echo time()."<---------->".date("Y/m/d H:i:s",time())."</br></br>";
echo "<form method='get'  action='123.php'>
		<input type='text' name='t' size='20'>
		<input type='submit' value='go' />
		</form>";

//取得特定時間字串的unix時間
if($char!=null)
	$hello = strtotime($char);
else
	$hello = strtotime("2013-07-16T01:58:36+0000");
echo $char."<---------->".date("Y/m/d H:i:s",$hello)."<---------->".$hello."</br></br>";
echo "<form method='get'  action='123.php'>
		<input type='text' name='char' size='20'>
		<input type='submit' value='go' />
		</form>";


//連接資料庫
$dbhost = 'localhost';
$dbuser = 'b9929061';
$dbpass = 'b9929061';
$dbname = 'b9929061';
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
mysql_select_db($dbname);
mysql_query("SET NAMES 'utf8'");
//mysql_query ("insert into tb2 values ('0','0','0','0','0','0')");

//將associate內互動為0的剃除，並存入associate2中
/*$result = mysql_query("select * from associate");
while($row = mysql_fetch_array($result)){
	//變數處理
	$id=$row['id'];
	$friend_id=$row['friend_id'];
	$row['9']="'xxx'";
	$row['14']="'xxx'";
	$row['15']="'xxx'";
	$row['17']="'xxx'";
	for($i=0 ; $i<18 ; $i++){
		if($i==0)
			$ins=$row[$i];
		else
			$ins=$ins.",".$row[$i];
	}
	//檢查是否存在associate2內
	$re = mysql_query("select * from associate2 where id='$id' and friend_id='$friend_id'");
	$r = mysql_fetch_array($re);
	//如果沒有,新增
	if($r['id']==null){
		if($row['f_picture_reply']!=0 || $row['f_alb_reply']!=0 || $row['f_picture_like']!=0 || $row['f_alb_like']!=0 || $row['f_po_reply']!=0 || $row['f_po_like']!=0 || $row['f_reply_like']!=0){
			echo "insert into associate2 values ($ins)</br>";
			mysql_query("insert into associate2 values ($ins)");
		}
	}
	//如果有,修改
	else{
		echo "delete from associate2 where id='$id' and friend_id='$friend_id'</br>";
		echo "insert into associate2 values ($ins)</br>";
		mysql_query("delete from associate2 where id='$id' and friend_id='$friend_id'");
		mysql_query("insert into associate2 values ($ins)");
	}
	$ins="";
}*/

//查看某人共同好友排名
/*$id='100001173013348';
$result = mysql_query("select * from associate where id='$id' order by same_friend_num desc");
while($row = mysql_fetch_array($result))
{
	//echo "<a href=\"http://www.facebook.com/profile.php?id=$row['friend_id']\"><img src=\"http://graph.facebook.com/$row['friend_id']/picture\" /></a>";
	echo "<a href=\"http://www.facebook.com/profile.php?id=".$row['friend_id']."\"><img src=\"http://graph.facebook.com/".$row['friend_id']."/picture\" /></a>";
	echo str_pad($row['same_friend_num'],3,'0',STR_PAD_LEFT);
}*/

//刪除單筆資訊
/*$id='100001173013348';//胡
mysql_query("delete from ttb5 where id='$id'");
mysql_query("delete from tb5 where id='$id'");
mysql_query("delete from tb6 where id='$id'");
mysql_query("delete from user_about where id='$id'");
mysql_query("delete from associate where id='$id'");
echo "已刪除 <font color='red'>ttb5 tb5 tb6 user_about associate</font> 內的".$id."</br>";

$id='100001303371482';//郭
mysql_query("delete from ttb5 where id='$id'");
mysql_query("delete from tb5 where id='$id'");
mysql_query("delete from tb6 where id='$id'");
mysql_query("delete from user_about where id='$id'");
mysql_query("delete from associate where id='$id'");
echo "已刪除 <font color='red'>ttb5 tb5 tb6 user_about associate</font> 內的".$id."</br>";

$id='100000200225311';//東
mysql_query("delete from ttb5 where id='$id'");
mysql_query("delete from tb5 where id='$id'");
mysql_query("delete from tb6 where id='$id'");
mysql_query("delete from user_about where id='$id'");
mysql_query("delete from associate where id='$id'");
echo "已刪除 <font color='red'>ttb5 tb5 tb6 user_about associate</font> 內的".$id."</br>";

$id='655432748';//梓佑
mysql_query("delete from ttb5 where id='$id'");
mysql_query("delete from tb5 where id='$id'");
mysql_query("delete from tb6 where id='$id'");
mysql_query("delete from user_about where id='$id'");
mysql_query("delete from associate where id='$id'");
echo "已刪除 <font color='red'>ttb5 tb5 tb6 user_about associate</font> 內的".$id."</br>";*/

/*$id='1159847706';//lyo
mysql_query("delete from ttb5 where id='$id'");
mysql_query("delete from tb5 where id='$id'");
mysql_query("delete from tb6 where id='$id'");
mysql_query("delete from user_about where id='$id'");
mysql_query("delete from associate where id='$id'");
echo "已刪除 <font color='red'>ttb5 tb5 tb6 user_about associate</font> 內的".$id."</br>";*/

/*$id='1434976032';//小豬
mysql_query("delete from ttb5 where id='$id'");
mysql_query("delete from tb5 where id='$id'");
mysql_query("delete from tb6 where id='$id'");
mysql_query("delete from user_about where id='$id'");
mysql_query("delete from associate where id='$id'");
echo "已刪除 <font color='red'>ttb5 tb5 tb6 user_about associate</font> 內的".$id."</br>";*/

/*$id='100001173013348';
$re = mysql_query("select * from associate where id='$id'");
while($r = mysql_fetch_array($re)){
	$fid = $r['friend_id'];
	$temp = explode(",",$r['f_po_reply']);
	$s = sizeof($temp) - 1;
	$f_po_reply = $temp[0].",".$temp[$s];
	
	$temp = explode(",",$r['f_po_like']);
	$s = sizeof($temp) - 1;
	$f_po_like = $temp[0].",".$temp[$s];
	
	$temp = explode(",",$r['f_reply_like']);
	$s = sizeof($temp) - 1;
	$f_reply_like = $temp[0].",".$temp[$s];
	
	$temp = explode(",",$r['share_f_po']);
	$s = sizeof($temp) - 1;
	$share_f_po = $temp[0].",".$temp[$s];
	
	$temp = explode(",",$r['same_tag_num']);
	$s = sizeof($temp) - 1;
	$same_tag_num = $temp[0].",".$temp[$s];
	
	mysql_query ("update `associate` set `f_po_reply`='$f_po_reply',`f_po_like`='$f_po_like',`f_reply_like`='$f_reply_like',`share_f_po`='$share_f_po',`same_tag_num`='$same_tag_num' where `id`='$id' and `friend_id`=$fid");
}*/
/*$re = mysql_query("select * from associate");
while($r = mysql_fetch_array($re)){
	$id = $r['id'];
	$fid = $r['friend_id'];
	if($r['f_po_reply']=='')
		mysql_query ("update `associate` set `f_po_reply`='0' where `id`='$id' and `friend_id`=$fid");
	if($r['f_po_like']=='')
		mysql_query ("update `associate` set `f_po_like`='0' where `id`='$id' and `friend_id`=$fid");
	if($r['f_reply_like']=='')
		mysql_query ("update `associate` set `f_reply_like`='0' where `id`='$id' and `friend_id`=$fid");
	if($r['share_f_po']=='')
		mysql_query ("update `associate` set `share_f_po`='0' where `id`='$id' and `friend_id`=$fid");
	if($r['same_tag_num']=='')
		mysql_query ("update `associate` set `same_tag_num`='0' where `id`='$id' and `friend_id`=$fid");
}*/
/*$user_about = mysql_query("select * from `user_about` where `update_time`<'2013/08/09 00:00:00' and `update_time`>='2013/08/08 00:00:00' order by `update_time` desc");
while($user = mysql_fetch_array($user_about)){
	echo $user['id']."</br>";
	$id = $user['id'];
	$po = mysql_query("select * from `po` where `from`='$id'");
	$comment = mysql_query("select * from `comment` where `to`='$id'");
	$commentt = mysql_query("select * from `comment` where `from`='$id'");
	$album = mysql_query("select * from `album` where `from`='$id'");
	$picture = mysql_query("select * from `picture` where `from`='$id'");
	$checkin = mysql_query("select * from `check_in` where `from`='$id'");
	$check = mysql_query("select * from `check_in` where `tag` like '%$id%'");
	$re = mysql_query("select * from `associate` where `id`='$id'");
	while($r = mysql_fetch_array($re)){
		$id = $r['id'];
		$fid = $r['friend_id'];
		//更新f_po_like
		$f_po_like = 0;
		if( mysql_num_rows( $po )>0)
			mysql_data_seek($po,0);
		while($p = mysql_fetch_array($po)){
			$temp = explode(",",$p['like']);
			if( in_array($fid,$temp) ){
				if($p['time']>='2013/07/01 00:00:00' && $p['time']<'2013/08/01 00:00:00')
					$f_po_like++;
			}
		}
		//更新f_po_reply & f_picture_reply & f_alb_reply
		$f_po_reply = 0;
		$f_picture_reply = 0;
		$f_alb_reply = 0;
		if( mysql_num_rows( $comment )>0)
			mysql_data_seek($comment,0);
		while($com = mysql_fetch_array($comment)){
			if($com['from']==$fid){
				if($com['time']>='2013/07/01 00:00:00' && $com['time']<'2013/08/01 00:00:00'){
					if($com['type']=='feed')
						$f_po_reply++;
					else if($com['type']=='picture')
						$f_picture_reply++;
					else if($com['type']=='album')
						$f_alb_reply++;
				}
			}
		}
		//更新f_reply_like
		$f_reply_like = 0;
		if( mysql_num_rows( $commentt )>0)
			mysql_data_seek($commentt,0);
		while($com = mysql_fetch_array($commentt)){
			$temp = explode(",",$com['like']);
			if( in_array($fid,$temp) ){
				if($com['time']>='2013/07/01 00:00:00' && $com['time']<'2013/08/01 00:00:00')
					$f_reply_like++;
			}
		}
		//更新f_alb_like
		$f_alb_like = 0;
		if( mysql_num_rows( $album )>0)
			mysql_data_seek($album,0);
		while($alb = mysql_fetch_array($album)){
			$temp = explode(",",$alb['like']);
			if( in_array($fid,$temp) )
					$f_alb_like++;
		}
		//更新f_picture_like
		$f_picture_like = 0;
		if( mysql_num_rows( $picture )>0)
			mysql_data_seek($picture,0);
		while($pic = mysql_fetch_array($picture)){
			$temp = explode(",",$pic['like']);
			if( in_array($fid,$temp) ){
				if($pic['time']>='2013/07/01 00:00:00' && $pic['time']<'2013/08/01 00:00:00' && $pic['alb_id']!=1)
					$f_picture_like++;
			}
		}
		//更新same_tag_num
		$same_tag_num = 0;
		if( mysql_num_rows( $checkin )>0)
			mysql_data_seek($checkin,0);
		while($che = mysql_fetch_array($checkin)){
			$temp = explode(",",$che['tag']);
			if( in_array($fid,$temp) ){
				if($che['time']>='2013/07/01 00:00:00' && $che['time']<'2013/08/01 00:00:00')
					$same_tag_num++;
			}
		}
		if( mysql_num_rows( $check )>0)
			mysql_data_seek($check,0);
		while($che = mysql_fetch_array($check)){
			if( $che['from']==$fid ){
				if($che['time']>='2013/07/01 00:00:00' && $che['time']<'2013/08/01 00:00:00')
					$same_tag_num++;
			}
		}
		//update `associate` set `f_po_reply`='0',`f_po_like`='0',`f_reply_like`='0',`f_picture_reply`='0',`f_alb_reply`='0',`f_alb_like`='0',`f_picture_like`='0',`same_tag_num`='0'
		mysql_query ("update `associate` set `f_po_reply`='$f_po_reply',`f_po_like`='$f_po_like',`f_reply_like`='$f_reply_like',`f_picture_reply`='$f_picture_reply',`f_alb_reply`='$f_alb_reply',`f_alb_like`='$f_alb_like',`f_picture_like`='$f_picture_like',`same_tag_num`='$same_tag_num' where `id`='$id' and `friend_id`='$fid'");
	}
}*/
//回歸直線
/*$i = 0;
$avg_x1 = 0;
$avg_x2 = 0;
$avg_y = 0;
$temp = mysql_query("select * from `test` ");
while($d = mysql_fetch_array($temp)){
	$data[$i][0] = $d['x1']; //x1
	$avg_x1 += $d['x1'];
	$data[$i][1] = $d['x2']; //x2
	$avg_x2 += $d['x2'];
	$data[$i][2] = $d['y']; //y
	$avg_y += $d['y'];
	$i++;
}
$n = sizeof($data);
$avg_x1 = $avg_x1 / $n;
$avg_x2 = $avg_x2 / $n;
$avg_y = $avg_y /$n;
//echo $avg_x1.' '.$avg_x2.' '.$avg_y.'</br>';
$s11 = 0;
$s22 = 0;
$syy = 0;
$s1y = 0;
$s2y = 0;
$s12 = 0;
for( $i=0 ; $i<$n ; $i++ ){
	$data[$i][3] = $data[$i][0] - $avg_x1; //x1 - x.bar
	$data[$i][4] = $data[$i][1] - $avg_x2; //x2 - x.bar
	$data[$i][5] = $data[$i][2] - $avg_y; //y - y.bar
	$s11 = $s11 + ( $data[$i][3] * $data[$i][3] ); //s11
	$s22 = $s22 + ( $data[$i][4] * $data[$i][4] ); //s22
	$syy = $syy + ( $data[$i][5] * $data[$i][5] ); //syy
	$s1y = $s1y + ( $data[$i][3] * $data[$i][5] ); //s1y
	$s2y = $s2y + ( $data[$i][4] * $data[$i][5] ); //s2y
	$s12 = $s12 + ( $data[$i][3] * $data[$i][4] ); //s12
	//echo $s11.' '.$s22.' '.$syy.' '.$s1y.' '.$s2y.' '.$s12.'</br>';
}
$b1 = ($s22*$s1y - $s12*$s2y)/($s11*$s22 - $s12*$s12);
$b2 = ($s11*$s2y - $s12*$s1y)/($s11*$s22 - $s12*$s12);
$b0 = $avg_y - $b1*$avg_x1 - $b2*$avg_x2;
echo "<font face='標楷體'>y = $b0 ";
if( $b1 < 0 )
	echo Round($b1,5)."x<sub>1</sub> ";
else if( $b1 > 0 )
	echo "+ ".Round($b1,5)."x<sub>1</sub> ";
if( $b2 < 0 )
	echo Round($b2,5)."x<sub>2</sub> ";
else if( $b2 > 0 )
	echo "+ ".Round($b2,5)."x<sub>2</sub> ";
echo "</font>";*/

//共同粉絲
/*$id1 = $_GET['a'];
$id2 = $_GET['b'];
echo $id1."</br>";
echo $id2."</br>";
$fan1 = mysql_query("select * from `fan_page` where `museum_id`='$id1' ");
$fan2 = mysql_query("select * from `fan_page` where `museum_id`='$id2' ");
$f1 = mysql_fetch_array($fan1);
$f2 = mysql_fetch_array($fan2);

$temp1 = explode(',',$f1['user']);
$temp2 = explode(',',$f2['user']);

$line=0;
for($i=1;$i<sizeof($temp1);$i++){
	if( in_array( $temp1[$i],$temp2 ) ){
		$line++;
		echo $temp1[$i]."</br>";
	}
}
echo $line;
mysql_query("insert into `fan_associate` values('$id1','$id2','$line') ");*/


//去除陣列中相同的元素
/*$a[0]='123';
$a[1]='456';
$a[2]='123';
$a[3]='798';
print_r($a);
echo "</br>";
$a = array_unique($a);
print_r($a);*/

//更新計算
/*$type_a=0;
$type_b=0;
$type_c=0;
$id = '100000200225311';
$time = time()-7*86400;
$time = date("Y/m/d H:i:s",$time);
//aaaaaaaaaaaaa
//大頭貼次數
$picture = mysql_query("select * from `picture` where `from`='$id' and `alb_name`='Profile Pictures' and `time`>'$time' ");
//echo mysql_num_rows($picture)."</br>";
if( mysql_num_rows($picture)>=5 ){
	echo "對於自己非常的有自信";
	$type_a=1;
}
else
	echo "沒有什麼特別的變化，可以考慮改變造型或許能夠改變心情";
echo "</br>";
//封面照面次數
$picture = mysql_query("select * from `picture` where `from`='$id' and `alb_name`='Cover Photos' and `time`>'$time' ");
//echo mysql_num_rows($picture)."</br>";
if( mysql_num_rows($picture)>=5 ){
	echo "很想在生活當中增添一些變化";
	$type_a=1;
}
else
	echo "對於目前穩定的生活感到滿意";
echo "</br>";
//po照片次數
$picture = mysql_query("select * from `picture` where `from`='$id' and `time`>'$time' ");
//echo mysql_num_rows($picture)."</br>";
if( mysql_num_rows($picture)>=5 ){
	echo "分享了許多關於自己的生活或旅遊";
	$type_a=1;
}
else
	echo "是否都投入在工作中呢?下周建議可以安排時間去郊外走走";
echo "</br></br>";
//bbbbbbbbbbbb
//po文次數
$po = mysql_query("select * from `po` where `from`='$id' and `time`>'$time' ");
//echo mysql_num_rows($po)."</br>";
if( mysql_num_rows($po)>=5 ){
	echo "有各式各樣想要表達的情緒，要注意不要一直沉溺於同一個情緒中";
	$type_b=1;
}
else
	echo "心情很平靜";
echo "</br>";
//留言次數
$commentt = mysql_query("select * from `comment` where `from`='$id' and `time`>'$time' ");
//echo mysql_num_rows($commentt)."</br>";
if( mysql_num_rows($commentt)>=5 ){
	echo "對於親友講的話有共鳴";
	$type_b=1;
}
else
	echo "很低調的觀察朋友的一舉一動";
echo "</br>";
//聊天次數
$message = mysql_query("select `to` from `message` where `from`='$id' and `time`>'$time' group by `to` ");
//echo mysql_num_rows($message)."</br>";
if( mysql_num_rows($message)>=5 ){
	echo "跟朋友有親近的互動";
	$type_b=1;
}
else
	echo "跟朋友沒有特別親近的互動";
echo "</br></br>";
//ccccccccccccc
//朋友點讚次數
$like = 0;
while($p = mysql_fetch_array($po)){
	$temp = explode(",",$p['like']);
	$like += sizeof($temp);
}
//echo $like."</br>";
if( $like>=5 )
	$type_c=1;
//朋友留言次數
$comment = mysql_query("select * from `comment` where `to`='$id' and `time`>'$time' ");
//echo mysql_num_rows($comment)."</br>";
if( mysql_num_rows($comment)>=5 )
	$type_c=1;
//朋友聊天次數
$message = mysql_query("select `from` from `message` where `to`='$id' and `time`>'$time' group by `from` ");
//echo mysql_num_rows($message)."</br>";
if( mysql_num_rows($message)>=5 )
	$type_c=1;
if($type_c==1)
	echo "最近的狀況受到朋友的關注";
else
	echo "跟朋友有點疏遠，建議可以多主動跟朋友互動";
echo "</br>";*/

/*$feed = mysql_query("select * from `fan_feed`");
while($fee = mysql_fetch_array($feed)){
	$feed_id = $fee['feed_id'];
	$temp = explode('_',$fee['feed_id']);
	$page_id = $temp[0];
	if($page_id != $fee['from'])
		echo "update `fan_feed` set `page_id`='$page_id' where `feed_id`='$feed_id'</br>";
	//mysql_query("update `fan_feed` set `page_id`='$page_id' where `feed_id`='$feed_id'");
}*/

//將blog時間改正
/*$blog = mysql_query("select * from `comment` where `type`='blog'");
while($b = mysql_fetch_array($blog)){
	$comment_id = $b['comment_id'];
	$time = $b['time'];
	$time = date("Y/m/d H:i:s",$time);
	echo "update `comment` set `time`='$time' where `comment_id`='$comment_id'</br>";
	mysql_query("update `comment` set `time`='$time' where `comment_id`='$comment_id'");
}*/

//計算!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//step1:計算 Si ( 0.5*ri + 0.5*di + Ni 即為分數 )
/*$id = '100001173013348';
//算ri
$po = mysql_query("select * from `po` where `from`='$id'");
$ri += mysql_num_rows( $po );
$user_about = mysql_query("select * from `user_about` where `id`='$id'");
$user = mysql_fetch_array($user_about);
$ri += $user['edu_work'];
if($user['about_me']>0)
	$ri++;
if($user['quote']>0)
	$ri++;
$ri += $user['live'];
$ri += $user['basic_data'];
$ri += $user['message_data'];
$ri += $user['picture_up_num'];
$ri += $user['alb_num'];
$ri += $user['tag_num'];
$ri += $user['like_music'];
$ri += $user['like_movie'];
$ri += $user['like_TV'];
$ri += $user['like_book'];
$ri += $user['like_etc'];
$ri += $user['blog_num'];
$ri += $user['group_num'];
$ri += $user['active_num'];
$ri = $ri/5000;
$b = 0.01;
$r = 0.99;
//echo $ri."</br>";//算ri end
$friend = mysql_query("select * from `associate` where `id`='$id'");
$i = 0;
while($f = mysql_fetch_array($friend)){
	$di=0;
	$fid = $f['friend_id'];
	$point[$i][0] = $f['friend_id'];//0:朋友id
	//算di
	//album : like
	$album = mysql_query("select * from `album` where `from`='$id'");
	while($alb = mysql_fetch_array($album)){
		if( substr_count($alb['like'],$fid)!=0 )
			$di++;
	}
	$album = mysql_query("select * from `album` where `from`='$fid'");
	while($alb = mysql_fetch_array($album)){
		if( substr_count($alb['like'],$id)!=0 )
			$di++;
	}
	//picture : tag,like
	$picture = mysql_query("select * from `picture` where `from`='$id'");
	while($pic = mysql_fetch_array($picture)){
		if( substr_count($pic['tag'],$fid)!=0 )
			$di++;
		if( substr_count($pic['like'],$fid)!=0 )
			$di++;
	}
	$picture = mysql_query("select * from `picture` where `from`='$fid'");
	while($pic = mysql_fetch_array($picture)){
		if( substr_count($pic['tag'],$id)!=0 )
			$di++;
		if( substr_count($pic['like'],$id)!=0 )
			$di++;
	}
	//check_in : tag
	$check_in = mysql_query("select * from `check_in` where `from`='$id'");
	while($check = mysql_fetch_array($check_in)){
		if( substr_count($check['tag'],$fid)!=0 )
			$di++;
	}
	$check_in = mysql_query("select * from `check_in` where `from`='$fid'");
	while($check = mysql_fetch_array($check_in)){
		if( substr_count($check['tag'],$id)!=0 )
			$di++;
	}
	//po : tag,like
	$po = mysql_query("select * from `po` where `from`='$id'");
	while($p = mysql_fetch_array($picture)){
		if( substr_count($p['tag'],$fid)!=0 )
			$di++;
		if( substr_count($p['like'],$fid)!=0 )
			$di++;
	}
	$po = mysql_query("select * from `po` where `from`='$fid'");
	while($p = mysql_fetch_array($picture)){
		if( substr_count($p['tag'],$id)!=0 )
			$di++;
		if( substr_count($p['like'],$id)!=0 )
			$di++;
	}
	//message : to
	$message = mysql_query("select * from `message` where `from`='$id'");
	while($msg = mysql_fetch_array($message)){
		if( $msg['to']==$fid )
			$di++;
	}
	$message = mysql_query("select * from `message` where `from`='$fid'");
	while($msg = mysql_fetch_array($message)){
		if( $msg['to']==$id )
			$di++;
	}
	//comment : to,tag,like
	$comment = mysql_query("select * from `comment` where `from`='$id'");
	while($com = mysql_fetch_array($comment)){
		if( $com['to']==$fid )
			$di++;
		if( substr_count($com['tag'],$fid)!=0 )
			$di++;
		if( substr_count($com['like'],$fid)!=0 )
			$di++;
	}
	$comment = mysql_query("select * from `comment` where `from`='$fid'");
	while($com = mysql_fetch_array($comment)){
		if( $com['to']==$id )
			$di++;
		if( substr_count($com['tag'],$id)!=0 )
			$di++;
		if( substr_count($com['like'],$id)!=0 )
			$di++;
	}
	//associate : same_friend_num,same_group_num
	$associate = mysql_query("select * from `associate` where `id`='$id' and `friend_id`='$fid'");
	$asso = mysql_fetch_array($associate);
	$di = $di + $asso['same_friend_num'] + $asso['same_group_num'];
	$di = $di/5000;
	//echo $di."</br>";//算di end
	$point[$i][7] = $di;//7:Di
	$point[$i][1] = $b*$ri + $r*$di;//1:Si
	//echo $point[$i][0]." ".$point[$i][1]."</br>";
	$i++;
}
//step2:求出每個朋友的 M ( 計算 Ni 的參數 ) 並計算 Ni 與新 Si
$n = sizeof($point);
$N=9;
for( $step=0 ; $step<$N ; $step++ ){//疊代9次
	echo "<h1>step ".$step."</h1></br>";
	for( $i=0 ; $i<$n ; $i++){//n個朋友都要求M
		$fid = $point[$i][0];
		$mutal = mysql_query("select * from `associate_detail` where `id`='$id' and `friend_id`='$fid' ");
		$m = mysql_fetch_array($mutal);
		$mutal_friend = explode(',',$m['same_friend']);//找出第i位好友與我的共同好友
		for( $j=0 ; $j<sizeof($mutal_friend) ; $j++ ){//取得與第i位朋友的共同好友Si
			if($mutal_friend[$j] != null){//防呆(資料庫可能誤存連續兩個逗號 ex: 123,456,459,,4588,125)
				for( $k=0 ; $k<$n ; $k++){//比對第j位共同好友的id  以及  第k位好友的id 才可獲得Si
					if( $point[$k][0] == $mutal_friend[$j] ){
						if($temp_m == null)
							$temp_m = $point[$k][1];
						else
							$temp_m = $temp_m.",".$point[$k][1];
						break;
					}
				}
			}
		}
		if($step==$N-1){
			echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a>";
			echo $fid."</br>";
			echo "old Si=".$point[$i][1]."</br>";
			echo "M={".$temp_m."}</br>";//第i位好友的M (字串型態)
		}
		$temp_temp = explode(',',$temp_m);//第i位好友的M (array型態)
		$temp_m=null;
		sort($temp_temp);
		$point[$i][2] = $temp_temp[0];//取得2:min
		if(sizeof($temp_temp) % 2 ==0)//取得3:med
			$point[$i][3] = ($temp_temp[ (sizeof($temp_temp)/2 - 1) ] + $temp_temp[ (sizeof($temp_temp)/2) ])/2;
		else
			$point[$i][3] = $temp_temp[ (sizeof($temp_temp)/2) ];
		$point[$i][4] = $temp_temp[ sizeof($temp_temp)-1 ];//取得4:max
		$sum=0;
		for( $j=0 ; $j<sizeof($temp_temp) ; $j++)//取得5:avg
			$sum += $temp_temp[$j];
		$point[$i][5] = $sum/sizeof($temp_temp);
		//計算雙重迴圈部分
		$loop = 0;
		for( $j=0 ; $j<sizeof($temp_temp) ;$j++){
			$loop = $loop + 0.165*pow(($temp_temp[$j] - $point[$i][5]),2);
			$loop = $loop + 0.138*pow(($temp_temp[$j] - $point[$i][5]),3);
			$loop = $loop + 0.079*pow(($temp_temp[$j] - $point[$i][5]),4);
		}
		if($step==$N-1)
			echo "min=".$point[$i][2].",med=".$point[$i][3].",max=".$point[$i][4].",avg=".$point[$i][5].",std=".$point[$i][6]."</br>";
		//算 Ni 與新 Si
		//Ni = 0.328*avg + 0.197*med + 0.165*(std-avg)^2 + 0.138*(std-avg)^3 +0.079*(std-avg)^4 + 0.048*min + 0.045*max
		//$Ni = 0.328*$point[$i][5] + 0.197*$point[$i][3] + 0.165*pow(($point[$i][6]-$point[$i][5]),2) + 0.138*pow(($point[$i][6]-$point[$i][5]),3) + 0.079*pow(($point[$i][6]-$point[$i][5]),4) + 0.048*$point[$i][2] + 0.045*$point[$i][4];
		$Ni = 0.328*$point[$i][5] + 0.197*$point[$i][3] + $loop + 0.048*$point[$i][2] + 0.045*$point[$i][4];
		$new[$i][1] = $b*$ri + $r*$point[$i][7] + $Ni;
		if($step==$N-1){
			echo "new Si=".$new[$i][1]."</br>ri=".$ri." di=".$point[$i][7]." Ni=".$Ni."</br>";
			$score = $new[$i][1];
			$social = mysql_query("select * from `social_score` where `id`='$id' and `fid`='$fid'");
			$soci = mysql_fetch_array($social);
			//if($soci['id']==null)
				//mysql_query ("insert into `social_score` values ('$id','$fid','$score','')");
			//else
				//mysql_query ("update `social_score` set `score`='$score' where `id`='$id' and `fid`='$fid'");
		}
	}
	for( $i=0 ; $i<$n ; $i++){//n個朋友都要更新Si
		$point[$i][1] = $new[$i][1];
	}
}*/
/*$i = 0;
$social = mysql_query("select * from `social_score` where `id`='$id' order by `score` desc");
while($soci = mysql_fetch_array($social)){
	$fid = $soci['fid'];
	$i++;
	echo $i."_";
	echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a>_";
	echo $soci['score']."</br>";
}*/

/*$seprable = mysql_query("select `id` from `social_score` group by `id`");
while($sep = mysql_fetch_array($seprable)){
	$id = $sep['id'];
	echo "this one<a href=\"http://www.facebook.com/profile.php?id=".$id."\"><img src=\"http://graph.facebook.com/".$id."/picture\" /></a></br>";
	$social = mysql_query("select * from `social_score` where `id`='$id' order by `score` desc limit 20");
	while($soci = mysql_fetch_array($social)){
		$fid = $soci['fid'];
		echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a>_";
		echo $soci['score']."</br>";
	}
}*/


//情感辨認(自己打字)
/*if($_GET['word']!=null)
	$word=$_GET['word'];
echo "請輸入文字判斷情緒</br>";
echo "<form method='get'  action='123.php'>
		<input type='text' name='word' size='20'>
		<input type='submit' value='go' />
		</form>";
if($word!=null){
	echo $word."</br>";
	$emotion = mysql_query("select * from `emotion`");
	$n = 0;
	while($e = mysql_fetch_array($emotion)){
		$keyword="";
		$type[$n]=0;
		$temp = explode(",",$e['word']);
		for( $i=0 ; $i<sizeof($temp) ; $i++){
			if( substr_count($word,$temp[$i])>0 ){
				if($keyword==null)
					$keyword=$temp[$i];
				else
					$keyword=$keyword.",".$temp[$i];
				$type[$n]++;
			}
		}
		echo "emotion:".$e['type'].":".$type[$n]."次</br>".$keyword."</br>";
		$n++;
	}
}*/
//情感辨認(資料庫內動態)
/*$po = mysql_query("select `content` from `po` order by `time`");
while($p = mysql_fetch_array($po)){
	$word = $p['content'];
	if($word!=null){
		//echo "</br>".$word."</br>";
		$emotion = mysql_query("select * from `emotion`");
		$n = 0;
		while($e = mysql_fetch_array($emotion)){
			$keyword="";
			$type[$n]=0;
			$temp = explode(",",$e['word']);
			for( $i=0 ; $i<sizeof($temp) ; $i++){
				if( substr_count($word,$temp[$i])>0 ){
					if($keyword==null)
						$keyword=$temp[$i];
					else
						$keyword=$keyword.",".$temp[$i];
					$type[$n]++;
				}
			}
			if($type[$n]>=2){
				echo "emotion:".$e['type'].":".$type[$n]."次</br>".$keyword."</br>";
				$post=1;
			}
			if($n==8 && $post==1){
				echo $word."</br></br>";
				$post=0;
			}
			$n++;
		}
	}
}*/
//情感辨認(4種)
/*$temp0=array();
$temp1=array();
$temp2=array();
$temp3=array();
$emotion = mysql_query("select * from `emotion` where `group`='0'");
while($e = mysql_fetch_array($emotion)){
	$temptemp = explode(",",$e['word']);
	//print_r($temptemp);
	//echo "</br>";
	$temp0=array_merge($temp0,$temptemp);
}
//print_r($temp0);
$emotion = mysql_query("select * from `emotion` where `group`='1'");
while($e = mysql_fetch_array($emotion)){
	$temptemp = explode(",",$e['word']);
	//print_r($temptemp);
	//echo "</br>";
	$temp1=array_merge($temp1,$temptemp);
}
//print_r($temp1);
$emotion = mysql_query("select * from `emotion` where `group`='2'");
while($e = mysql_fetch_array($emotion)){
	$temptemp = explode(",",$e['word']);
	//print_r($temptemp);
	//echo "</br>";
	$temp2=array_merge($temp2,$temptemp);
}
//print_r($temp2);
$emotion = mysql_query("select * from `emotion` where `group`='3'");
while($e = mysql_fetch_array($emotion)){
	$temptemp = explode(",",$e['word']);
	//print_r($temptemp);
	//echo "</br>";
	$temp3=array_merge($temp3,$temptemp);
}
//print_r($temp3);
$po = mysql_query("select * from `po` order by `time` desc");
while($p = mysql_fetch_array($po)){
	$word = $p['content'];
	$poid = $p['po_id'];
	$type0 = 0;
	$type1 = 0;
	$type2 = 0;
	$type3 = 0;
	if($word!=null){
		for( $i=0 ; $i<sizeof($temp0) ; $i++ )
			$type0 = $type0 + substr_count($word,$temp0[$i]);
		for( $i=0 ; $i<sizeof($temp1) ; $i++ )
			$type1 = $type1 + substr_count($word,$temp1[$i]);
		for( $i=0 ; $i<sizeof($temp2) ; $i++ )
			$type2 = $type2 + substr_count($word,$temp2[$i]);
		for( $i=0 ; $i<sizeof($temp3) ; $i++ )
			$type3 = $type3 + substr_count($word,$temp3[$i]);
		if( $type0>1){
			echo $word."</br>".$type0." ".$type1." ".$type2." ".$type3."</br>";
			//mysql_query ("update `friend_wall` set `mood`='0' where `po_id`='$poid' and `content`='$word'");
		}
		else if( $type1>1 ){
			echo $word."</br>".$type0." ".$type1." ".$type2." ".$type3."</br>";
			//mysql_query ("update `friend_wall` set `mood`='1' where `po_id`='$poid' and `content`='$word'");
		}
		else if( $type2>1 ){
			echo $word."</br>".$type0." ".$type1." ".$type2." ".$type3."</br>";
			//mysql_query ("update `friend_wall` set `mood`='2' where `po_id`='$poid' and `content`='$word'");
		}
		else if( $type3>1 ){
			echo $word."</br>".$type0." ".$type1." ".$type2." ".$type3."</br>";
			//mysql_query ("update `friend_wall` set `mood`='3' where `po_id`='$poid' and `content`='$word'");
		}
	}
}*/
//我打卡誰,誰打卡我
/*$user_about = mysql_query("select `id` from `user_about`");
while($user = mysql_fetch_array($user_about)){
	$id = $user['id'];
	$friend = mysql_query("select `friend_id` from `associate` where `id`='$id'");
	while($f = mysql_fetch_array($friend)){
		$fid = $f['friend_id'];
		//$check_in = mysql_query("select * from `check_in` where `from`='$id' and `tag` like '%$fid%'");
		$check_in = mysql_query("select * from `check_in` where `from`='$fid' and `tag` like '%$id%'");
		if(mysql_num_rows( $check_in )>0){
			//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a>".$i."</br>";
			//mysql_query ("insert into `easy_check_in` values ('$id','$fid','0')");
			mysql_query ("insert into `easy_check_in` values ('$id','$fid','1')");
		}
	}
}*/

//我tag誰
/*$user_about = mysql_query("select `id` from `user_about`");
while($user = mysql_fetch_array($user_about)){
	$i=0;
	$pool=array();
	$id = $user['id'];
	//$id = '100000200225311';
	$comment = mysql_query("select * from `comment` where `from`='$id'");
	while($com = mysql_fetch_array($comment)){
		$tag = $com['tag'];
		$temp = explode(",",$tag);
		for( $j=0 ; $j<sizeof($temp) ;$j++){
			if( in_array($temp[$j],$pool)==false ){
				$pool[$i] = $temp[$j];
				$i++;
			}
		}
	}
	$picture = mysql_query("select * from `picture` where `from`='$id'");
	while($pic = mysql_fetch_array($picture)){
		$tag = $pic['tag'];
		$temp = explode(",",$tag);
		for( $j=0 ; $j<sizeof($temp) ;$j++){
			if( in_array($temp[$j],$pool)==false ){
				$pool[$i] = $temp[$j];
				$i++;
			}
		}
	}
	$po = mysql_query("select * from `po` where `from`='$id'");
	while($p = mysql_fetch_array($po)){
		$tag = $p['tag'];
		$temp = explode(",",$tag);
		for( $j=0 ; $j<sizeof($temp) ;$j++){
			if( in_array($temp[$j],$pool)==false ){
				$pool[$i] = $temp[$j];
				$i++;
			}
		}
	}
	//print_r($pool);
	echo "</br>";
	$friend = mysql_query("select `friend_id` from `associate` where `id`='$id'");
	while($f = mysql_fetch_array($friend)){
		$fid = $f['friend_id'];
		if( in_array($fid,$pool)){
			//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
			mysql_query ("insert into `easy_tag` values ('$id','$fid','0')");
		}
	}
}*/

//誰tag我
/*$user_about = mysql_query("select `id` from `user_about`");
while($user = mysql_fetch_array($user_about)){
	$i=0;
	$pool=array();
	$id = $user['id'];
	//$id = '100001173013348';
	$comment = mysql_query("select * from `comment` where `tag` like '%$id%'");
	while($com = mysql_fetch_array($comment)){
		$from = $com['from'];
		if( in_array($from,$pool)==false ){
			$pool[$i] = $from;
			$i++;
		}
	}
	$picture = mysql_query("select * from `picture` where `tag` like '%$id%'");
	while($pic = mysql_fetch_array($picture)){
		$from = $pic['from'];
		if( in_array($from,$pool)==false ){
			$pool[$i] = $from;
			$i++;
		}
	}
	$po = mysql_query("select * from `po` where `tag` like '%$id%'");
	while($p = mysql_fetch_array($po)){
		$from = $p['from'];
		if( in_array($from,$pool)==false ){
			$pool[$i] = $from;
			$i++;
		}
	}
	//print_r($pool);
	echo "</br>";
	$friend = mysql_query("select `friend_id` from `associate` where `id`='$id'");
	while($f = mysql_fetch_array($friend)){
		$fid = $f['friend_id'];
		if( in_array($fid,$pool)){
			//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
			mysql_query ("insert into `easy_tag` values ('$id','$fid','1')");
		}
	}
}*/

//我like誰
/*$user_about = mysql_query("select `id` from `user_about`");
while($user = mysql_fetch_array($user_about)){
	$i=0;
	$pool = array();
	$id = $user['id'];
	//$id = '100000200225311';
	$comment = mysql_query("select * from `comment` where `like` like '%$id%'");
	while($com = mysql_fetch_array($comment)){
		$from = $com['from'];
		if( in_array($from,$pool)==false ){
			$pool[$i] = $from;
			$i++;
		}
	}
	$picture = mysql_query("select * from `picture` where `like` like '%$id%'");
	while($pic = mysql_fetch_array($picture)){
		$from = $pic['from'];
		if( in_array($from,$pool)==false ){
			$pool[$i] = $from;
			$i++;
		}
	}
	$po = mysql_query("select * from `po` where `like` like '%$id%'");
	while($p = mysql_fetch_array($po)){
		$from = $p['from'];
		if( in_array($from,$pool)==false ){
			$pool[$i] = $from;
			$i++;
		}
	}
	//print_r($pool);
	$friend = mysql_query("select `friend_id` from `associate` where `id`='$id'");
	while($f = mysql_fetch_array($friend)){
		$fid = $f['friend_id'];
		if( in_array($fid,$pool)){
			//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
			mysql_query ("insert into `easy_like` values ('$id','$fid','0')");
		}
	}
}
//誰like我
$user_about = mysql_query("select `id` from `user_about`");
while($user = mysql_fetch_array($user_about)){
	$i=0;
	$pool = array();
	$id = $user['id'];
	//$id = '100000200225311';
	$comment = mysql_query("select * from `comment` where `from`='$id'");
	while($com = mysql_fetch_array($comment)){
		$like = $com['like'];
		$temp = explode(",",$like);
		for( $j=0 ; $j<sizeof($temp) ;$j++){
			if( in_array($temp[$j],$pool)==false ){
				$pool[$i] = $temp[$j];
				$i++;
			}
		}
	}
	$picture = mysql_query("select * from `picture` where `from`='$id'");
	while($pic = mysql_fetch_array($picture)){
		$like = $pic['like'];
		$temp = explode(",",$like);
		for( $j=0 ; $j<sizeof($temp) ;$j++){
			if( in_array($temp[$j],$pool)==false ){
				$pool[$i] = $temp[$j];
				$i++;
			}
		}
	}
	$po = mysql_query("select * from `po` where `from`='$id'");
	while($p = mysql_fetch_array($po)){
		$like = $p['like'];
		$temp = explode(",",$like);
		for( $j=0 ; $j<sizeof($temp) ;$j++){
			if( in_array($temp[$j],$pool)==false ){
				$pool[$i] = $temp[$j];
				$i++;
			}
		}
	}
	//print_r($pool);
	$friend = mysql_query("select `friend_id` from `associate` where `id`='$id'");
	while($f = mysql_fetch_array($friend)){
		$fid = $f['friend_id'];
		if( in_array($fid,$pool)){
			//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
			mysql_query ("insert into `easy_like` values ('$id','$fid','1')");
		}
	}
}*/

//我留言誰
/*$user_about = mysql_query("select `id` from `user_about`");
while($user = mysql_fetch_array($user_about)){
	$i=0;
	$pool = array();
	$id = $user['id'];
	//$id = '100000200225311';
	$comment = mysql_query("select * from `comment` where `from`='$id'");
	while($com = mysql_fetch_array($comment)){
		$to = $com['to'];
		if( in_array($to,$pool)==false ){
			$pool[$i] = $to;
			$i++;
		}
	}
	//print_r($pool);
	$friend = mysql_query("select `friend_id` from `associate` where `id`='$id'");
	while($f = mysql_fetch_array($friend)){
		$fid = $f['friend_id'];
		if( in_array($fid,$pool)){
			//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
			mysql_query ("insert into `easy_comment` values ('$id','$fid','0')");
		}
	}
}
//誰留言我
$user_about = mysql_query("select `id` from `user_about`");
while($user = mysql_fetch_array($user_about)){
	$i=0;
	$pool = array();
	$id = $user['id'];
	//$id = '100000200225311';
	$comment = mysql_query("select * from `comment` where `to`='$id'");
	while($com = mysql_fetch_array($comment)){
		$from = $com['from'];
		if( in_array($from,$pool)==false ){
			$pool[$i] = $from;
			$i++;
		}
	}
	//print_r($pool);
	$friend = mysql_query("select `friend_id` from `associate` where `id`='$id'");
	while($f = mysql_fetch_array($friend)){
		$fid = $f['friend_id'];
		if( in_array($fid,$pool)){
			//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
			mysql_query ("insert into `easy_comment` values ('$id','$fid','1')");
		}
	}
}*/
//我打卡誰,誰打卡我(20人)
/*$random_test = mysql_query("select * from `random_test`");
while($random = mysql_fetch_array($random_test)){
	$id = $random['id'];
	$fid = $random['friend_id'];
	$check_in = mysql_query("select * from `check_in` where `from`='$id' and `tag` like '%$fid%'");
	//$check_in = mysql_query("select * from `check_in` where `from`='$fid' and `tag` like '%$id%'");
	if(mysql_num_rows( $check_in )>0){
		//echo "<a href=\"http://www.facebook.com/profile.php?id=".$id."\"><img src=\"http://graph.facebook.com/".$id."/picture\" /></a>tag<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
		mysql_query ("insert into `20_check_in` values ('$id','$fid','0')");
		//mysql_query ("insert into `20_check_in` values ('$id','$fid','1')");
	}
}*/
//我tag誰(20人)
/*$random_test = mysql_query("select * from `random_test`");
while($random = mysql_fetch_array($random_test)){
	$i=0;
	$fid = $random['friend_id'];
	if($id != $random['id']){
		$pool=array();
		$id = $random['id'];
		//$id = '100000200225311';
		$comment = mysql_query("select * from `comment` where `from`='$id'");
		while($com = mysql_fetch_array($comment)){
			$tag = $com['tag'];
			$temp = explode(",",$tag);
			for( $j=0 ; $j<sizeof($temp) ;$j++){
				if( in_array($temp[$j],$pool)==false ){
					$pool[$i] = $temp[$j];
					$i++;
				}
			}
		}
		$picture = mysql_query("select * from `picture` where `from`='$id'");
		while($pic = mysql_fetch_array($picture)){
			$tag = $pic['tag'];
			$temp = explode(",",$tag);
			for( $j=0 ; $j<sizeof($temp) ;$j++){
				if( in_array($temp[$j],$pool)==false ){
					$pool[$i] = $temp[$j];
					$i++;
				}
			}
		}
		$po = mysql_query("select * from `po` where `from`='$id'");
		while($p = mysql_fetch_array($po)){
			$tag = $p['tag'];
			$temp = explode(",",$tag);
			for( $j=0 ; $j<sizeof($temp) ;$j++){
				if( in_array($temp[$j],$pool)==false ){
					$pool[$i] = $temp[$j];
					$i++;
				}
			}
		}
	}
	//print_r($pool);
	echo "</br>";
	if( in_array($fid,$pool)){
		//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
		mysql_query ("insert into `20_tag` values ('$id','$fid','0')");
	}
}*/
//誰tag我(20人)
/*$random_test = mysql_query("select * from `random_test`");
while($random = mysql_fetch_array($random_test)){
	$i=0;
	$fid = $random['friend_id'];
	if($id != $random['id']){
		$pool=array();
		$id = $random['id'];
		//$id = '100001173013348';
		$comment = mysql_query("select * from `comment` where `tag` like '%$id%'");
		while($com = mysql_fetch_array($comment)){
			$from = $com['from'];
			if( in_array($from,$pool)==false ){
				$pool[$i] = $from;
				$i++;
			}
		}
		$picture = mysql_query("select * from `picture` where `tag` like '%$id%'");
		while($pic = mysql_fetch_array($picture)){
			$from = $pic['from'];
			if( in_array($from,$pool)==false ){
				$pool[$i] = $from;
				$i++;
			}
		}
		$po = mysql_query("select * from `po` where `tag` like '%$id%'");
		while($p = mysql_fetch_array($po)){
			$from = $p['from'];
			if( in_array($from,$pool)==false ){
				$pool[$i] = $from;
				$i++;
			}
		}
	}
	//print_r($pool);
	echo "</br>";
	if( in_array($fid,$pool)){
		//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
		mysql_query ("insert into `20_tag` values ('$id','$fid','1')");
	}
}*/
//我like誰(20人)
/*$random_test = mysql_query("select * from `random_test`");
while($random = mysql_fetch_array($random_test)){
	$i=0;
	$fid = $random['friend_id'];
	if($id != $random['id']){
		$pool = array();
		$id = $random['id'];
		//$id = '100000200225311';
		$comment = mysql_query("select * from `comment` where `like` like '%$id%'");
		while($com = mysql_fetch_array($comment)){
			$from = $com['from'];
			if( in_array($from,$pool)==false ){
				$pool[$i] = $from;
				$i++;
			}
		}
		$picture = mysql_query("select * from `picture` where `like` like '%$id%'");
		while($pic = mysql_fetch_array($picture)){
			$from = $pic['from'];
			if( in_array($from,$pool)==false ){
				$pool[$i] = $from;
				$i++;
			}
		}
		$po = mysql_query("select * from `po` where `like` like '%$id%'");
		while($p = mysql_fetch_array($po)){
			$from = $p['from'];
			if( in_array($from,$pool)==false ){
				$pool[$i] = $from;
				$i++;
			}
		}
	}
	//print_r($pool);
	if( in_array($fid,$pool)){
		//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
		mysql_query ("insert into `20_like` values ('$id','$fid','0')");
	}
}*/
//誰like我(20人)
/*$random_test = mysql_query("select * from `random_test`");
while($random = mysql_fetch_array($random_test)){
	$i=0;
	$fid = $random['friend_id'];
	if($id != $random['id']){
		$pool = array();
		$id = $random['id'];
		//$id = '100000200225311';
		$comment = mysql_query("select * from `comment` where `from`='$id'");
		while($com = mysql_fetch_array($comment)){
			$like = $com['like'];
			$temp = explode(",",$like);
			for( $j=0 ; $j<sizeof($temp) ;$j++){
				if( in_array($temp[$j],$pool)==false ){
					$pool[$i] = $temp[$j];
					$i++;
				}
			}
		}
		$picture = mysql_query("select * from `picture` where `from`='$id'");
		while($pic = mysql_fetch_array($picture)){
			$like = $pic['like'];
			$temp = explode(",",$like);
			for( $j=0 ; $j<sizeof($temp) ;$j++){
				if( in_array($temp[$j],$pool)==false ){
					$pool[$i] = $temp[$j];
					$i++;
				}
			}
		}
		$po = mysql_query("select * from `po` where `from`='$id'");
		while($p = mysql_fetch_array($po)){
			$like = $p['like'];
			$temp = explode(",",$like);
			for( $j=0 ; $j<sizeof($temp) ;$j++){
				if( in_array($temp[$j],$pool)==false ){
					$pool[$i] = $temp[$j];
					$i++;
				}
			}
		}
	}
	//print_r($pool);
	if( in_array($fid,$pool)){
		//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
		mysql_query ("insert into `20_like` values ('$id','$fid','1')");
	}
}*/
//我留言誰
/*$random_test = mysql_query("select * from `random_test`");
while($random = mysql_fetch_array($random_test)){
	$i=0;
	$fid = $random['friend_id'];
	if($id != $random['id']){
		$pool = array();
		$id = $random['id'];
		//$id = '100000200225311';
		$comment = mysql_query("select * from `comment` where `from`='$id'");
		while($com = mysql_fetch_array($comment)){
			$to = $com['to'];
			if( in_array($to,$pool)==false ){
				$pool[$i] = $to;
				$i++;
			}
		}
	}
	//print_r($pool);
	if( in_array($fid,$pool)){
		//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
		mysql_query ("insert into `20_comment` values ('$id','$fid','0')");
	}
}
//誰留言我
$random_test = mysql_query("select * from `random_test`");
while($random = mysql_fetch_array($random_test)){
	$i=0;
	$fid = $random['friend_id'];
	if($id != $random['id']){
		$pool = array();
		$id = $random['id'];
		//$id = '100000200225311';
		$comment = mysql_query("select * from `comment` where `to`='$id'");
		while($com = mysql_fetch_array($comment)){
			$from = $com['from'];
			if( in_array($from,$pool)==false ){
				$pool[$i] = $from;
				$i++;
			}
		}
	}
	//print_r($pool);
	if( in_array($fid,$pool)){
		//echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a></br>";
		mysql_query ("insert into `20_comment` values ('$id','$fid','1')");
	}
}*/

//群組功能
/*$check_in = mysql_query("select * from `check_in` where `time`>'2013/08/01 00:00:00' and `tag`!=''");
$i=0;
while($check = mysql_fetch_array($check_in)){
	//echo $check['from'].$check['tag']."</br>";
	$from = $check['from'];
	$tag = $check['tag'];
	$temp[$i] = explode(",",$tag);
	$temp[$i][0] = $from;
	$i++;
}
//print_r($temp);//記錄所有打卡名單temp[第幾次打卡][名單]
$id = '100000200225311';
$friend = mysql_query("select `friend_id` from `associate` where `id`='$id'");
$i=0;
while($fr = mysql_fetch_array($friend)){
	$f[$i] = $fr['friend_id'];
	$i++;
}
//print_r($f);//紀錄我所有的好友
for( $i=0 ; $i<sizeof($temp) ; $i++){
	for( $j=0 ; $j<sizeof($temp[$i]) ; $j++){
		$tag = $temp[$i][$j];
		if(!in_array($tag,$f)){
			//unset($temp[$i][$j]);
			$temp[$i][$j]='';
		}
	}
}
print_r($temp);//記錄所有打卡名單temp[第幾次打卡][名單] (名單僅限朋友)
$row=0;
$br=0;
for( $i=0 ; $i<sizeof($temp) ; $i++){
	$col=0;
	for( $j=0 ; $j<sizeof($temp[$i]) ; $j++){	
		if($temp[$i][$j]!=''){
			//echo $temp[$i][$j].",";
			echo "<a href=\"http://www.facebook.com/profile.php?id=".$temp[$i][$j]."\"><img src=\"http://graph.facebook.com/".$temp[$i][$j]."/picture\" /></a></br>";
			$new_temp[$row][$col]=$temp[$i][$j];
			$col++;
			$br=1;
		}
	}
	if($br==1){
		echo $i."</br>";
		$br=0;
		if(sizeof($new_temp[$row])!=1)
			$row++;
	}
}
print_r($new_temp);*/

//群組功能
//$fid=$_POST[''];
/*$fid = '100000463989762';
$check_in = mysql_query("select * from `check_in` where `time`>'2013/08/01 00:00:00' and `tag`!=''");
while($check = mysql_fetch_array($check_in)){
	$from = $check['from'];
	$tag = $check['tag'];
	$temp = explode(",",$tag);
	if( $from == $fid ){//target是打卡者
		for( $i=0 ; $i<sizeof($temp) ; $i++)
			if( $temp[$i]!='' && !strstr($ans,$temp[$i]) )
				$ans = $ans.','.$temp[$i];
	}
	else if( in_array($fid,$temp) ){//target是被打卡者
		if( !strstr($ans,$from) )
			$ans = $ans.','.$from;
		for( $i=0 ; $i<sizeof($temp) ; $i++)
			if( $temp[$i]!='' && $temp[$i]!=$fid && !strstr($ans,$temp[$i]) )
				$ans = $ans.','.$temp[$i];
	}
}
echo substr($ans,1);*/

/*$i = 0;
$id = '100000200225311';
$fresult = mysql_query("select `friend_id` from `associate` where `id`=$id ");
while( $f =  mysql_fetch_array($fresult)){
	$friend[$i][0] = $f['friend_id'];
	$i++;
}
$presult = mysql_query("select * from `po` where `from`=$id ");
while( $p =  mysql_fetch_array($presult)){
	$ptemp = $p['like'];
	$plike = explode(",",$ptemp);
	$ptemp = $p['tag'];
	$ptag = explode(",",$ptemp);
	for( $i=0 ; $i<sizeof($friend) ; $i++){
		if(in_array( $friend[$i][0],$plike) )
			$friend[$i][1]++;
		if(in_array( $friend[$i][0],$ptag) )
			$friend[$i][2]++;
	}
}
$cresult = mysql_query("select `from`,`type` from `comment` where `to`=$id");
while( $c =  mysql_fetch_array($cresult)){
	if($c['type']=='feed'){
		for( $i=0 ; $i<sizeof($friend) ; $i++){
			if( $friend[$i][0]==$c['from'] ){
				$friend[$i][3]++;
				break;
			}
		}
	}
}

for( $i=0 ; $i<sizeof($friend) ; $i++){
	if(($friend[$i][2]+$friend[$i][3])!=0){
		echo "<a href=\"http://www.facebook.com/profile.php?id=".$friend[$i][0]."\"><img src=\"http://graph.facebook.com/".$friend[$i][0]."/picture\" /></a> ";
		echo " tag:".$friend[$i][2]." comm:".$friend[$i][3]." total:".($friend[$i][2]+$friend[$i][3])."次</br>";
	}
}*/


//二維陣列排序
/*$friend_id = array(
	array('手机','诺基亚',1050),
	array('笔记本电脑','lenovo',4300),
	array('剃须刀','飞利浦',3100),
	array('跑步机','三和松石',4900),
	array('手表','卡西欧',960),
	array('液晶电视','索尼',6299),
	array('激光打印机','惠普',1200)
);
$friend_id = array_sort($friend_id,'2','desc');
foreach($friend_id as $row)
		echo $row[2]."</br>";
print_r($friend_id);*/


//推薦好友
/*$nowtime = time();
$lasttime = $nowtime - 86400*90;
$lasttime = date("Y/m/d H:i:s",$lasttime);
$id = '100001173013348';
$i=0;
$fresult = mysql_query("select `friend_id` from `associate` where `id`=$id ");
while( $f =  mysql_fetch_array($fresult)){
	$friend = $friend.",".$f['friend_id'];
	$i++;
}
$result = mysql_query("select `tag`,`url`,`time`,`from` from `picture` where `tag` like '%$id%' or `from`='$id' order by `time` desc");
while( $r=mysql_fetch_array($result) ){//每張有自己的相片
	if($lasttime>$r['time'])
		break;
	$pictag = explode(",",$r['tag']);
	$picurl = $r['url'];
	$picfrom = $r['from'];
	if($picurl=='')
		continue;
	if( !strstr($friend,$picfrom) && !strstr($ans,$picfrom) && $picfrom!=$id){
		$ans = $ans.','.$picfrom;
		//echo "<a href=\"http://www.facebook.com/profile.php?id=$picfrom\"><img src=\"http://graph.facebook.com/$picfrom/picture\" /></a>";
		//echo "<img src=\"".$picurl."\" />";
		//echo $r['time']."</br>";
	}
	for($i=0 ; $i<sizeof($pictag) ; $i++){
		$temp = $pictag[$i];
		if($temp=='')
			continue;
		if( !strstr($friend,$temp) && !strstr($ans,$temp) && $temp!=$id){
			$ans = $ans.','.$temp;
			//echo "<a href=\"http://www.facebook.com/profile.php?id=$temp\"><img src=\"http://graph.facebook.com/$temp/picture\" /></a>";
			//echo "<img src=\"".$picurl."\" />";
			//echo $r['time']."</br>";
		}
	}
}
echo substr($ans,1);*/


//總相簿顯示(相簿封面+共同相簿)
/*$id = '100000200225311';
$i=0;
$fresult = mysql_query("select `friend_id` from `associate` where `id`=$id ");
while( $f =  mysql_fetch_array($fresult)){
	$friend = $friend.",".$f['friend_id'];
	$i++;
}
$result = mysql_query("select * from `album` where `from`='$id' order by `time` desc");
while( $r=mysql_fetch_array($result) ){//每本自己的相簿
	if($r['alb_name']=="Profile Pictures" || $r['alb_name']=="Timeline Photos" || $r['alb_name']=="Mobile Uploads" || $r['alb_name']=="Cover Photos" || $r['alb_name']=="iOS Photos")
		continue;
	$albtime = $r['time'];
	$my_albid = $r['alb_id'];
	$ableecho = 1;//為了輸出的控制變數
	$mytag='';//取得這本相簿中相片tag的人
	$picture = mysql_query("select * from `picture` where `alb_id`='$my_albid' ");
	while( $p=mysql_fetch_array($picture) ){
		if($ableecho==1 && $p['url']!=''){//為了輸出
			$ableecho = 0;
			$url = $p['url'];
			$url = str_replace("/s75x225","",$url);
			$url = str_replace("s.jpg","a.jpg",$url);
			echo $r['alb_name'].",".$albtime.",".$url.",".$my_albid;
			//echo "<img src=\"".$url."\" /></br>";
		}
		$mytag = $mytag.",".$p['tag'];
	}
	$albtime = substr($r['time'],0,10);//只取年月日
	
	$mutal = mysql_query("select * from `album` where `time` like '$albtime%' ");
	while( $m=mysql_fetch_array($mutal) ){//同一天的相簿
		if($m['alb_name']=="Profile Pictures" || $m['alb_name']=="Timeline Photos" || $m['alb_name']=="Mobile Uploads" || $m['alb_name']=="Cover Photos" || $m['alb_name']=="iOS Photos")
			continue;
		$from = $m['from'];
		if( strstr($friend,$from) ){//自己朋友同一天的相簿
			$albid = $m['alb_id'];
			$mutaltag = mysql_query("select `tag` from `picture` where `alb_id`='$albid' ");
			while( $tag=mysql_fetch_array($mutaltag) ){//自己朋友同一天+tag同樣人的相簿
				$ftag = explode(",",$tag['tag']);
				for($i=0 ; $i<sizeof($ftag) ; $i++){
					$temp = $ftag[$i];
					if($temp=='')
						continue;
					if( strstr($mytag,$temp) ){
						echo ".".$m['alb_id'].".".$soci['fname'];
						//echo "<a href=\"http://www.facebook.com/profile.php?id=".$from."\"><img src=\"http://graph.facebook.com/".$from."/picture\" /></a></br> ";
						break 2;
					}
				}
			}
		}
	}
	echo ";";
}*/

//顯示相簿內相片
/*$albid_str = '316320411717964.197278813681630';
$albid = explode(".",$albid_str);
for( $i=0 ; $i<sizeof($albid) ; $i++){//自己的相簿+共同相簿
	$temp = $albid[$i];
	$picture = mysql_query("select * from `picture` where `alb_id`='$temp' ");
	while( $p=mysql_fetch_array($picture) ){
		$url = $p['url'];
		$fid = $p['from'];
		$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
		$soci = mysql_fetch_array($social);		
		if($url=='')
			continue;
		$url = str_replace("/s75x225","",$url);
		$url = str_replace("s.jpg","a.jpg",$url);
		$ans = $ans.",".$url;
	
		//echo "<img src=\"".$url."\" />".$soci['fname']"</br>";
	}
}
echo substr($ans,1);*/

//改相片size
/*$id = '100000200225311';
$picture = mysql_query("select * from `picture` where `from`='$id' ");
while( $p=mysql_fetch_array($picture) ){
	$url = $p['url'];
	if($url=='')
		continue;
	echo "<img src=\"".$url."\" />--->";
	$url = str_replace("/s75x225","",$url);
	$url = str_replace("s.jpg","a.jpg",$url);
	echo "<img src=\"".$url."\" /></br>";
}*/

//統計(決定分數)
/*$time = '2013/08/01 13:05:21';
$time = strtotime($time);
$time = $time -86400*14;
$time = date("Y/m/d H:i:s",$time);
$po = mysql_query("select * from `po` where `time`>'$time' order by `time` desc");
$picture = mysql_query("select * from `picture` where `time`>'$time' order by `time` desc");
$comment = mysql_query("select * from `comment` where `time`>'$time' order by `time` desc");
$user = mysql_query("select `id`,`time` from `user_about` order by `time` desc");
while( $u=mysql_fetch_array($user) ){
	$id = $u['id'];
	$time = strtotime($u['time']);
	$time = $time -86400*14;
	$time = date("Y/m/d H:i:s",$time);
	$po_other = 0;
	$com_other = 0;
	$like_other = 0;
	while( $p=mysql_fetch_array($po) ){
		if($p['time']>$time && $p['time']<$u['time']){
			$from = $p['from'];
			$poid = explode("_",$p['po_id']);
			$polike = $p['like'];
			if( $from==$id && $from!=$poid[0] )//po文在別人牆上
				$po_other++;
			if( $from!=$id && strstr($polike,$id) )//點別人po文讚
				$like_other++;
		}
	}
	while( $p=mysql_fetch_array($picture) ){
		if($p['time']>$time && $p['time']<$u['time']){
			$from = $p['from'];
			$piclike = $p['like'];
			if( $from!=$id && strstr($piclike,$id) )//點別人相片讚
				$like_other++;
		}
	}
	while( $com=mysql_fetch_array($comment) ){
		if($com['time']>$time && $com['time']<$u['time']){
			$from = $com['from'];
			$to = $com['to'];
			$comlike = $com['like'];
			if( $from==$id && $to!=$id )//留言在別人po文or相片上
				$com_other++;
			if( $from!=$id && strstr($comlike,$id) )//點別人留言讚
				$like_other++;
		}
	}
	$score = $po_other*0.4 + $com_other*0.5 + $like_other*0.1;
	mysql_data_seek($po,0);
	mysql_data_seek($picture,0);
	mysql_data_seek($comment,0);
	echo "<a href=\"http://www.facebook.com/profile.php?id=".$id."\"><img src=\"http://graph.facebook.com/".$id."/picture\" /></a>";
	echo "time=".$time.",po=".$po_other.",com=".$com_other.",like=".$like_other.",score=".$score."</br>";
	$result = mysql_query("select * from `tree_size` where `id`='$id'");
	$row = mysql_fetch_array($result);
	if($row['id']==null)
		mysql_query ("insert into `tree_size` values ('$id','$po_other','$com_other','$like_other','$score','0')");
	else
		mysql_query ("update `tree_size` set `po`='$po_other',`comment`='$com_other',`like`='$like_other',`score`='$score' where `id`='$id'");
}
//統計(決定level)
$level = mysql_query("select * from `tree_size` order by `score` asc");
$num = mysql_num_rows($level)+1;
$i = 0;
while( $lv=mysql_fetch_array($level) ){
	$i++;
	$id = $lv['id'];
	if( $i<$num*0.2 )
		$llv = 1;
	else if( $i<$num*0.4 )
		$llv = 2;
	else if( $i<$num*0.6 )
		$llv = 3;
	else if( $i<$num*0.8 )
		$llv = 4;
	else
		$llv = 5;
	mysql_query ("update `tree_size` set `level`='$llv' where `id`='$id'");
	echo "<a href=\"http://www.facebook.com/profile.php?id=".$id."\"><img src=\"http://graph.facebook.com/".$id."/picture\" /></a>";
	echo $llv."</br>";
}*/

//關心好友
/*$i=0;
$comment = mysql_query("SELECT `type_id`,count(*) as `num` FROM `comment` where `type`='feed' group by `type_id`");
$po = mysql_query("select * from `po` order by `time` desc");
while( $p=mysql_fetch_array($po) ){
	$like = explode(",",$p['like']);
	$likenum = sizeof($like);
	$poid = $p['po_id'];
	while( $com=mysql_fetch_array($comment) ){
		if( $com['type_id']==$poid ){
			$comnum = $comnum + $com['num'];
			break;
		}	
	}
	$test[$i] = $likenum + $comnum;
	$comnum = 0;
	mysql_data_seek($comment,0);
	$i++;
}
$friend_wall = mysql_query("select * from `friend_wall` where `content` not like '%生日%' order by `time` desc");
while( $wall=mysql_fetch_array($friend_wall) ){
	$test[$i] = $wall['like_num'] + $wall['comment_num'];
	$i++;
}
sort($test,SORT_NUMERIC);
$num = sizeof($test);
echo $num."</br>";
echo $test[floor(0.2*($num+1))-1]."</br>";
echo $test[floor(0.4*($num+1))-1]."</br>";
echo $test[floor(0.6*($num+1))-1]."</br>";
echo $test[floor(0.8*($num+1))-1]."</br>";*/

//去除系統訊息
/*$data = fopen("delete.txt", "r");
while (!feof ($data)) {
	$line = fgets($data);
	$line = str_replace("SELECT `content`","delete",$line);
	$line = str_replace("`po`","`ppo`",$line);
	mysql_query($line);
	echo $line."</br>";
}
fclose ($data);*/
/*$posts_message=0;
if( !strstr($posts_message,'is using') && !strstr($posts_message,' used ') && !strstr($posts_message,'is now') && !strstr($posts_message,'are now') && !strstr($posts_message,' played ') && !strstr($posts_message,' playing ') && !strstr($posts_message,'on his own') && !strstr($posts_message,'on her own') && !strstr($posts_message,'went to') && !strstr($posts_message,'going to') && !strstr($posts_message,' likes ') && !strstr($posts_message,' was ') && !strstr($posts_message,' shared ') && !strstr($posts_message,' updated ') && !strstr($posts_message,' added ') && !strstr($posts_message,' tagged ') && !strstr($posts_message,' changed ') && !strstr($posts_message,' got ') && !strstr($posts_message,' uploaded ') && !strstr($posts_message,' created ') && !strstr($posts_message,' posted ') && !strstr($posts_message,' listed ') && !strstr($posts_message,' rated ') && !strstr($posts_message,' commented ') && !strstr($posts_message,' activated ') && !strstr($posts_message,' joined ') )
	if( strstr($posts_message,'.') ){
	}*/

//挽救資料庫
/*$i = 1;
$data = fopen("po.txt", "r");
while (!feof ($data)) {
	$line = fgets($data);
	//$temp = explode("︿",$line);
	$from = $temp[0];
	$po_id = $temp[1];
	$time = $temp[2];
	$place = $temp[3];
	$tag = $temp[4];
	$like = $temp[5];
	$content = $temp[6];
	//$line = "insert into ppo values('$from','$po_id','$time','$place','$tag','$like','$content')";
	//"insert into tb2 values ('0','0','0','0','0','0')"
	echo $i."  ".$line."</br>";
	$i++;
}
fclose ($data);*/
/*$result = mysql_query("select * from `ppo`");
while($r=mysql_fetch_array($result)){
	$condition = $r['po_id'];
	$from = str_replace(",","",$r['from']);
	$po_id = str_replace(",","",$r['po_id']);
	$time = str_replace(",","",$r['time']);
	$place = str_replace(",","",$r['place']);
	mysql_query ("update `ppo` set `from`='$from',`po_id`='$po_id',`time`='$time',`place`='$place' where `po_id`='$condition'");
}*/

//被點讚(po,com,pic)
/*$time = time();
$time = $time - 86400*3;
$time = date("Y/m/d H:i:s",$time);
$uid='100000200225311';
$event = 'like';
$type = 'po';
$content = '一張照片';
$po = mysql_query("select * from `ppo` where `from`='$uid' order by `time` desc");
while($p=mysql_fetch_array($po)){
	if( $p['time']<$time )
		break;
	$like = explode(",",$p['like']);
	for($i=0 ; $i<sizeof($like) ; $i++){
		if( strstr($like[$i],'.') ){
			$like[$i] = substr($like[$i],1);
			echo $like[$i]."_".$event."_".$type."_".$p['po_id']."_".$p['content']."</br>";
		}
	}
}
$type = 'com';
$comment = mysql_query("select * from `comment` where `from`='$uid' order by `time` desc");
while($com=mysql_fetch_array($comment)){
	if( $com['time']<$time )
		break;
	$like = explode(",",$com['like']);
	for($i=0 ; $i<sizeof($like) ; $i++){
		if( strstr($like[$i],'.') ){
			$like[$i] = substr($like[$i],1);
			echo $like[$i]."_".$event."_".$type."_".$com['comment_id']."_".$com['content']."</br>";
		}
	}
}
$type = 'pic';
$picture = mysql_query("select * from `picture` where `from`='$uid' order by `time` desc");
while($pic=mysql_fetch_array($picture)){
	if( $pic['time']<$time )
		break;
	$like = explode(",",$pic['like']);
	for($i=0 ; $i<sizeof($like) ; $i++){
		if( strstr($like[$i],'.') ){
			$like[$i] = substr($like[$i],1);
			echo $like[$i]."_".$event."_".$type."_".$pic['pic_id']."_".$content."</br>";
		}
	}
}
//被留言(com)
$event = 'com';
$comment = mysql_query("select * from `comment_sup` where `to`='$uid' order by `time` desc");
while($comto=mysql_fetch_array($comment)){
	if( $comto['time']<$time )
		break;
	if($comto['type']=='feed')
		$type = 'po';
	else if($comto['type']=='picture')
		$type = 'pic';
	else
		continue;
	if($comto['unseen']==1){
		$comid = $comto['comment_id'];
		$commenttt = mysql_query("select * from `comment` where `comment_id`='$comid' ");
		$com = mysql_fetch_array($commenttt);
		echo $comto['from']."_".$event."_".$type."_".$comto['type_id']."_".$com['content']."</br>";
	}
}

//被tag(po,pic,com,checkin)
$event = 'tag';
$type = 'po';
$po = mysql_query("select * from `ppo` where `tag` like '%.$uid%' group by `content` order by `time` desc");
while($p=mysql_fetch_array($po)){
	if( $p['time']<$time )
		break;
	echo $p['from']."_".$event."_".$type."_".$p['po_id']."_".$p['content']."</br>";
}
$type = 'pic';
$content = '一張照片';
$picture = mysql_query("select * from `picture` where `tag` like '%.$uid%' order by `time` desc");
while($pic=mysql_fetch_array($picture)){
	if( $pic['time']<$time )
		break;
	echo $pic['from']."_".$event."_".$type."_".$pic['pic_id']."_".$content."</br>";
}
$type = 'com';
$comment = mysql_query("select * from `comment` where `tag` like '%.$uid%' order by `time` desc");
while($com=mysql_fetch_array($comment)){
	if( $com['time']<$time )
		break;
	echo $com['from']."_".$event."_".$type."_".$com['type_id']."_".$com['content']."</br>";
}
$type = 'checkin';
$content = '一個地點';
$checkin = mysql_query("select * from `check_in` where `tag` like '%.$uid%' order by `time` desc");
while($check=mysql_fetch_array($checkin)){
	if( $check['time']<$time )
		break;
	echo $check['from']."_".$event."_".$type."_".$check['check_id']."_".$content."</br>";
}

//被私訊(message)
$event = 'msg';
$type = 'null';
$content = '訊息';
$message = mysql_query("select * from `message_less` where `id`='$uid' and `unseen`='1' ");
while($msg=mysql_fetch_array($message)){
	echo $msg['fid']."_".$event."_".$type."_".$msg['msg_id']."_".$content."</br>";
}*/
/*$uid = '100001303371482';
$fid = '100001173013348';
$event = 'tag';
$type = 'com';
$type_id = '100001303371482_605193812867392';
if( $event=='like' ){
	if( $type=='po' ){
		$po = mysql_query("select * from `ppo` where `po_id`='$type_id'");
		$p = mysql_fetch_array($po);
		$temp = ".".$fid;
		$like = $p['like'];
		$like = str_replace($temp,$fid,$like);//未讀改已讀
		mysql_query("update `ppo` set `like`='$like' where `po_id`='$type_id' limit 1");
	}
	else if( $type=='com' ){
		$comment = mysql_query("select * from `comment` where `comment_id`='$type_id'");
		$com = mysql_fetch_array($comment);
		$temp = ".".$fid;
		$like = $com['like'];
		$like = str_replace($temp,$fid,$like);//未讀改已讀
		mysql_query("update `comment` set `like`='$like' where `comment_id`='$type_id' limit 1");
	}
	else if( $type=='pic' ){
		$picture = mysql_query("select * from `picture` where `pic_id`='$type_id'");
		$pic = mysql_fetch_array($picture);
		$temp = ".".$fid;
		$like = $pic['like'];
		$like = str_replace($temp,$fid,$like);//未讀改已讀
		mysql_query("update `picture` set `like`='$like' where `pic_id`='$type_id' limit 1");
	}
}
else if( $event=='com' ){
	mysql_query("update `comment_sup` set `unseen`='0' where `from`='$fid' and `to`='$uid' and `type_id`='$type_id'");
}
else if( $event=='tag' ){
	if( $type=='po' ){
		$po = mysql_query("select * from `ppo` where `po_id`='$type_id'");
		$p = mysql_fetch_array($po);
		$temp = ".".$uid;
		$tag = $p['tag'];
		$tag = str_replace($temp,$uid,$tag);//未讀改已讀
		mysql_query("update `ppo` set `tag`='$tag' where `po_id`='$type_id'");
	}
	else if( $type=='com' ){
		$comment = mysql_query("select * from `comment` where `type_id`='$type_id'");
		$com = mysql_fetch_array($comment);
		$temp = ".".$uid;
		$tag = $com['tag'];
		$tag = str_replace($temp,$uid,$tag);//未讀改已讀
		mysql_query("update `comment` set `tag`='$tag' where `from`='$fid' and `type_id`='$type_id'");
	}
	else if( $type=='pic' ){
		$picture = mysql_query("select * from `picture` where `pic_id`='$type_id'");
		$pic = mysql_fetch_array($picture);
		$temp = ".".$uid;
		$tag = $pic['tag'];
		$tag = str_replace($temp,$uid,$tag);//未讀改已讀
		mysql_query("update `picture` set `tag`='$tag' where `pic_id`='$type_id'");
	}
	else if( $type=='checkin' ){
		$checkin = mysql_query("select * from `check_in` where `check_id`='$type_id'");
		$check = mysql_fetch_array($checkin);
		$temp = ".".$uid;
		$tag = $check['tag'];
		$tag = str_replace($temp,$uid,$tag);//未讀改已讀
		mysql_query("update `check_in` set `tag`='$tag' where `check_id`='$type_id'");
	}
}
else if( $event=='msg' ){
	mysql_query("update `message_less` set `unseen`='0' where `id`='$uid' and `fid`='$fid'");
}*/

//歷史被點讚(po,com,pic)
$time = time();
$time = $time - 86400*3;
$time = date("Y/m/d H:i:s",$time);
$uid='100001303371482';
/*$event = 'like';
$type = 'po';
$content = '一張照片';
$like_notice = array( array(0,0,0,0,0,0) );
$n = 0;
$po = mysql_query("select * from `ppo` where `from`='$uid' group by `content` order by `time` desc");
while($p=mysql_fetch_array($po)){
	if( $p['time']<$time || $n>50 )
		break;
	$like = explode(",",$p['like']);
	for($i=0 ; $i<sizeof($like) ; $i++){
		if( strstr($like[$i],'.') ){
			$like[$i] = substr($like[$i],1);
			$fid = $like[$i];
			$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
			$soci = mysql_fetch_array($social);	
			echo $soci['fname']."_".$event."_".$type."_".$p['po_id']."_".$p['content']."</br>";
			$n++;
		}
	}
}
if( mysql_num_rows( $po )>0)
	mysql_data_seek($po,0);
$j = 0;
while($p=mysql_fetch_array($po)){
	if( $j>=30 )
		break;
	$like = explode(",",$p['like']);
	for($i=0 ; $i<sizeof($like) ; $i++){
		if( (!strstr($like[$i],'.') && $like[$i]!='') || (strstr($like[$i],'.') ) ){
			$like_notice[$j][0] = sizeof($like);
			$like_notice[$j][1] = $event;
			$like_notice[$j][2] = $type;
			$like_notice[$j][3] = $p['po_id'];
			$like_notice[$j][4] = $p['content'];
			$like_notice[$j][5] = $p['time'];
			$j++;
			break;
		}
	}
	$p['like'] = ;
}
$type = 'com';
$comment = mysql_query("select * from `comment` where `from`='$uid'  group by `content` order by `time` desc");
while($com=mysql_fetch_array($comment)){
	if( $com['time']<$time || $n>50 )
		break;
	$like = explode(",",$com['like']);
	for($i=0 ; $i<sizeof($like) ; $i++){
		if( strstr($like[$i],'.') ){
			$like[$i] = substr($like[$i],1);
			$fid = $like[$i];
			$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
			$soci = mysql_fetch_array($social);	
			echo $soci['fname']."_".$event."_".$type."_".$com['comment_id']."_".$com['content']."</br>";
			$n++;
		}
	}
}
if( mysql_num_rows( $comment )>0)
	mysql_data_seek($comment,0);
while($com=mysql_fetch_array($comment)){
	if( $j>=60 )
		break;
	$like = explode(",",$com['like']);
	for($i=0 ; $i<sizeof($like) ; $i++){
		if( (!strstr($like[$i],'.') && $like[$i]!='') || (strstr($like[$i],'.') ) ){
			$like_notice[$j][0] = sizeof($like);
			$like_notice[$j][1] = $event;
			$like_notice[$j][2] = $type;
			$like_notice[$j][3] = $com['comment_id'];
			$like_notice[$j][4] = $com['content'];
			$like_notice[$j][5] = $com['time'];
			$j++;
			break;
		}
	}
}	
$type = 'pic';
$picture = mysql_query("select * from `picture` where `from`='$uid' order by `time` desc");
while($pic=mysql_fetch_array($picture)){
	if( $pic['time']<$time || $n>50 )
		break;
	$like = explode(",",$pic['like']);
	for($i=0 ; $i<sizeof($like) ; $i++){
		if( strstr($like[$i],'.') ){
			$like[$i] = substr($like[$i],1);
			$fid = $like[$i];
			$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
			$soci = mysql_fetch_array($social);
			echo $soci['fname']."_".$event."_".$type."_".$pic['pic_id']."_".$content."</br>";
			$n++;
		}
	}
}
if( mysql_num_rows( $picture )>0)
	mysql_data_seek($picture,0);
while($pic=mysql_fetch_array($picture)){
	if( $j>=90 )
		break;
	$like = explode(",",$pic['like']);
	for($i=0 ; $i<sizeof($like) ; $i++){
		if( (!strstr($like[$i],'.') && $like[$i]!='') || (strstr($like[$i],'.') )){
			$like_notice[$j][0] = sizeof($like);
			$like_notice[$j][1] = $event;
			$like_notice[$j][2] = $type;
			$like_notice[$j][3] = $pic['pic_id'];
			$like_notice[$j][4] = $content;
			$like_notice[$j][5] = $pic['time'];
			$j++;
			break;
		}
	}
}
echo "</br>";

$like_notice = array_sort($like_notice,'5','desc');
foreach($like_notice as $row){
	if($n>=50)
		break;
	echo $row[0]."_".$row[1]."_".$row[2]."_".$row[3]."_".$row[4]."_".$row[5]."</br>";
	$n++;
}*/

//歷史被留言(com)
/*$event = 'com';
$n = 0;
$comment = mysql_query("select * from `comment_sup` where `to`='$uid' order by `time` desc");
while($comto=mysql_fetch_array($comment)){
	if( $comto['time']<$time || $n>50)
		break;
	if($comto['type']=='feed')
		$type = 'po';
	else if($comto['type']=='picture')
		$type = 'pic';
	else
		continue;
	if($comto['unseen']==1){
		$fid = $comto['from'];
		$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
		$soci = mysql_fetch_array($social);
		$comid = $comto['comment_id'];
		$commenttt = mysql_query("select * from `comment` where `comment_id`='$comid' ");
		$com = mysql_fetch_array($commenttt);
		echo $soci['fname']."_".$event."_".$type."_".$comto['type_id']."_".$com['content']."</br>";
		$n++;
	}
}
if( mysql_num_rows( $comment )>0)
	mysql_data_seek($comment,0);
while($comto=mysql_fetch_array($comment)){
	if($n>50)
		break;
	if($comto['type']=='feed')
		$type = 'po';
	else if($comto['type']=='picture')
		$type = 'pic';
	else
		continue;
	if($comto['unseen']==0 || ($comto['unseen']==1 && $comto['time']<$time) ){
		$fid = $comto['from'];
		$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
		$soci = mysql_fetch_array($social);
		$comid = $comto['comment_id'];
		$commenttt = mysql_query("select * from `comment` where `comment_id`='$comid' ");
		$com = mysql_fetch_array($commenttt);
		echo $soci['fname']."_".$event."_".$type."_".$comto['type_id']."_".$com['content']."</br>";
		$n++;
	}
}*/

//歷史被tag(po,pic,com,checkin)
$event = 'tag';
$type = 'po';
$tag_notice = array( array(0,0,0,0,0,0) );
$n = 0;
$po = mysql_query("select * from `ppo` where `tag` like '%.$uid%' group by `content` order by `time` desc");
while($p=mysql_fetch_array($po)){
	if( $p['time']<$time || $n>50)
		break;
	$fid = $p['from'];
	$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
	$soci = mysql_fetch_array($social);
	echo $soci['fname']."_".$event."_".$type."_".$p['po_id']."_".$p['content']."</br>";
	$n++;
}
$po = mysql_query("select * from `ppo` where `tag` like '%$uid%' group by `content` order by `time` desc");
$j = 0;
while($p=mysql_fetch_array($po)){
	if( $j>=30 )
		break;
	if( $p['time'] < $time ){
		$fid = $p['from'];
		$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
		$soci = mysql_fetch_array($social);
		$tag_notice[$j][0] = $soci['fname'];
		$tag_notice[$j][1] = $event;
		$tag_notice[$j][2] = $type;
		$tag_notice[$j][3] = $p['po_id'];
		$tag_notice[$j][4] = $p['content'];
		$tag_notice[$j][5] = $p['time'];
		$j++;
	}
}
$type = 'pic';
$content = '一張照片';
$picture = mysql_query("select * from `picture` where `tag` like '%.$uid%' order by `time` desc");
while($pic=mysql_fetch_array($picture)){
	if( $pic['time']<$time || $n>50 )
		break;
	$fid = $pic['from'];
	$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
	$soci = mysql_fetch_array($social);
	echo $soci['fname']."_".$event."_".$type."_".$pic['pic_id']."_".$content."</br>";
	$n++;
}
$picture = mysql_query("select * from `picture` where `tag` like '%$uid%' order by `time` desc");
while($pic=mysql_fetch_array($picture)){
	if( $j>=60 )
		break;
	if( $pic['time'] < $time ){
		$fid = $pic['from'];
		$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
		$soci = mysql_fetch_array($social);
		if($soci['fname']=='')
			continue;
		$tag_notice[$j][0] = $soci['fname'];
		$tag_notice[$j][1] = $event;
		$tag_notice[$j][2] = $type;
		$tag_notice[$j][3] = $pic['pic_id'];
		$tag_notice[$j][4] = $content;
		$tag_notice[$j][5] = $pic['time'];
		$j++;
	}
}
$type = 'com';
$comment = mysql_query("select * from `comment` where `tag` like '%.$uid%' order by `time` desc");
while($com=mysql_fetch_array($comment)){
	if( $com['time']<$time || $n>50 )
		break;
	$fid = $com['from'];
	$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
	$soci = mysql_fetch_array($social);
	echo $soci['fname']."_".$event."_".$type."_".$com['type_id']."_".$com['content']."</br>";
	$n++;
}
$comment = mysql_query("select * from `comment` where `tag` like '%$uid%' order by `time` desc");
while($com=mysql_fetch_array($comment)){
	if( $j>=90 )
		break;
	if( $com['time'] < $time ){
		$fid = $com['from'];
		$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
		$soci = mysql_fetch_array($social);
		$tag_notice[$j][0] = $soci['fname'];
		$tag_notice[$j][1] = $event;
		$tag_notice[$j][2] = $type;
		$tag_notice[$j][3] = $com['comment_id'];
		$tag_notice[$j][4] = $com['content'];
		$tag_notice[$j][5] = $com['time'];
		$j++;
	}
}
$type = 'checkin';
$content = '一個地點';
$checkin = mysql_query("select * from `check_in` where `tag` like '%.$uid%' order by `time` desc");
while($check=mysql_fetch_array($checkin)){
	if( $check['time']<$time || $n>50 )
		break;
	$fid = $check['from'];
	$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
	$soci = mysql_fetch_array($social);
	echo $soci['fname']."_".$event."_".$type."_".$check['check_id']."_".$content."</br>";
	$n++;
}
$checkin = mysql_query("select * from `check_in` where `tag` like '%$uid%' order by `time` desc");
while($check=mysql_fetch_array($checkin)){
	if( $j>=120 )
		break;
	if( $check['time'] < $time ){
		$fid = $check['from'];
		$social = mysql_query("select * from `social_score` where `fid`='$fid' ");
		$soci = mysql_fetch_array($social);
		$tag_notice[$j][0] = $soci['fname'];
		$tag_notice[$j][1] = $event;
		$tag_notice[$j][2] = $type;
		$tag_notice[$j][3] = $check['check_id'];
		$tag_notice[$j][4] = $content;
		$tag_notice[$j][5] = $check['time'];
		$j++;
	}
}
$tag_notice = array_sort($tag_notice,'5','desc');
foreach($tag_notice as $row){
	if($n>=50)
		break;
	echo $row[0]."_".$row[1]."_".$row[2]."_".$row[3]."_".$row[4]."_".$row[5]."</br>";
	$n++;
}

function array_sort($arr,$keys,$type='asc'){
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v)
		$keysvalue[$k] = $v[$keys];
	if($type == 'asc')
		asort($keysvalue);
	else
		arsort($keysvalue);
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v)
		$new_array[$k] = $arr[$k];
	return $new_array;
}
?>