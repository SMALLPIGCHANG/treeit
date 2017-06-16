<?php
ignore_user_abort(true);
set_time_limit(0);
require("php_sdk/src/facebook.php");
$config = array();
	$config['appId'] = '460055537422881';
	$config['secret'] = '8da37510651fb4444984e1c02f8c5979';
	$config['fileUpload'] = true; // optional
$facebook = new Facebook($config);
$login_url = $facebook->getLoginUrl(
							array(
								'canvas' => 1,
								'fbconnect' => 1,
								'scope' => 'email,user_activities,user_questions,user_videos,
											user_events,user_birthday,user_relationships,
											publish_actions,user_games_activity,
											user_interests,user_notes,user_relationship_details,
											user_status,user_groups,user_likes,user_photos,friends_groups,
											friends_likes,friends_status,friends_photos,user_about_me,read_stream,read_mailbox,
											read_friendlists,user_website',
								'next' => 'http://apps.facebook.com/bookroyt/',
								'redirect_uri' => 'http://apps.facebook.com/bookroyt/'
								));
$user_id = $facebook->getUser();
$accessToken = $facebook->getAccessToken();
$facebook->setAccessToken($accessToken);
if($user_id) 
{
	try{
		$user_about_me =  $facebook->api('/me');
		$id = $user_about_me[id];
	}
	catch(FacebookApiException $e){
		echo '<a href="'.$login_url.'" target="_top">第一次使用程式</a>';
    }
}
else{
    echo '<a href="'.$login_url.'" target="_top">第一次使用程式</a>';
}
//連接資料庫
$dbhost = 'localhost';
$dbuser = 'b9929061';
$dbpass = 'b9929061';
$dbname = 'b9929061';
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
mysql_select_db($dbname);
mysql_query("SET NAMES 'utf8'");

$now_time = time();

