<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title><?php echo $error['type'] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE"/>
    <style type="text/css">
        *{
            padding: 0;
            margin: 0;
        }
        body{
            background: #eee;
        }
        div.box{
            width: 700px;
            height:300px;
            border:dashed 2px white;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            font-family:'微软雅黑',Arial;
            background: #f2f3f1;
        }
        div.box h1{
            color:#009CFF;
            font-size: 40px;
        }
        div.box .sorry{
            color: #009CFF;
            margin-top: 12px;
            font-size: 20px;
        }
        div.box .info{
            color:#858585;
            margin-top: 40px;
        }
        div.box .info span{
            color:red;
            font-size:20px;
        }
        div.box a.btn{
            width: 100px;
            height: 35px;
            background: #009CFF;
            display: block;
            color: #fff;
            text-align: center;
            line-height: 35px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="box">
    <h1>抱歉,<?php echo $error['type']?></h1>
    <div class="sorry">Sorry,the page you requested was wrong.</div>
    <div class="info"><span>哎呀,出错了!&nbsp;&nbsp;</span> 错误信息: <?php echo $error['message'] ?></div>
    <a class="btn" href="javascript:click_go();" target="_self">返回</a>
</div>

<script type="text/javascript">
    function click_go(){
        window.history.back();  //返回上一页
    }

</script>
</body>
</html>