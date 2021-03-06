<html>
<head>
	<title>Book Management System For DataBase Lab</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="../css/page.css">
    <script src="../js/common.js"></script>
</head>
<body>
    <h1 class="title">流水统计</h1>
<?php
    include 'connect.php';
    $RoomNoErr ="";$idErr ="";$nameErr ="";$OutTimeErr ="";$dayErr ="";
    $RoomNo=$id=$name=$OutTime=$day=$price=$No=$employeeid=$InTime=$account="";
    $num=0;
    $tbool=true;
    $startre=false;
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $startre=true;
        if (empty($_POST["RoomNo"]))
        {
            $RoomNoErr = "必填";
            $tbool=false;
        }
        else    
        {
            $RoomNo = test_input($_POST["RoomNo"]);
        }

        if (empty($_POST["id"]))
        {
            $idErr = "必填";
            $tbool=false;
        }
        else    
        {
            $id = test_input($_POST["id"]);
        }

        if (empty($_POST["name"]))
        {
            $nameErr = "必填";
            $tbool=false;
        }
        else    
        {
            $name = test_input($_POST["name"]);
        }

        if (empty($_POST["OutTime"]))
        {
            $OutTimeErr = "必填";
            $tbool=false;
        }
        else    
        {
            $OutTime = test_input($_POST["OutTime"]);
        }

        if (empty($_POST["day"]))
        {
            $dayErr = "必填";
            $tbool=false;
        }
        else    
        {
            $day = test_input($_POST["day"]);
        }
    }

    if($tbool && $startre){
        //首先看看是否已经存在顾客信息，如果存在，提示已登记,否则就插入新记录
        $sql_query="select * from checkout where 身份证号 ='".$id."'";
        $sql=$conn->query($sql_query);
        $info=mysqli_fetch_array($sql);
        if($info==false){
            //并不存在这一顾客
            //插入退房表
            $sql_query2="insert into checkout values('".$RoomNo."','".$id."','".$name."',
            '".$OutTime."','".$day."')";
            $conn->query($sql_query2);
            $num=$num+1;
            $No=strval($num);
            //查询入住时间
            $sql_query3="select 入住时间 from checkin where 身份证号 ='".$id."'";
            $sql1=$conn->query($sql_query3);
            $info1=mysqli_fetch_assoc($sql1);
            $InTime=$info1["入住时间"];
            //查询员工编号
            $sql_query4="select 服务的员工编号 from custmerinfo where 身份证号 ='".$id."'";
            $sql2=$conn->query($sql_query4);
            $info2=mysqli_fetch_assoc($sql2);
            $employeeid=$info2["服务的员工编号"];
            //查询价格
            $sql_query5="select 价格 from roominfo where 房间号 ='".$RoomNo."'";
            $sql3=$conn->query($sql_query5);
            $info3=mysqli_fetch_assoc($sql3);
            $price=$info3["价格"];
            //删除记录
            $sql_query6="delete from checkin where 房间号='".$RoomNo."'";
            $conn->query($sql_query6);
            //改变客房状态
            $sql_query7="update roomstatus set 状态='未入住' where 房间号='".$RoomNo."'";
            $conn->query($sql_query7);
            //将所有信息插入订单
            $account=strval(intval($price)*intval($day));

            $sql_query1="insert into orderinfo values('".$No."','".$id."','".$name."','".$RoomNo."',
            '".$employeeid."','".$price."','".$InTime."','".$OutTime."','".$account."')";
            $conn->query($sql_query1);
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<header>
<div id="sinsert">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off">
    <div class="block">
        <label class="font">房间号:</label><input type="text" name="RoomNo">
        <span class="error" style="color:brown"><?php echo $RoomNoErr;?></span>
    </div>
    <div class="block">
        <label class="font">身份证号:</label><input type="text" name="id">
        <span class="error" style="color:brown"><?php echo $idErr;?></span>
    </div>
    <div class="block">
        <label class="font">姓名:</label><input type="text" name="name">
        <span class="error" style="color:brown"><?php echo $nameErr;?></span>
    </div>
    <div class="block">
        <label class="font">退房时间:</label><input type="date" name="OutTime">
        <span class="error" style="color:brown"><?php echo $OutTimeErr;?></span>
    </div>
    <div class="block">
        <label class="font">天数:</label><input type="text" name="day">
        <span class="error" style="color:brown"><?php echo $dayErr;?></span>
    </div>
    <div>
        <input class="button" type= "submit" name="submit" value="Checkout" onclick="information()">
    </div>
</form>
</div>
</header>
</body>

</div>
<div id='information' style="margin-top: 400px">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div>
        <label class="font">订单编号: </label>
        <span class="error" style="color:brown"><?php echo $No;?></span>
        </div>
        <div>
        <label class="font">身份证号：</label>
        <span class="error" style="color:brown"><?php echo $id;?></span>
        </div>
        <div>
        <label class="font">姓名：</label>
        <span class="error" style="color:brown"><?php echo $name;?></span>
        </div>
        <div>
        <label class="font">房间号：</label>
        <span class="error" style="color:brown"><?php echo $RoomNo;?></span>
        </div>
        <div>
        <label class="font">员工编号：</label>
        <span class="error" style="color:brown"><?php echo $employeeid;?></span>
        </div>
        <div>
        <label class="font">价格：</label>
        <span class="error" style="color:brown"><?php echo $price;?></span>
        </div>
        <div>
        <label class="font">入住时间: </label>
        <span class="error" style="color:brown"><?php echo $InTime;?></span>
        </div>
        <div>
        <label class="font">退房时间: </label>
        <span class="error" style="color:brown"><?php echo $OutTime;?></span>
        </div>
        <div>
        <label class="font">总金额: </label>
        <span class="error" style="color:brown"><?php echo $account;?></span>
        </div>
    </form>
</div>
</html>