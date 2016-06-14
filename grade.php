<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>영어 실력 테스트</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div id="page-wrap">
    <h1>나의 영어 레벨 테스트 결과</h1>
    <?php

    $num_total_question = 10;   //총 문제수
    $num_total_level = 10;      //레벨 수
    $num_sub_sample = 3;        //레벨당 문제샘플수
    $num_sub_question = 1;      //레벨당 문제수
    $num_choice = 5;            //보기

    if (0) {
        $conn = mysql_connect("localhost", "inspo82", "lifestudy82");
    } else {
        $conn = mysql_connect("localhost", "root", "");
    }
    mysql_query('SET NAMES utf8');

    if (!$conn) {
        echo "Unable to connect to DB: " . mysql_error();
        exit;
    }

    if (!mysql_select_db("inspo82")) {
        echo "Unable to select mydbname: " . mysql_error();
        exit;
    }


    for ($level_idx = 0; $level_idx < $num_total_level; $level_idx++) {

        $sql[$level_idx] = "SELECT * FROM level_" . ($level_idx + 1);

        $result[$level_idx] = mysql_query($sql[$level_idx]);

        if (!$result[$level_idx]) {
            echo "Could not successfully run query ($sql) from DB: " . mysql_error();
            exit;
        }

        if (mysql_num_rows($result[$level_idx]) == 0) {
            echo "No rows found, nothing to print so am exiting";
            exit;
        }

        for ($sub_idx = 0; $sub_idx < $num_sub_sample; $sub_idx++) {
            $row[$level_idx][$sub_idx] = mysql_fetch_assoc($result[$level_idx]);
            $right_answer[$level_idx][$sub_idx] = $row[$level_idx][$sub_idx]['good'];
        }
    }

    $rand_question = $_POST['rand_order'];
    $totalCorrect = 0;
    $totalScore = 0;

    for ($level_idx = 0; $level_idx < $num_total_level; $level_idx++) {

        if ($level_idx < 3) {
            $score = 5;
        } else if ($level_idx < 7) {
            $score = 10;
        } else {
            $score = 15;
        }

        for ($sub_question_idx = 0; $sub_question_idx < $num_sub_question; $sub_question_idx++) {
            $sql_score[($level_idx * $num_sub_question) + $sub_question_idx] = "UPDATE level_" . ($level_idx + 1) . " SET total = total+1 WHERE id =  " . $rand_question[$level_idx][$sub_question_idx];

            $sql_result[$level_idx] = mysql_query($sql_score[($level_idx * $num_sub_question) + $sub_question_idx]);

            $cur_answer = ($_POST['answers'] / pow(10, $level_idx)) % 10;

            if ($cur_answer == $right_answer[$level_idx][$rand_question[$level_idx][$sub_question_idx] - 1]) {
                $totalCorrect++;
                $totalScore = $totalScore + $score;
                $sql_correct = "UPDATE level_" . ($level_idx + 1) . " SET correct = correct+1 WHERE id =  " . $rand_question[$level_idx][$sub_question_idx];
                $sql_cor_result = mysql_query($sql_correct);
            }
        }
    }

    echo "<div id='results'>$totalScore / 100 점입니다 </div>";
    echo "</br>";
    echo "<div id='results1'>이 테스트는 7월 출간 예정인 BIGVOCA 단어장을 기반으로 만들어졌습니다. 8000개의 표제어만 외워도 원어민이 쓰는 단어 빈도의 90%를 알게 됩니다. 단어만 제대로 외우면 영어는 반이상 정복됩니다. </br> 모두 화이팅! </div>";

    ?>

</div>

<script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
    var pageTracker = _gat._getTracker("UA-68528-29");
    pageTracker._initData();
    pageTracker._trackPageview();
</script>

</body>

</html>