if($id != null){
$user_about_me =  $facebook->api('/me');
$id = $user_about_me[id];
$check = mysql_query("SELECT * FROM `social_score` WHERE `id`='$id' limit 1");//是否存在於user_about
$row = mysql_fetch_array($check);
if($row['id'])
	$new = 0;
else
	$new = 1;
if($new == 1){
	$fri = $facebook ->api('/me?fields=friends.limit(1000)');
	$friend_i = 0;
	while($fri[friends][data][$friend_i] != null)//朋友封包
	{
		$friend_id[$friend_i][0] = $fri[friends][data][$friend_i][id];
		$store_id = $fri[friends][data][$friend_i][id];
		$store_name = $fri[friends][data][$friend_i][name];
		
		$result = mysql_query("select * from `social_score` where `id`='$id' and `fid`='$store_id'");
		$row = mysql_fetch_array($result);
		if($row['id']==null)
			mysql_query ("insert into `social_score` values ('$id','$store_id','0','$store_name')");
		$friend_i++;
	}
	//$friend_id[$i][0] -> 朋友ID , $friend_id[$i][1] -> 留言次數 , $friend_id[$i][2] -> 一篇只算一次
	
	//聊天
	$duration_time = $now_time - 5184000;//現在時間往前2個月
	$box = $facebook->api('/me?fields=inbox.limit(100).since('.$duration_time.').fields(to,comments.limit(1))');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$box_i = 0;
	while($box[inbox][data][$box_i] != null){//訊息封包
		if($box[inbox][data][$box_i][to][data][0][id] != $id)
		{
			$fid = $box[inbox][data][$box_i][to][data][0][id];
			$fname = $box[inbox][data][$box_i][to][data][0][name];
		}
		else
		{
			$fid = $box[inbox][data][$box_i][to][data][1][id];
			$fname = $box[inbox][data][$box_i][to][data][1][name];
		}
		if($box[inbox][data][$box_i][to][data][2][id])
		{
			$fid = "group";
			$fname ="group";
		}
		$box_comment_id = $box[inbox][data][$box_i][comments][data][0][id];
		$temp = explode('_',$box_comment_id);
		for( $i=0 ; $i<sizeof($friend_id) ; $i++)
		{
			if( $fid == $friend_id[$i][0] )
			{
				$friend_id[$i][3] = $temp[1];
				break;
			}
		}
		$box_i++;
	}
	for( $i=0 ; $i<sizeof($friend_id) ; $i++)
		$total = $friend_id[$i][3] + $total;

	for( $i=0 ; $i<sizeof($friend_id) ; $i++)
	{
		$friend_id[$i][3] = ($friend_id[$i][3] / $total);
		$friend_id[$i][1] = 1 * $friend_id[$i][3];
	}

	//打卡
	$duration_time = $now_time - 5184000;//現在時間往前2個月
	$lo = $facebook->api('/me?fields=locations.limit(1000).since('.$duration_time.')');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$locations_i = 0;
	while($lo[locations][data][$locations_i] != null){//打卡封包
		$locations_from = $lo[locations][data][$locations_i][from][id];
		for( $i=0 ; $i<sizeof($friend_id) ; $i++)
		{
			if( $locations_from == $friend_id[$i][0])
			{
				$friend_id[$i][1] = $friend_id[$i][1] * 1.4;//pi/2
				break;
			}
		}
		$locations_tag_i = 0;
		while($lo[locations][data][$locations_i][tags][data][$locations_tag_i] != null){//tag
			$locations_tag = $lo[locations][data][$locations_i][tags][data][$locations_tag_i][id];
			for( $i=0 ; $i<sizeof($friend_id) ; $i++)
			{
				if( $locations_tag == $friend_id[$i][0])
				{
					$friend_id[$i][1] = $friend_id[$i][1] * 1.4;
					break;
				}
			}
			$locations_tag_i++;
		}
		$locations_tag=null;
		$locations_i++;
	}
	//塗鴉牆
	$duration_time = $now_time - 31536000;//現在時間往前12個月
	$wall = $facebook->api('/me?fields=posts.limit(1000).since('.$duration_time.').fields(comments.fields(from))');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$feed_i = 0;
	while($wall[posts][data][$feed_i] != null)
	{
		$wall_comment = $wall[posts][data][$feed_i];
		$feed_comment_i = 0;
		while($wall_comment[comments][data][$feed_comment_i] != null)
		{
			$feed_comments_from = $wall_comment[comments][data][$feed_comment_i][from][id];
			for( $i=0 ; $i<sizeof($friend_id) ; $i++)
			{
				if( $feed_comments_from == $friend_id[$i][0] && $friend_id[$i][2] == 0)
				{
					$friend_id[$i][2] = 1;
					$friend_id[$i][1] = $friend_id[$i][1] * 1.1;
					break;
				}
			}
			$feed_comment_i++;
		}
		for( $i=0 ; $i<sizeof($friend_id) ; $i++)
			$friend_id[$i][2] = 0;
		$feed_i++;
	}
	
	//儲存一篇動態
	$wall = $facebook->api('/me?fields=feed.limit(2).fields(status_type)');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$feed_i = 0;
	while($wall[feed][data][$feed_i] != null){//塗鴉牆封包
		if($wall[feed][data][$feed_i][status_type]=='app_created_story')
			$wall_detail = $facebook->api('/'.$wall[feed][data][$feed_i][id].'?fields=from,actions,to,id,created_time,place,likes.limit(1000),comments.limit(1000).fields(message,message_tags,id,from,created_time),message,message_tags,story');
		else
			$wall_detail = $facebook->api('/'.$wall[feed][data][$feed_i][id].'?fields=from,actions,to,id,created_time,place,likes.limit(1000),comments.limit(1000).fields(message,message_tags,id,from,created_time,likes.limit(1000)),message,message_tags,story');
		
		$feed_from = $wall_detail[from][id];
		$feed_id = $wall_detail[id];
		$feed_created_time = date( "Y/m/d H:i:s" , strtotime($wall_detail[created_time]));
		$feed_place = $wall_detail[place][id];
		if($wall_detail[message] != null)
			$feed_message = $wall_detail[message];
		else
			$feed_message = $wall_detail[story];
		for($i = 0;$i < strlen($feed_message);$i++){
			if($wall_detail[message_tags][$i][0] != null){//tag
				$feed_tag = $feed_tag.",.".$wall_detail[message_tags][$i][0][id];
			}
		}
		//點讚區格START
		$result_like = mysql_query("select * from `ppo` where `po_id`='$feed_id'");
		$row_like = mysql_fetch_array($result_like);
		if($row_like['po_id']){
			$str_like1 = $row_like['like'];
			$str_like2 = str_replace(".", "", $str_like1);
			$temp = explode(',',$str_like2);
			$feed_likes_i = 0;
			while($wall_detail[likes][data][$feed_likes_i] != null){//like
				if(!in_array($wall_detail[likes][data][$feed_likes_i][id],$temp))
					$str_like1 = $str_like1.",.".$wall_detail[likes][data][$feed_likes_i][id];
				$feed_likes_i++;
			}
		}
		else{
			$feed_likes_i = 0;
			while($wall_detail[likes][data][$feed_likes_i] != null){//like
				$feed_like = $feed_like.",.".$wall_detail[likes][data][$feed_likes_i][id];
				$feed_likes_i++;
			}
		}
		//點讚區隔END
		$feed_comments_i = 0;
		while($wall_detail[comments][data][$feed_comments_i] != null){//留言
			$feed_comments_id = $wall_detail[comments][data][$feed_comments_i][id];
			$feed_comments_from = $wall_detail[comments][data][$feed_comments_i][from][id];
			$feed_comments_created_time = date( "Y/m/d H:i:s" , strtotime($wall_detail[comments][data][$feed_comments_i][created_time]));
			$feed_comments_message = $wall_detail[comments][data][$feed_comments_i][message];
			$result = mysql_query("select * from `comment_sup` where `comment_id`='$feed_comments_id'");
			$row = mysql_fetch_array($result);
			if($row['comment_id']==null){
				$feed_comment_tag_i = 0;
				while($wall_detail[comments][data][$feed_comments_i][message_tags][$feed_comment_tag_i] != null){//tag
					$feed_comments_tag = $feed_comments_tag.",.".$wall_detail[comments][data][$feed_comments_i][message_tags][$feed_comment_tag_i][id];
					$feed_comment_tag_i++;
				}
			}
			//點讚區格START
			$result_comment_like = mysql_query("select * from `comment` where `comment_id`='$feed_comments_id'");
			$row_comment_like = mysql_fetch_array($result_comment_like);
			if($row_comment_like['comment_id']){
				$str_comment_like1 = $row_comment_like['like'];
				$str_comment_like2 = str_replace(".", "", $str_comment_like1);
				$temp_commnet = explode(',',$str_comment_like2);
				$feed_comments_like_i = 0;
				while($wall_detail[comments][data][$feed_comments_i][likes][data][$feed_comments_like_i] != null){//like
					if(!in_array($wall_detail[comments][data][$feed_comments_i][likes][data][$feed_comments_like_i][id],$temp_commnet))
						$str_comment_like1 = $str_comment_like1.",.".$wall_detail[comments][data][$feed_comments_i][likes][data][$feed_comments_like_i][id];
					$feed_comments_like_i++;
				}
			}
			else{
				$feed_comments_like_i = 0;
				while($wall_detail[comments][data][$feed_comments_i][likes][data][$feed_comments_like_i] != null){//like
					$feed_comment_like = $feed_comment_like.",.".$wall_detail[comments][data][$feed_comments_i][likes][data][$feed_comments_like_i][id];
					$feed_comments_like_i++;
				}
			}
			//點讚區隔END
			//table of comment
			$result = mysql_query("select * from `comment_sup` where `comment_id`='$feed_comments_id'");
			$row = mysql_fetch_array($result);
			if($row['comment_id']==null){
				//echo "creat feed comment</br>";
				mysql_query ("insert into `comment` values ('$feed_comments_id','$feed_comments_from','$feed_from','feed','$feed_id','$feed_comments_created_time','$feed_comments_tag','$feed_comment_like','$feed_comments_message')");
				mysql_query ("insert into `comment_sup` values ('$feed_comments_id','$feed_comments_from','$feed_from','feed','$feed_id','$feed_comments_created_time','1')");
			}
			else{
				//echo "modify feed comment</br>";
				mysql_query ("update `comment` set `time`='$feed_comments_created_time',`tag`='$feed_comments_tag',`like`='$str_comment_like1',`content`='$feed_comments_message' where `comment_id`='$feed_comments_id'");
			}
			$feed_comments_tag=null;
			$feed_comment_like=null;
			$feed_comments_i++;
		}				
		//table of po
		$result = mysql_query("select * from `ppo` where `po_id`='$feed_id'");
		$row = mysql_fetch_array($result);
		if($row['po_id']==null){
			//echo "creat po</br>";
			mysql_query ("insert into `ppo` values ('$feed_from','$feed_id','$feed_created_time','$feed_place','$feed_tag','$feed_like','$feed_message')");
		}
		else{
			//echo "modify po</br>";
			mysql_query ("update `ppo` set `like`='$str_like1' where `po_id`='$feed_id'");
		}
		$feed_tag=null;
		$feed_like=null;
		$feed_i++;
	}
	//自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end
	

	$friend_id = array_sort($friend_id,'1','desc');
	$count = 0;
	foreach($friend_id as $row){
			$import[$count][0] = $row[0];
			$import[$count][1] = $row[1];
			$count++;
			if($count == 15)
				break;
	}
	for($i = 0; $i<15; $i++)
	{
		$fid = $import[$i][0];
		$score = $import[$i][1];
		echo "<a href=\"http://www.facebook.com/profile.php?id=".$fid."\"><img src=\"http://graph.facebook.com/".$fid."/picture\" /></a>";
		echo "__".$import[$i][1]."次"."<br>";
		$result = mysql_query("select * from `social_score` where `id`='$id' and `fid`='$fid'");
		$row = mysql_fetch_array($result);
		if($row['id']!=null)
			mysql_query ("update `social_score` set `score`='$score' where `id`='$id' and `fid`='$fid'");
	}
	//要記得存初始15人進socail score
	
	
	
	
	
	
	//情感辨認(4種)
	$temp0=array();
	$temp1=array();
	$temp2=array();
	$temp3=array();
	$emotion = mysql_query("select * from `emotion` where `group`='0'");
	while($e = mysql_fetch_array($emotion))
	{
		$temptemp = explode(",",$e['word']);
		$temp0=array_merge($temp0,$temptemp);
	}
	$emotion = mysql_query("select * from `emotion` where `group`='1'");
	while($e = mysql_fetch_array($emotion))
	{
		$temptemp = explode(",",$e['word']);
		$temp1=array_merge($temp1,$temptemp);
	}
	$emotion = mysql_query("select * from `emotion` where `group`='2'");
	while($e = mysql_fetch_array($emotion))
	{
		$temptemp = explode(",",$e['word']);
		$temp2=array_merge($temp2,$temptemp);
	}
	$emotion = mysql_query("select * from `emotion` where `group`='3'");
	while($e = mysql_fetch_array($emotion))
	{
		$temptemp = explode(",",$e['word']);
		$temp3=array_merge($temp3,$temptemp);
	}
	//情感辨認陣列建立END
	
	$count = 0;
	while($import[$count][0] != null)
	{
		$fid = $import[$count][0];
		$count++;
		$fql = "SELECT post_id,description,message,type,like_info,created_time FROM stream WHERE source_id ='".$fid."'
				LIMIT 2";
		$fqlresult = $facebook->api(    
			array(   
				'method' => 'fql.query',    
				'query' => $fql  
			));
		echo "<img src='http://graph.facebook.com/".$fid."/picture'/>_".$fid."</br>";
		foreach($fqlresult as $wall){
			if($wall['like_info']['can_like']){
				echo "post_id ".$wall['post_id']."<br>";
				$pid = $wall['post_id'];
				
				if($wall['description'] != null){
					echo "description ".$wall['description']."<br>";
					$posts_message = $wall['description'];
				}
				else if($wall['message'] != null){
					echo "message ".$wall['message']."<br>";
					$posts_message = $wall['message'];
				}
				
				$word = $posts_message;
				$type0 = 0;
				$type1 = 0;
				$type2 = 0;
				$type3 = 0;
				$type = 0;
				$mood = 0;
				if($word!=null){
					for( $i=0 ; $i<sizeof($temp0) ; $i++ )
						$type0 = $type0 + substr_count($word,$temp0[$i]);
					for( $i=0 ; $i<sizeof($temp1) ; $i++ )
						$type1 = $type1 + substr_count($word,$temp1[$i]);
					for( $i=0 ; $i<sizeof($temp2) ; $i++ )
						$type2 = $type2 + substr_count($word,$temp2[$i]);
					for( $i=0 ; $i<sizeof($temp3) ; $i++ )
						$type3 = $type3 + substr_count($word,$temp3[$i]);
						
					$happy = $type0+$type1;
					$sad = $type2+$type3;
					$type = $happy - $sad;
					if($type >=1 )
						$mood = 1;
					if($type >=2)
						$mood = 2;
					if($type <0)
						$mood = -1;
					if($type <-1)
						$mood = -2;	
				}

				if($wall['description'] != null || $wall['message'] != null){
					//echo "created_time ".date( "Y/m/d H:i:s" , $wall['created_time'])."<br>";
					$time = date( "Y/m/d H:i:s" , $wall['created_time']);
					if($wall['like_info']['user_likes'])
						//echo "You said this good.<br>";
					//echo "like_count : " . $wall['like_info']['like_count']."<br>";
					$like_num = $wall['like_info']['like_count'];
					$fql_in = "SELECT fromid,text,time FROM comment WHERE post_id = '".$pid."'
						ORDER BY time ASC LIMIT 300 OFFSET 0";
					$fqlresult_in = $facebook->api(    
						array(   
							'method' => 'fql.query',    
							'query' => $fql_in  
						));
					$commnet_num = 0;
					foreach($fqlresult_in as $comment){
						//if($comment['fromid'] == $id)
						//echo $comment['fromid']."-->".$comment['text']."<br>";
						//echo "created_time ".date( "Y/m/d H:i:s" , $comment['time'])."<br>";
						$c_content = $comment['text'];
						$c_time = date( "Y/m/d H:i:s" , $comment['time']);
						$fromid = $comment['fromid'];
						$commnet_num++;
						$result = mysql_query("select * from `friend_wall_comment` where `from`='$fromid' and `time`='$c_time'");
						$row = mysql_fetch_array($result);
						//mysql_query ("insert into `friend_wall_comment` values ('$fromid','$fid','$pid','$c_time','$c_content')");
					}
					//echo "<br>";
					if( (!strstr($posts_message,'is using') && !strstr($posts_message,' used ') && !strstr($posts_message,'is now') && !strstr($posts_message,'are now') && !strstr($posts_message,' played ') && !strstr($posts_message,' playing ') && !strstr($posts_message,'on his own') && !strstr($posts_message,'on her own') && !strstr($posts_message,'went to') && !strstr($posts_message,'going to') && !strstr($posts_message,' likes ') && !strstr($posts_message,' was ') && !strstr($posts_message,' shared ') && !strstr($posts_message,' updated ') && !strstr($posts_message,' added ') && !strstr($posts_message,' tagged ') && !strstr($posts_message,' changed ') && !strstr($posts_message,' got ') && !strstr($posts_message,' uploaded ') && !strstr($posts_message,' created ') && !strstr($posts_message,' posted ') && !strstr($posts_message,' listed ') && !strstr($posts_message,' rated ') && !strstr($posts_message,' commented ') && !strstr($posts_message,' activated ') && !strstr($posts_message,' joined ') ) || !strstr($posts_message,'.')){
						$result = mysql_query("select * from `friend_wall` where `po_id`='$pid'");
						$row = mysql_fetch_array($result);
						if($row['from']==null)
							mysql_query ("insert into `friend_wall` values ('$fid','$pid','$time','$like_num','$commnet_num','$posts_message','$mood')");
						else
							mysql_query ("update `friend_wall` set `like_num`='$like_num',`comment_num`='$commnet_num',`content`='$posts_message' where `po_id`='$pid'");
					}
				}
			}
		}			
	}

	
}


$ctrl = $_GET['time'];
$result = mysql_query("SELECT * FROM `user_about` WHERE `id`='$id'");//是否存在於user_about
$row = mysql_fetch_array($result);
$last_time = strtotime($row['time']);//判斷是否過一天(unix時間)
//一天更新 : 社團,共同社團,朋友,共同好友,說讚內容,活動,基本資料,網誌,家人
if( ($now_time - $last_time >=86400 && $ctrl==null) || $new == 1 ){
	//社團(公開 不公開 秘密)社團,社團,社團,社團,社團,社團,社團,社團,社團,社團,社團,社團,社團,社團,社團,社團,社團,社團
	$gp = $facebook->api('/me?fields=groups.limit(500).fields(id)');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$group_i = 0;
	while($gp[groups][data][$group_i] != null){//社團封包
		$group_id[$group_i] = $gp[groups][data][$group_i][id];
		$group_detail = $group_detail.",".$gp[groups][data][$group_i][id];
		$group_i++;
	}
	//社團end,社團end,社團end,社團end,社團end,社團end,社團end,社團end,社團end,社團end,社團end,社團end,社團end,社團end
	
	//朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友,朋友
	$fri = $facebook ->api('/me?fields=friends.limit(1000)');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$friend_i = 0;
	while($fri[friends][data][$friend_i] != null){//朋友封包
		$friend_id[$friend_i][0] = $fri[friends][data][$friend_i][id];
		$fid = $fri[friends][data][$friend_i][id];
		//共同社團
		$fri_group = $facebook->api('/'.$friend_id[$friend_i][0].'?fields=groups.limit(1000)');
		$friend_group_i = 0;
		while($fri_group[groups][data][$friend_group_i] != null){
			$gi=0;
			while($group_id[$gi]!=null){//$friend_id[$offset][11]
				if($group_id[$gi] == $fri_group[groups][data][$friend_group_i][id]){
					$same_group_detail= $same_group_detail.",".$fri_group[groups][data][$friend_group_i][id];
					$friend_id[$friend_i][11]++;
				}
				$gi++;
			}
			$friend_group_i++;
		}
		$l = $friend_id[$friend_i][11];
		//判斷共同好友數
		$multual_friends = $facebook->api('/me?fields=mutualfriends.limit(1000).user('.$friend_id[$friend_i][0].')');
		$mut_friend_i = 0;
		while($multual_friends[mutualfriends][data][$mut_friend_i] != null){//共同好友封包
			$same_friend_detail = $same_friend_detail.",".$multual_friends[mutualfriends][data][$mut_friend_i][id];
			$mut_friend_i++;
		}
		$friend_id[$offset][10] = $mut_friend_i;//***存共同好友數***//
		$k = $mut_friend_i;
		
		//table of associate_detail
		/*
		$result = mysql_query("select * from `associate_detail` where `id`='$id' and `friend_id`='$fid'");
		$row = mysql_fetch_array($result);
		if( $row['id']==null )
			mysql_query ("insert into `associate_detail` values ('$id','$fid','$same_friend_detail','$same_group_detail')");
		else
			mysql_query ("update `associate_detail` set  `same_friend` ='$same_friend_detail',`same_group`='$same_group_detail'  where `id`='$id' and `friend_id`='$fid'");
		//table of associate
		$result = mysql_query("select * from `associate` where `id`='$id' and `friend_id`='$fid'");
		$row = mysql_fetch_array($result);
		if( $row['id']==null )
			mysql_query ("insert into `associate` values ('$id','$fid','0','0','0','0','0','0','0','xxx','0','0','$k','$l','xxx','xxx','0','xxx')");
		else
			mysql_query ("update `associate` set  `same_friend_num` ='$k',`same_group_num`='$l'  where `id`='$id' and `friend_id`='$fid'");
		*/
		$same_group_detail = null;
		$same_friend_detail = null;
		$friend_i++;
	}
	//朋友end,朋友end,朋友end,朋友end,朋友end,朋友end,朋友end,朋友end,朋友end,朋友end,朋友end,朋友end,朋友end,朋友end
	
	//說讚的內容,說讚的內容,說讚的內容,說讚的內容,說讚的內容,說讚的內容,說讚的內容,說讚的內容,說讚的內容
	$lik = $facebook->api('/me?fields=likes');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$likes_i = 0;
	$likes_music = 0;
	$likes_movie = 0;
	$likes_tvshow = 0;
	$likes_book = 0;
	$likes_others = 0;
	while($lik[likes][data][$likes_i] != null){//說讚的內容封包
		//分類
		if($lik[likes][data][$likes_i][category] == 'Musician/band'){
			$likes_music++;
			$likes_music_detail = $likes_music_detail.",".$lik[likes][data][$likes_i][id];
		}
		else if($lik[likes][data][$likes_i][category] == 'Movie'){
			$likes_movie++;
			$likes_movie_detail = $likes_movie_detail.",".$lik[likes][data][$likes_i][id];
		}
		else if($lik[likes][data][$likes_i][category] == 'Tv show'){
			$likes_tvshow++;
			$likes_tvshow_detail = $likes_tvshow_detail.",".$lik[likes][data][$likes_i][id];
		}
		else if($lik[likes][data][$likes_i][category] == 'Book'){
			$likes_book++;
			$likes_book_detail = $likes_book_detail.",".$lik[likes][data][$likes_i][id];
		}
		else{
			$likes_others++;
			$likes_others_detail = $likes_others_detail.",".$lik[likes][data][$likes_i][id];
		}
		$likes_i++;
	}
	//說讚的內容end,說讚的內容end,說讚的內容end,說讚的內容end,說讚的內容end,說讚的內容end,說讚的內容end,說讚的內容end
	
	//活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動,活動
	$ent = $facebook->api('/me?fields=events.until(1609430399).limit(1000).fields(id,owner,rsvp_status,name,start_time)');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$event_i = 0;
	$event_hold_num=0;
	while($ent[events][data][$event_i] != null){//活動封包
		$active_detail = $active_detail.",".$ent[events][data][$event_i][id];
		if($ent[events][data][$event_i][owner][id]==$id)
			$event_hold_num++;
		$event_i++;
	}
	//活動end,活動end,活動end,活動end,活動end,活動end,活動end,活動end,活動end,活動end,活動end,活動end,活動end,活動end,活動end
	
	//基本資料,基本資料,基本資料,基本資料,基本資料,基本資料,基本資料,基本資料,基本資料,基本資料,基本資料,基本資料
	$user_about_me =  $facebook->api('/me');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	if($user_about_me[id] != null){//基本資料封包
		//家鄉id
		$hometown_id = $user_about_me[hometown][id];
		$live=0;
		if($hometown_id!=null)
			$live++;
		//居住地id
		$location_id = $user_about_me[location][id];
		if($location_id!=null)
			$live++;
		//生日
		$birthday = $user_about_me[birthday];
		$basic=0;
		if($birthday!=null){
			$basic++;
			$basic_detail = $basic_detail.",".$birthday;
		}
		//關於我
		$bio = $user_about_me[bio];
		$aboutme=0;
		if($bio!=null)
			$aboutme=strlen($bio);
		$aboutme/=3;
		//佳句
		$quotes = $user_about_me[quotes];
		$quote=0;
		if($quotes!=null)
			$quote=strlen($quotes);
		$quote/=3;
		//學歷
		$edu_i = 0;
		$edu_work=0;
		if($user_about_me[education][$edu_i] != null)
			$edu_work++;
		while($user_about_me[education][$edu_i] != null){
			$edu_work_detail = $edu_work_detail.",".$user_about_me[education][$edu_i][school][id];
			$edu_i++;
		}
		//工作
		$work_i = 0;
		if($user_about_me[work][$work_i] != null)
			$edu_work++;
		while($user_about_me[work][$work_i] != null){
			$edu_work_detail = $edu_work_detail.",".$user_about_me[work][$work_i][employer][id];
			$work_i++;
		}
		//性別
		$gender = $user_about_me[gender];
		if($gender!=null){
			$basic++;
			$basic_detail = $basic_detail.",".$gender;
		}
		//戀愛性向
		$interested_in_i = 0;
		if($user_about_me[inspirational_people][$interested_in_i]!=null)
			$basic++;
		while($user_about_me[inspirational_people][$interested_in_i] != null){
			$basic_detail = $basic_detail.",".$user_about_me[interested_in][$interested_in_i];
			$interested_in_i++;
		}
		//情感狀態
		$relationship_status = $user_about_me[relationship_status];
		if($relationship_status!=null){
			$basic++;
			$basic_detail = $basic_detail.",".$relationship_status;
		}
		//宗教
		$religion = $user_about_me[religion];
		if($religion!=null){
			$basic++;
			$basic_detail = $basic_detail.",".$religion;
		}
		//政治
		$political = $user_about_me[political];
		if($political!=null){
			$basic++;
			$basic_detail = $basic_detail.",".$political;
		}
		//電子郵件
		$email = $user_about_me[email];
		$message=0;
		if($email!=null){
			$message++;
			$message_detail = $message_detail.",".$emil;
		}
		//語言
		$languages_i = 0;
		if($user_about_me[languages][$languages_i]!=null)
			$basic++;
		while($user_about_me[languages][$languages_i] != null){
			$basic_detail = $basic_detail.",".$user_about_me[languages][$languages_i][id];
			$languages_i++;
		}
	}
	//基本資料end,基本資料end,基本資料end,基本資料end,基本資料end,基本資料end,基本資料end,基本資料end,基本資料end
	
	//網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌,網誌
	$no = $facebook->api('/me?fields=notes');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$notes_i = 0;
	while($no[notes][data][$notes_i] != null){//網誌封包
		$notes_id = $no[notes][data][$notes_i][id];
		$notes_create_time = date( "Y/m/d H:i:s" , strtotime($no[notes][data][$notes_i][created_time]) );
		$notes_comment_i = 0;
		while($no[notes][data][$notes_i][comments][data][$notes_comment_i] != null){//留言
			$notes_comments_id = $no[notes][data][$notes_i][comments][data][$notes_comment_i][id];
			$notes_comments_from = $no[notes][data][$notes_i][comments][data][$notes_comment_i][from][id];
			$notes_comments_created_time = date( "Y/m/d H:i:s" , strtotime($no[notes][data][$notes_i][comments][data][$notes_comment_i][created_time]) );
			$notes_comments_message = $no[notes][data][$notes_i][comments][data][$notes_comment_i][message];
			$notes_comment_like_i = 0;
			$notes_comment_tag_i = 0;
			while($no[notes][data][$notes_i][comments][data][$notes_comment_i][message_tags][$notes_comment_tag_i] != null){//tag
				$notes_comments_tag = $notes_comments_tag.",".$no[notes][data][$notes_i][comments][data][$notes_comment_i][message_tags][$notes_comment_tag_i][id];
				$notes_comment_tag_i++;
			}
			while($no[notes][data][$notes_i][comments][data][$notes_comment_i][likes][data][$notes_comment_like_i] != null){//like
				$notes_comments_like =  $notes_comments_like.",".$no[notes][data][$notes_i][comments][data][$notes_comment_i][likes][data][$notes_comment_like_i][id];
				$notes_comment_like_i++;
			}
			//table of comment
			$result = mysql_query("select * from `comment` where `comment_id`='$notes_comments_id'");
			$row = mysql_fetch_array($result);
			if($row['comment_id']==null){
				//echo "creat comment</br>";
				mysql_query ("insert into `comment` values ('$notes_comments_id','$notes_comments_from','$id','blog','$notes_id','$notes_comments_created_time','$notes_comments_tag','$notes_comments_like','$notes_comments_message')");
			}
			else{
				//echo "modify comment</br>";
				mysql_query ("update `comment` set `time`='$notes_comments_created_time',`tag`='$notes_comments_tag',`like`='$notes_comments_like',`content`='$notes_comments_message' where `comment_id`='$notes_comments_id'");
			}
			$notes_comments_like=null;
			$notes_comments_tag=null;
			$notes_comment_i++;
		}
		//table of blog
		$result = mysql_query("select * from `blog` where `blog_id`='$notes_id'");
		$row = mysql_fetch_array($result);
		if($row['blog_id']==null){
			//echo "creat blog</br>";
			mysql_query ("insert into `blog` values ('$id','$notes_id','$notes_create_time')");
		}
		else{
			//echo "modify blog</br>";
			mysql_query ("update `blog` set `time`='$notes_create_time' where `blog_id`='$notes_id'");
		}
		$notes_i++;
	}
	//網誌end,網誌end,網誌end,網誌end,網誌end,網誌end,網誌end,網誌end,網誌end,網誌end,網誌end,網誌end,網誌end,網誌end
	
	//家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人,家人
	$fam = $facebook->api('/me?fields=family.limit(100)');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$family_i = 0;
	$ffamily=0;
	if($fam[family][data][$family_i] != null)
		$ffamily=1;
	while($fam[family][data][$family_i] != null){//家人封包
		$family_detail = $family_detail.",".$fam[family][data][$family_i][id];
		$family_detail = $family_detail.".".$fam[family][data][$family_i][relationship];
		$family_i++;
	}
	//家人end,家人end,家人end,家人end,家人end,家人end,家人end,家人end,家人end,家人end,家人end,家人end,家人end,家人end
	
	//存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫
	//table of user_about
	$nowtime = date("Y/m/d H:i:s",$now_time);
	$result = mysql_query("select * from `user_about` where `id`='$id'");
	$row = mysql_fetch_array($result);
	if($row['id'] == null)
		mysql_query ("insert into `user_about` values ('$id','$nowtime','$nowtime','$edu_work','$ffamily','$aboutme','$quote','$live','$basic','$message','xxx','xxx','$friend_i','xxx','0','0','0','xxx','0','xxx','xxx','0','$likes_music','$likes_movie','$likes_tvshow','$likes_book','$likes_others','$notes_i','xxx','xxx','0','0','0','0','xxx','0','0','0','xxx','xxx','$group_i','0','$event_i','$event_hold_num')");
	
	else
	{
		mysql_query ("update `user_about` set `time`='$nowtime',`edu_work`='$edu_work',`family`='$ffamily',`about_me`='$aboutme',`quote`='$quote',`live`='$live',`basic_data`='$basic',`message_data`='$message',`friend_num`='$friend_i',`like_music`='$likes_music',`like_movie`='$likes_movie',`like_TV`='$likes_tvshow',`like_book`='$likes_book',`like_etc`='$likes_others',`blog_num`='$notes_i',`group_num`='$group_i',`active_num`='$event_i',`active_hold_num`='$event_hold_num' where `id`='$id'");
		//table of user_about_detail
		//mysql_query ("update `user_about_detail` set `edu_work`='$edu_work_detail',`family`='$family_detail',`about_me`='$bio',`quote`='$quotes',`live`='$hometown_id',`basic_data`='$basic_detail',`message_data`='$message_detail',`group`='$group_detail',`like_music`='$likes_music_detail',`like_movie`='$likes_movie_detail',`like_TV`='$likes_tvshow_detail',`like_book`='$likes_book_detail',`like_etc`='$likes_others_detail',`active`='$active_detail' where `id`='$id'");
	}
	//存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end
}
else if( $ctrl==60 ){//1小時更新 : 相簿,照片,打卡
	//相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿,相簿
	$alb = $facebook->api('/me?fields=albums.limit(1000).fields(id,count,updated_time)');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$album_i = 0;
	$picture_up_num = 0;
	$duration_time = $now_time - 86400*7 ;
	while($alb[albums][data][$album_i] != null){//相簿
		$picture_up_num=$picture_up_num+$alb[albums][data][$album_i][count];
		if( strtotime($alb[albums][data][$album_i][updated_time]) > $duration_time){
			$alb_update = $facebook->api('/'.$alb[albums][data][$album_i][id].'?fields=from,id,created_time,description,place,comments.limit(1000).fields(from,id,message,message_tags,created_time,likes.limit(1000)),name,count,likes.limit(1000)');
			$album_id = $alb_update[id];
			$album_name = $alb_update[name];
			$album_place = $alb_update[place][id];
			$album_description = $alb_update[description];
			$album_created_time = date( "Y/m/d H:i:s" , strtotime($alb_update[created_time]));
			//點讚START
			$result_like = mysql_query("select * from `album` where `alb_id`='$album_id'");
			$row_like = mysql_fetch_array($result_like);
			if($row_like['alb_id']){
				$str_like1 = $row_like['like'];
				$str_like2 = str_replace(".", "", $str_like1);
				$temp = explode(',',$str_like2);
				for($y=0;$y<sizeof($temp);$y++)
					echo $temp[$y]."<br>";
				echo "-----------------------<br>";
				$album_like_i = 0;
				while($alb_update[likes][data][$album_like_i] != null){//like
					if(!in_array($alb_update[likes][data][$album_like_i][id],$temp))
						$str_like1 = $str_like1.",.".$alb_update[likes][data][$album_like_i][id];
					echo $alb_update[likes][data][$album_like_i][id]."<br>";
					$album_like_i++;
				}
			}
			else{
				$album_like_i = 0;
				while($alb_update[likes][data][$album_like_i] != null){//like
					$album_like = $album_like.",.".$alb_update[likes][data][$album_like_i][id];
					$album_like_i++;
				}
			}
			//點讚END
			
			$album_comment_i = 0;
			while($alb_update[comments][data][$album_comment_i] != null){//留言
				$album_comment_id = $alb_update[comments][data][$album_comment_i][id];
				$album_comment_from = $alb_update[comments][data][$album_comment_i][from][id];
				$album_comment_created_time = date( "Y/m/d H:i:s" , strtotime($alb_update[comments][data][$album_comment_i][created_time]));
				$album_comment_message = $alb_update[comments][data][$album_comment_i][message];
				$album_comment_tag_i = 0;
				while($alb_update[comments][data][$album_comment_i][message_tags][$album_comment_tag_i] != null){//tag
					$album_comment_tag = $album_comment_tag.",.".$alb_update[comments][data][$album_comment_i][message_tags][$album_comment_tag_i][id];
					$album_comment_tag_i++;
				}
				
				//點讚區格START
				$result_comment_like = mysql_query("select * from `comment` where `comment_id`='$album_comment_id'");
				$row_comment_like = mysql_fetch_array($result_comment_like);
				if($row_comment_like['comment_id']){
					$str_comment_like1 = $row_comment_like['like'];
					$str_comment_like2 = str_replace(".", "", $str_comment_like1);
					$temp_commnet = explode(',',$str_comment_like2);
					$album_comment_like_i = 0;
					while($alb_update[comments][data][$album_comment_i][likes][data][$album_comment_like_i] != null){//like
						if(!in_array($alb_update[comments][data][$album_comment_i][likes][data][$album_comment_like_i][id],$temp_commnet))
							$str_comment_like1 = $str_comment_like1.",.".$alb_update[comments][data][$album_comment_i][likes][data][$album_comment_like_i][id];
						$album_comment_like_i++;
					}
				}
				else{
					$album_comment_like_i = 0;
					while($alb_update[comments][data][$album_comment_i][likes][data][$album_comment_like_i] != null){//like
						$album_comment_like = $album_comment_like.",.".$alb_update[comments][data][$album_comment_i][likes][data][$album_comment_like_i][id];
						$album_comment_like_i++;
					}	
				}
				//點讚區隔END
				//table of comment
				$result = mysql_query("select * from `comment` where `comment_id`='$album_comment_id'");
				$row = mysql_fetch_array($result);
				if($row['comment_id']==null){
					//echo "creat comment</br>";
					mysql_query ("insert into `comment` values ('$album_comment_id','$album_comment_from','$id','album','$album_id','$album_comment_created_time','$album_comment_tag','$album_comment_like','$album_comment_message')");
				}
				else{
					//echo "modify alb comment</br>";
					mysql_query ("update `comment` set `tag`='$album_comment_tag',`like`='$str_comment_like1',`content`='$album_comment_message' where `comment_id`='$album_comment_id'");
				}
				$album_comment_tag=null;
				$album_comment_like=null;
				$album_comment_i++;
			}
			//table of album
			$result = mysql_query("select * from `album` where `alb_id`='$album_id'");
			$row = mysql_fetch_array($result);
			if($row['alb_id']==null){
				mysql_query ("insert into `album` values ('$id','$album_id','$album_name','$album_created_time','$album_place','$album_like','$album_description')");
			}
			else{
				mysql_query ("update `album` set `alb_name`='$album_name',`place`='$album_place',`like`='$str_like1',`content`='$album_description' where `alb_id`='$album_id'");
			}
			$album_like=null;
	//相簿內照片,相簿內照片,相簿內照片,相簿內照片,相簿內照片,相簿內照片,相簿內照片,相簿內照片,相簿內照片,相簿內照片,相簿內照片,相簿內照片,相簿內照片
			$pic = $facebook->api('/'.$album_id.'?fields=photos.limit(10000).fields(from,id,name,album,created_time,updated_time,place,tags,likes.limit(1000),comments.limit(1000).fields(from,id,message,message_tags,created_time,likes.limit(1000)),images)');
			//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
			$photo_i = 0;
			while($pic[photos][data][$photo_i] != null){//照片封包
				$photo_from = $pic[photos][data][$photo_i][from][id];
				$photo_id = $pic[photos][data][$photo_i][id];
				$photo_album_id = $pic[photos][data][$photo_i][album][id];
				$photo_name = $pic[photos][data][$photo_i][name];
				$photo_created_time = date( "Y/m/d H:i:s" , strtotime($pic[photos][data][$photo_i][created_time]));
				$photo_place = $pic[photos][data][$photo_i][place][id];
				$photo_image = $pic[photos][data][$photo_i][images][6][source];
				$photo_tags_i = 0;
				while($pic[photos][data][$photo_i][tags][data][$photo_tags_i] != null){//tag
					$photo_tag = $photo_tag.",.".$pic[photos][data][$photo_i][tags][data][$photo_tags_i][id];
					$photo_tags_i++;
				}
				
				//點讚區格START
				$result_like = mysql_query("select * from `picture` where `pic_id`='$photo_id'");
				$row_like = mysql_fetch_array($result_like);
				if($row_like['pic_id']){
					$str_like1 = $row_like['like'];
					$str_like2 = str_replace(".", "", $str_like1);
					$temp = explode(',',$str_like2);
					$photo_likes_i = 0;
					while($pic[photos][data][$photo_i][likes][data][$photo_likes_i] != null){//like
						if(!in_array($pic[photos][data][$photo_i][likes][data][$photo_likes_i][id],$temp))
							$str_like1 = $str_like1.",.".$pic[photos][data][$photo_i][likes][data][$photo_likes_i][id];
						$photo_likes_i++;
					}
				}
				else{
					$photo_likes_i = 0;
					while($pic[photos][data][$photo_i][likes][data][$photo_likes_i] != null){//like
						$photo_likes = $photo_likes.",.".$pic[photos][data][$photo_i][likes][data][$photo_likes_i][id];
						$photo_likes_i++;
					}
				}
				//點讚區隔END
				$photo_comment_i = 0;
				while($pic[photos][data][$photo_i][comments][data][$photo_comment_i] != null){//留言
					$photo_comment_id = $pic[photos][data][$photo_i][comments][data][$photo_comment_i][id];
					$photo_comment_from = $pic[photos][data][$photo_i][comments][data][$photo_comment_i][from][id];
					$photo_comment_created_time = date( "Y/m/d H:i:s" , strtotime($pic[photos][data][$photo_i][comments][data][$photo_comment_i][created_time]));
					$photo_comment_message = $pic[photos][data][$photo_i][comments][data][$photo_comment_i][message];
					$result = mysql_query("select * from `comment_sup` where `comment_id`='$photo_comment_id'");
					$row = mysql_fetch_array($result);
					if($row['comment_id']==null){
						$photo_comment_tag_i = 0;
						while($pic[photos][data][$photo_i][comments][data][$photo_comment_i][message_tags][$photo_comment_tag_i]){//tag
							$photo_comment_tag = $photo_comment_tag.",.".$pic[photos][data][$photo_i][comments][data][$photo_comment_i][message_tags][$photo_comment_tag_i][id];
							$photo_comment_tag_i++;
						}
					}
					//點讚區格START
					$result_comment_like = mysql_query("select * from `comment` where `comment_id`='$photo_comment_id'");
					$row_comment_like = mysql_fetch_array($result_comment_like);
					if($row_comment_like['comment_id']){
						$str_comment_like1 = $row_comment_like['like'];
						$str_comment_like2 = str_replace(".", "", $str_comment_like1);
						$temp_commnet = explode(',',$str_comment_like2);
						$photo_comment_like_i = 0;
						while($pic[photos][data][$photo_i][comments][data][$photo_comment_i][likes][data][$photo_comment_like_i] != null){//like
							if(!in_array($pic[photos][data][$photo_i][comments][data][$photo_comment_i][likes][data][$photo_comment_like_i][id],$temp_commnet))
								$str_comment_like1 = $str_comment_like1.",.".$pic[photos][data][$photo_i][comments][data][$photo_comment_i][likes][data][$photo_comment_like_i][id];
							$photo_comment_like_i++;
						}
					}
					else{
						$photo_comment_like_i = 0;
						while($pic[photos][data][$photo_i][comments][data][$photo_comment_i][likes][data][$photo_comment_like_i] != null){//like
							$photo_comment_like = $photo_comment_like.",.".$pic[photos][data][$photo_i][comments][data][$photo_comment_i][likes][data][$photo_comment_like_i][id];
							$photo_comment_like_i++;
						}
					}
					//點讚區隔END
					//table of comment
					$result = mysql_query("select * from `comment_sup` where `comment_id`='$photo_comment_id'");
					$row = mysql_fetch_array($result);
					if($row['comment_id']==null){
						mysql_query ("insert into `comment` values ('$photo_comment_id','$photo_comment_from','$photo_from','picture','$photo_id','$photo_comment_created_time','$photo_comment_tag','$photo_comment_like','$photo_comment_message')");
						mysql_query ("insert into `comment_sup` values ('$photo_comment_id','$photo_comment_from','$photo_from','picture','$photo_id','$photo_comment_created_time','1')");
					}
					else{
						mysql_query ("update `comment` set `tag`='$photo_comment_tag',`like`='$str_comment_like1',`content`='$photo_comment_message' where `comment_id`='$photo_comment_id'");
					}
					$photo_comment_tag=null;
					$photo_comment_like=null;
					$photo_comment_i++;
				}
				//table of picture
				$result = mysql_query("select * from picture where pic_id='$photo_id'");
				$row = mysql_fetch_array($result);
				if($row['pic_id']==null){
					mysql_query ("insert into `picture` values ('$photo_from','$photo_id','$photo_album_id','$album_name','$photo_created_time','$photo_place','$photo_tag','$photo_likes','$photo_name','$photo_image')");
				}
				else{
					mysql_query ("update `picture` set `album_name`='$album_name',`place`='$photo_place',`tag`='$photo_tag',`like`='$str_like1',`content`='$photo_name' where `pic_id`='$photo_id'");
				}
				$photo_tag=null;
				$photo_likes=null;
				$photo_i++;
			}
	//相簿內照片end,相簿內照片end,相簿內照片end,相簿內照片end,相簿內照片end,相簿內照片end,相簿內照片end,相簿內照片end,相簿內照片end,相簿內照片end
		}
		$album_i++;
	}
	//相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end,相簿end
	
	//相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片,相片
	//$pict = $facebook->api('/me?fields=photos.limit(10000).since('.$duration_time.').fields(from,id,album,created_time,place,tags,likes.limit(1000),comments.limit(1000).fields(from,id,message,message_tags,created_time,likes.limit(1000)),images)');
	$pict = $facebook->api('/me?fields=photos.limit(10000).fields(from,id,album,created_time,place,tags,likes.limit(1000),comments.limit(1000).fields(from,id,message,message_tags,created_time,likes.limit(1000)),images,name)');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$photo_i = 0;
	while($pict[photos][data][$photo_i] != null){//照片封包
		$photo_from = $pict[photos][data][$photo_i][from][id];
		$photo_id = $pict[photos][data][$photo_i][id];
		$photo_album_id = $pict[photos][data][$photo_i][album][id];
		$photo_album_name = $pict[photos][data][$photo_i][album][name];
		$photo_created_time = date( "Y/m/d H:i:s" , strtotime($pict[photos][data][$photo_i][created_time]));
		$photo_place = $pict[photos][data][$photo_i][place][id];
		$photo_name = $pict[photos][data][$photo_i][name];
		$photo_image = $pict[photos][data][$photo_i][images][6][source];
		$photo_tags_i = 0;
		
		while($pict[photos][data][$photo_i][tags][data][$photo_tags_i] != null){//tag
			$photo_tag = $photo_tag.",.".$pict[photos][data][$photo_i][tags][data][$photo_tags_i][id];
			$photo_tags_i++;
		}
		
		//點讚區格START
		$result_like = mysql_query("select * from `picture` where `pic_id`='$photo_id'");
		$row_like = mysql_fetch_array($result_like);
		if($row_like['pic_id']){
			$str_like1 = $row_like['like'];
			$str_like2 = str_replace(".", "", $str_like1);
			$temp = explode(',',$str_like2);
			$photo_likes_i = 0;
			while($pic[photos][data][$photo_i][likes][data][$photo_likes_i] != null){//like
				if(!in_array($pic[photos][data][$photo_i][likes][data][$photo_likes_i][id],$temp))
					$str_like1 = $str_like1.",.".$pic[photos][data][$photo_i][likes][data][$photo_likes_i][id];
				$photo_likes_i++;
			}
		}
		else{
			$photo_likes_i = 0;
			while($pic[photos][data][$photo_i][likes][data][$photo_likes_i] != null){//like
				$photo_likes = $photo_likes.",.".$pic[photos][data][$photo_i][likes][data][$photo_likes_i][id];
				$photo_likes_i++;
			}
		}
		//點讚區隔END
		$photo_comment_i = 0;
		while($pict[photos][data][$photo_i][comments][data][$photo_comment_i] != null){//留言
			$photo_comment_id = $pict[photos][data][$photo_i][comments][data][$photo_comment_i][id];
			$photo_comment_from = $pict[photos][data][$photo_i][comments][data][$photo_comment_i][from][id];
			$photo_comment_created_time = date( "Y/m/d H:i:s" , strtotime($pict[photos][data][$photo_i][comments][data][$photo_comment_i][created_time]));
			$photo_comment_message = $pict[photos][data][$photo_i][comments][data][$photo_comment_i][message];
			$result = mysql_query("select * from `comment_sup` where `comment_id`='$photo_comment_id'");
			$row = mysql_fetch_array($result);
			if($row['comment_id']==null){
				$photo_comment_tag_i = 0;
				while($pict[photos][data][$photo_i][comments][data][$photo_comment_i][message_tags][$photo_comment_tag_i] != null){//tag
					$photo_comment_tag = $photo_comment_tag.",.".$pict[photos][data][$photo_i][comments][data][$photo_comment_i][message_tags][$photo_comment_tag_i][id];
					$photo_comment_tag_i++;
				}
			}
			//點讚區格START
			$result_comment_like = mysql_query("select * from `comment` where `comment_id`='$photo_comment_id'");
			$row_comment_like = mysql_fetch_array($result_comment_like);
			if($row_comment_like['comment_id']){
				$str_comment_like1 = $row_comment_like['like'];
				$str_comment_like2 = str_replace(".", "", $str_comment_like1);
				$temp_commnet = explode(',',$str_comment_like2);
				$photo_comment_like_i = 0;
				while($pic[photos][data][$photo_i][comments][data][$photo_comment_i][likes][data][$photo_comment_like_i] != null){//like
					if(!in_array($pic[photos][data][$photo_i][comments][data][$photo_comment_i][likes][data][$photo_comment_like_i][id],$temp_commnet))
						$str_comment_like1 = $str_comment_like1.",.".$pic[photos][data][$photo_i][comments][data][$photo_comment_i][likes][data][$photo_comment_like_i][id];
					$photo_comment_like_i++;
				}
			}
			else{
				$photo_comment_like_i = 0;
				while($pic[photos][data][$photo_i][comments][data][$photo_comment_i][likes][data][$photo_comment_like_i] != null){//like
					$photo_comment_like = $photo_comment_like.",.".$pic[photos][data][$photo_i][comments][data][$photo_comment_i][likes][data][$photo_comment_like_i][id];
					$photo_comment_like_i++;
				}
			}
			//點讚區隔END
			//table of comment
			$result = mysql_query("select * from `comment_sup` where `comment_id`='$photo_comment_id'");
			$row = mysql_fetch_array($result);
			if($row['comment_id']==null){
				//echo "creat comment</br>";
				mysql_query ("insert into `comment` values ('$photo_comment_id','$photo_comment_from','$photo_from','picture','$photo_id','$photo_comment_created_time','$photo_comment_tag','$photo_comment_like','$photo_comment_message')");
				mysql_query ("insert into `comment_sup` values ('$photo_comment_id','$photo_comment_from','$photo_from','picture','$photo_id','$photo_comment_created_time','1')");
			}
			else{
				//echo "modify photo comment</br>";
				mysql_query ("update comment set `tag`='$photo_comment_tag',`like`='$str_comment_like1',`content`='$photo_comment_message' where `comment_id`='$photo_comment_id'");
			}
			$photo_comment_tag=null;
			$photo_comment_like=null;
			$photo_comment_i++;
		}
		//table of picture
		$result = mysql_query("select * from `picture` where `pic_id`='$photo_id'");
		$row = mysql_fetch_array($result);
		if($row['pic_id']==null){
			//echo "creat picture</br>";
			mysql_query ("insert into `picture` values ('$photo_from','$photo_id','$photo_album_id','$photo_album_name','$photo_created_time','$photo_place','$photo_tag','$photo_likes','$photo_name','$photo_image')");
		}
		else{
			//echo "modify picture</br>";
			mysql_query ("update `picture` set `place`='$photo_place',`tag`='$photo_tag',`like`='$str_like1',`content`='$photo_name' where `pic_id`='$photo_id'");
		}
		$photo_tag=null;
		$photo_likes=null;
		$photo_i++;
	}
	//相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end,相片end

	//打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡,打卡
	$duration_time = $now_time -86400;
	$lo = $facebook->api('/me?fields=locations.limit(1000).since('.$duration_time.')');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$locations_i = 0;
	while($lo[locations][data][$locations_i] != null){//打卡封包
		$locations_from = $lo[locations][data][$locations_i][from][id];
		$locations_id = $lo[locations][data][$locations_i][id];
		$locations_created_time = date( "Y/m/d H:i:s" , strtotime($lo[locations][data][$locations_i][created_time]));
		$locations_place = $lo[locations][data][$locations_i][place][id];
		$locations_tag_i = 0;
		while($lo[locations][data][$locations_i][tags][data][$locations_tag_i] != null){//tag
			$locations_tag = $locations_tag.",.".$lo[locations][data][$locations_i][tags][data][$locations_tag_i][id];
			$locations_tag_i++;
		}
		//table of check_in
		$result = mysql_query("select * from `check_in` where `check_id`='$locations_id'");
		$row = mysql_fetch_array($result);
		if($row['check_id']==null){
			//echo "creat check_in</br>";
			//echo "insert into check_in values ('$locations_from','$locations_id','$locations_created_time','$locations_place','$locations_tag')</br>";
			mysql_query ("insert into `check_in` values ('$locations_from','$locations_id','$locations_created_time','$locations_place','$locations_tag')");
		}
		$locations_tag=null;
		$locations_i++;
	}
	//打卡end,打卡end,打卡end,打卡end,打卡end,打卡end,打卡end,打卡end,打卡end,打卡end,打卡end,打卡end,打卡end,打卡end,打卡end
	
	//存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫,存入資料庫
	//table of user_about
	$result = mysql_query("select * from `user_about` where `id`='$id'");
	$row = mysql_fetch_array($result);
	$locations_i += $row['tag_num'];
	mysql_query ("update `user_about` set `picture_up_num`='$picture_up_num',`alb_num`='$album_i',`picture_tag_num`='$photo_i',`tag_num`='$locations_i' where `id`='$id'");
	//存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end,存入資料庫end
	
}
else if( $ctrl==10 ){
	$duration_time = $now_time - 86400;
	//對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話,對話
	//$box = $facebook->api('/me?fields=inbox.since('.$duration_time.').limit(1000).fields(to,comments.limit(1000).fields(from,message,created_time))');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$box_i = 0;
	while($box[inbox][data][$box_i] != null){//訊息封包
		$box_comment_i = 0;
		if($box[inbox][data][$box_i][to][data][0][id] != $id)
			$fid = $box[inbox][data][$box_i][to][data][0][id];
		else 
			$fid = $box[inbox][data][$box_i][to][data][1][id];
		while($box[inbox][data][$box_i][comments][data][$box_comment_i] != null){//內容
			$box_comment_from = $box[inbox][data][$box_i][comments][data][$box_comment_i][from][id];
			$box_comment_id = $box[inbox][data][$box_i][comments][data][$box_comment_i][id];
			$box_comment_created_time = date( "Y/m/d H:i:s" , strtotime($box[inbox][data][$box_i][comments][data][$box_comment_i][created_time]));
			$box_comment_message = $box[inbox][data][$box_i][comments][data][$box_comment_i][message];
			//table of message
			$result = mysql_query("select * from `message` where `msg_id`='$box_comment_id'");
			$row = mysql_fetch_array($result);
			if($row['msg_id']==null){
				//echo "creat message</br>";
				if($box_comment_from==$fid)
					mysql_query ("insert into `message` values ('$fid','$id','$box_comment_id','$box_comment_created_time','$box_comment_message')");
				else
					mysql_query ("insert into `message` values ('$id','$fid','$box_comment_id','$box_comment_created_time','$box_comment_message')");
			}
			$box_comment_i++;
		}
		$box_i++;
	}
	$box = $facebook->api('/me?fields=inbox.limit(50).fields(unseen,to,unread)');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$box_i = 0;
	while($box[inbox][data][$box_i] != null){//訊息封包
		if($box[inbox][data][$box_i][to][data][0][id] != $id)
		{
			$fid = $box[inbox][data][$box_i][to][data][0][id];
			$fname = $box[inbox][data][$box_i][to][data][0][name];
		}
		else
		{
			$fid = $box[inbox][data][$box_i][to][data][1][id];
			$fname = $box[inbox][data][$box_i][to][data][1][name];
		}
		if($box[inbox][data][$box_i][to][data][2][id])
		{
			$fid = "group";
			$fname ="group";
		}
		$unseen = $box[inbox][data][$box_i][unseen];
		$unread = $box[inbox][data][$box_i][unread];
		$msg_id = $box[inbox][data][$box_i][id];
		$result = mysql_query("select * from `message_less` where `id`='$id' and `fid`='$fid'");
		$row = mysql_fetch_array($result);
		if($row['msg_id']==null)
			mysql_query ("insert into `message_less` values ('$msg_id','$id','$fid','$fname','$unseen','$unread')");
		else
			mysql_query ("update `message_less` set `fname`='$fname',`unseen`='$unseen',`unread`='$unread' where `msg_id`='$msg_id' ");
		$box_i++;
	}
	
	
	
	//對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end,對話end
	
	//自己的塗鴉牆(user發出 or 有關user 筆數不固定 沒紀錄回應的回應),自己的塗鴉牆,自己的塗鴉牆,自己的塗鴉牆,自己的塗鴉牆,自己的塗鴉牆
	$wall = $facebook->api('/me?fields=feed.limit(1000).since('.$duration_time.').fields(status_type)');
	//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	$feed_i = 0;
	while($wall[feed][data][$feed_i] != null){//塗鴉牆封包
		if($wall[feed][data][$feed_i][status_type]=='app_created_story')
			$wall_detail = $facebook->api('/'.$wall[feed][data][$feed_i][id].'?fields=from,actions,to,id,created_time,place,likes.limit(1000),comments.limit(1000).fields(message,message_tags,id,from,created_time),message,message_tags,story');
		else
		$wall_detail = $facebook->api('/'.$wall[feed][data][$feed_i][id].'?fields=from,actions,to,id,created_time,place,likes.limit(1000),comments.limit(1000).fields(message,message_tags,id,from,created_time,likes.limit(1000)),message,message_tags,story');
		
		$feed_from = $wall_detail[from][id];
		$feed_id = $wall_detail[id];
		$feed_created_time = date( "Y/m/d H:i:s" , strtotime($wall_detail[created_time]));
		$feed_place = $wall_detail[place][id];
		if($wall_detail[message] != null)
			$feed_message = $wall_detail[message];
		else
			$feed_message = $wall_detail[story];
		for($i = 0;$i < strlen($feed_message);$i++){
			if($wall_detail[message_tags][$i][0] != null){//tag
				$feed_tag = $feed_tag.",.".$wall_detail[message_tags][$i][0][id];
			}
		}
		//點讚區格START
		$result_like = mysql_query("select * from `ppo` where `po_id`='$feed_id'");
		$row_like = mysql_fetch_array($result_like);
		if($row_like['po_id']){
			$str_like1 = $row_like['like'];
			$str_like2 = str_replace(".", "", $str_like1);
			$temp = explode(',',$str_like2);
			$feed_likes_i = 0;
			while($wall_detail[likes][data][$feed_likes_i] != null){//like
				if(!in_array($wall_detail[likes][data][$feed_likes_i][id],$temp))
					$str_like1 = $str_like1.",.".$wall_detail[likes][data][$feed_likes_i][id];
				$feed_likes_i++;
			}
		}
		else{
			$feed_likes_i = 0;
			while($wall_detail[likes][data][$feed_likes_i] != null){//like
				$feed_like = $feed_like.",.".$wall_detail[likes][data][$feed_likes_i][id];
				$feed_likes_i++;
			}
		}
		//點讚區隔END
		$feed_comments_i = 0;
		while($wall_detail[comments][data][$feed_comments_i] != null){//留言
			$feed_comments_id = $wall_detail[comments][data][$feed_comments_i][id];
			$feed_comments_from = $wall_detail[comments][data][$feed_comments_i][from][id];
			$feed_comments_created_time = date( "Y/m/d H:i:s" , strtotime($wall_detail[comments][data][$feed_comments_i][created_time]));
			$feed_comments_message = $wall_detail[comments][data][$feed_comments_i][message];
			$feed_comment_tag_i = 0;
			while($wall_detail[comments][data][$feed_comments_i][message_tags][$feed_comment_tag_i] != null){//tag
				$feed_comments_tag = $feed_comments_tag.",.".$wall_detail[comments][data][$feed_comments_i][message_tags][$feed_comment_tag_i][id];
				$feed_comment_tag_i++;
			}
			//點讚區格START
			$result_comment_like = mysql_query("select * from `comment` where `comment_id`='$feed_comments_id'");
			$row_comment_like = mysql_fetch_array($result_comment_like);
			if($row_comment_like['comment_id']){
				$str_comment_like1 = $row_comment_like['like'];
				$str_comment_like2 = str_replace(".", "", $str_comment_like1);
				$temp_commnet = explode(',',$str_comment_like2);
				$feed_comments_like_i = 0;
				while($wall_detail[comments][data][$feed_comments_i][likes][data][$feed_comments_like_i] != null){//like
					if(!in_array($wall_detail[comments][data][$feed_comments_i][likes][data][$feed_comments_like_i][id],$temp_commnet))
						$str_comment_like1 = $str_comment_like1.",.".$wall_detail[comments][data][$feed_comments_i][likes][data][$feed_comments_like_i][id];
					$feed_comments_like_i++;
				}
			}
			else{
				$feed_comments_like_i = 0;
				while($wall_detail[comments][data][$feed_comments_i][likes][data][$feed_comments_like_i] != null){//like
					$feed_comment_like = $feed_comment_like.",.".$wall_detail[comments][data][$feed_comments_i][likes][data][$feed_comments_like_i][id];
					$feed_comments_like_i++;
				}
			}
			//點讚區隔END
			//table of comment
			$result = mysql_query("select * from `comment_sup` where `comment_id`='$feed_comments_id'");
			$row = mysql_fetch_array($result);
			if($row['comment_id']==null){
				//echo "creat feed comment</br>";
				mysql_query ("insert into `comment` values ('$feed_comments_id','$feed_comments_from','$feed_from','feed','$feed_id','$feed_comments_created_time','$feed_comments_tag','$feed_comment_like','$feed_comments_message')");
				mysql_query ("insert into `comment_sup` values ('$feed_comments_id','$feed_comments_from','$feed_from','feed','$feed_id','$feed_comments_created_time','1')");
			}
			else{
				//echo "modify feed comment</br>";
				mysql_query ("update `comment` set `time`='$feed_comments_created_time',`tag`='$feed_comments_tag',`like`='$str_comment_like1',`content`='$feed_comments_message' where `comment_id`='$feed_comments_id'");
			}
			$feed_comments_tag=null;
			$feed_comment_like=null;
			$feed_comments_i++;
		}				
		//table of po
		$result = mysql_query("select * from `ppo` where `po_id`='$feed_id'");
		$row = mysql_fetch_array($result);
		if($row['po_id']==null){
			//echo "creat po</br>";
			mysql_query ("insert into `ppo` values ('$feed_from','$feed_id','$feed_created_time','$feed_place','$feed_tag','$feed_like','$feed_message')");
		}
		else{
			//echo "modify po</br>";
			mysql_query ("update `ppo` set `like`='$str_like1' where `po_id`='$feed_id'");
		}
		$feed_tag=null;
		$feed_like=null;
		$feed_i++;
	}
	//自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end,自己的塗鴉牆end

	//情感辨認(4種)
	$temp0=array();
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
	
	
	//朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆,朋友牆
	$resultlow = mysql_query("select * from `associate_detail` where `id`='$id' ");
	while($rowlow = mysql_fetch_array($resultlow)){
		$fid = $rowlow['friend_id'];
		$fql = "SELECT post_id,description,message,type,like_info,created_time FROM stream WHERE source_id ='".$fid."'
				LIMIT 2";
		$fqlresult = $facebook->api(    
			array(   
				'method' => 'fql.query',    
				'query' => $fql  
			));
		echo "<img src='http://graph.facebook.com/".$fid."/picture'/>_".$fid."</br>";
		foreach($fqlresult as $wall){
			if($wall['like_info']['can_like']){
				//echo "post_id ".$wall['post_id']."<br>";
				$pid = $wall['post_id'];
				
				if($wall['description'] != null){
					//echo "description ".$wall['description']."<br>";
					$posts_message = $wall['description'];
				}
				else if($wall['message'] != null){
					//echo "message ".$wall['message']."<br>";
					$posts_message = $wall['message'];
				}
				
				$word = $posts_message;
				$type0 = 0;
				$type1 = 0;
				$type2 = 0;
				$type3 = 0;
				$type = 0;
				$mood = 0;
				if($word!=null){
					for( $i=0 ; $i<sizeof($temp0) ; $i++ )
						$type0 = $type0 + substr_count($word,$temp0[$i]);
					for( $i=0 ; $i<sizeof($temp1) ; $i++ )
						$type1 = $type1 + substr_count($word,$temp1[$i]);
					for( $i=0 ; $i<sizeof($temp2) ; $i++ )
						$type2 = $type2 + substr_count($word,$temp2[$i]);
					for( $i=0 ; $i<sizeof($temp3) ; $i++ )
						$type3 = $type3 + substr_count($word,$temp3[$i]);
						
					$happy = $type0+$type1;
					$sad = $type2+$type3;
					$type = $happy - $sad;
					if($type >=1 )
						$mood = 1;
					if($type >=2)
						$mood = 2;
					if($type <0)
						$mood = -1;
					if($type <-1)
						$mood = -2;	
				}
				$posts_message = str_replace('\'','\'\'',$posts_message);
				if($wall['description'] != null || $wall['message'] != null){
					//echo "created_time ".date( "Y/m/d H:i:s" , $wall['created_time'])."<br>";
					$time = date( "Y/m/d H:i:s" , $wall['created_time']);
					//if($wall['like_info']['user_likes'])
						//echo "You said this good.<br>";
					//echo "like_count : " . $wall['like_info']['like_count']."<br>";
					$like_num = $wall['like_info']['like_count'];
					$fql_in = "SELECT fromid,text,time FROM comment WHERE post_id = '".$pid."'
						ORDER BY time ASC LIMIT 300 OFFSET 0";
					$fqlresult_in = $facebook->api(    
						array(   
							'method' => 'fql.query',    
							'query' => $fql_in  
						));
					$commnet_num = 0;
					foreach($fqlresult_in as $comment){
						//if($comment['fromid'] == $id)
						//echo $comment['fromid']."-->".$comment['text']."<br>";
						//echo "created_time ".date( "Y/m/d H:i:s" , $comment['time'])."<br>";
						$c_content = $comment['text'];
						$c_time = date( "Y/m/d H:i:s" , $comment['time']);
						$fromid = $comment['fromid'];
						$commnet_num++;
						//$result = mysql_query("select * from `friend_wall_comment` where `from`='$fromid' and `time`='$c_time'");
						//$row = mysql_fetch_array($result);
						//mysql_query ("insert into `friend_wall_comment` values ('$fromid','$fid','$pid','$c_time','$c_content')");
					}
					echo "<br>";
					if( (!strstr($posts_message,'is using') && !strstr($posts_message,' used ') && !strstr($posts_message,'is now') && !strstr($posts_message,'are now') && !strstr($posts_message,' played ') && !strstr($posts_message,' playing ') && !strstr($posts_message,'on his own') && !strstr($posts_message,'on her own') && !strstr($posts_message,'went to') && !strstr($posts_message,'going to') && !strstr($posts_message,' likes ') && !strstr($posts_message,' was ') && !strstr($posts_message,' shared ') && !strstr($posts_message,' updated ') && !strstr($posts_message,' added ') && !strstr($posts_message,' tagged ') && !strstr($posts_message,' changed ') && !strstr($posts_message,' got ') && !strstr($posts_message,' uploaded ') && !strstr($posts_message,' created ') && !strstr($posts_message,' posted ') && !strstr($posts_message,' listed ') && !strstr($posts_message,' rated ') && !strstr($posts_message,' commented ') && !strstr($posts_message,' activated ') && !strstr($posts_message,' joined ') ) || !strstr($posts_message,'.')){
							$result = mysql_query("select * from `friend_wall` where `po_id`='$pid'");
							$row = mysql_fetch_array($result);
							if($row['from']==null)
								mysql_query ("insert into `friend_wall` values ('$fid','$pid','$time','$like_num','$commnet_num','$posts_message','$mood')");
							else
								mysql_query ("update `friend_wall` set `like_num`='$like_num',`comment_num`='$commnet_num',`content`='$posts_message' where `po_id`='$pid'");
					}
					//else
						//echo "系統訊息:".$posts_message."<br>";
				}
			}
		}			
	}
	//朋友牆end,朋友牆end,朋友牆end,朋友牆end,朋友牆end,朋友牆end,朋友牆end,朋友牆end,朋友牆end,朋友牆end,朋友牆end,朋友牆end
}
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