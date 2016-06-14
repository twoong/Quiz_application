<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://ogp.me/ns/fb#">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta property="og:title" content="영어 레벨 테스트"/>
    <meta property="og:image" content="http://inspo82.cafe24.com/Quiz_English_w3/movie300.jpg"/>
    <meta property="og:description" content="이 사이트는 자신의 영어 레벨을 확인할 수 있는 문제가 있습니다. 열심히 풀어주시길 바랍니다."/>
    <meta property="og:url" content="http://inspo82.cafe24.com/Quiz_English_w3/"/>


    <title>영어 실력 테스트</title>

    <link rel="stylesheet" type="text/css" href="css/style.css"/>

    <meta name="viewport" content="width=device-width, initial-scale=1">


    <script Language="JavaScript">
        function check(x) {
            if (x.item.length == 0) {
                alert("문제를 풀어야 합니다.")
            }
        }
    </script>


</head>

<body>

<?php

// setting value
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
        $question[$level_idx][$sub_idx] = $row[$level_idx][$sub_idx]['question'];

        for ($choice_idx = 0; $choice_idx < $num_choice; $choice_idx++) {
            $e​xercise[$level_idx][$sub_idx][$choice_idx] = $row[$level_idx][$sub_idx]['answer' . ($choice_idx + 1)];
        }
    }

    //레벨당 문제 샘플에서 순서 랜덤하게 변
    for ($sub_question_idx = 0; $sub_question_idx < $num_sub_question; $sub_question_idx++) {
        $rand_question[$level_idx][] = mt_rand(1, count($question[$level_idx]));
        $rand_question[$level_idx] = array_unique($rand_question[$level_idx]);
        if (count($rand_question[$level_idx]) != $sub_question_idx + 1) {
            $sub_question_idx--;
        }
    }
    sort($rand_question[$level_idx]);
}

if (is_null($_POST['answers'])) {
    $total_answers = 0;
    $cur_level = 0;
    $cur_qnumber = 1;
    $cur_sub_question = 0;
} else {
    $cur_qnumber = $_POST['qnumber']++;
    $cur_sub_question = $_POST['cur_sub_question'];
    $cur_level = $_POST['cur_level'];
    $total_answers = $_POST['answers'] + pow(10, ($cur_qnumber - 1)) * $_POST['question-' . ($cur_sub_question + $cur_level + 1) . '-answers'];
    $cur_sub_question++;
    if ($cur_sub_question == $num_sub_question) {
        $cur_sub_question = 0;
        $cur_level++;
    }
    $cur_qnumber++;
    $rand_question = $_POST['rand_order'];
}
?>
<div id='page-wrap'>

<h1>빅 데이터로 확인하는 내 진짜 영어실력!</h1>

<?php
if ($cur_qnumber == $num_total_question) {
    echo "<form action='grade.php' method='post' id='quiz'>";
} else {
    echo "<form action='index.php' method='post' id='quiz'>";
}
?>


<div class="tw-padding-jumbo tw-light-grey">
    <h2>
        <?php
        echo "Q" . ($cur_sub_question + $cur_level + 1) . ". " . $question[$cur_level][$rand_question[$cur_level][$cur_sub_question] - 1] . "<br>";
        ?>
    </h2>
    <?php
    for ($choice_idx = 0; $choice_idx < $num_choice; $choice_idx++) {
        echo "<div id='choice'>";
        echo "<input type=radio name=question-" . ($cur_sub_question + $cur_level + 1) . "-answers id=question-" . ($cur_sub_question + $cur_level + 1) . "-answers-" . ($choice_idx + 1) . " value=" . ($choice_idx + 1) . " />";
        echo "<label for=question-" . ($cur_sub_question + $cur_level + 1) . "-answers-" . ($choice_idx + 1) . ">";
        echo " " . $e​xercise[$cur_level][$rand_question[$cur_level][$cur_sub_question] - 1][$choice_idx] . "<br>";
    echo "</label>";
    echo "</div>";
}
    ?>

    <div id='button'>
        <input type='submit' value='NEXT'/>
    </div>
</div>
</br>


<?php
echo "<input type='hidden' name='order" . ($cur_level + 1) . "_" . ($cur_sub_question + 1) . "' value=" . $rand_question[$cur_level][$cur_sub_question] . ">";

for ($level_idx = 0; $level_idx < $num_total_level; $level_idx++) {
    for ($sub_question_idx = 0; $sub_question_idx < $num_sub_question; $sub_question_idx++) {
        echo "<input type='hidden' name='rand_order[$level_idx][$sub_question_idx]' value=" . $rand_question[$level_idx][$sub_question_idx] . ">";
    }
}

echo "<input type='hidden' name='qnumber' value=" . $cur_qnumber . ">";
echo "<input type='hidden' name='answers' value=" . $total_answers . ">";
echo "<input type='hidden' name='cur_sub_question' value=" . $cur_sub_question . ">";
echo "<input type='hidden' name='cur_level' value=" . $cur_level . ">";
?>


</form>

<script type="text/javascript">
    var t = document.getElementById('quiz');
    t.addEventListener('submit', function (event) {
        if (!((document.getElementById('<?php echo "question-" . ($cur_sub_question + $cur_level + 1) . "-answers-1" ?>').checked)
            || (document.getElementById('<?php echo "question-" . ($cur_sub_question + $cur_level + 1) . "-answers-2" ?>').checked)
            || (document.getElementById('<?php echo "question-" . ($cur_sub_question + $cur_level + 1) . "-answers-3" ?>').checked)
            || (document.getElementById('<?php echo "question-" . ($cur_sub_question + $cur_level + 1) . "-answers-4" ?>').checked)
            || (document.getElementById('<?php echo "question-" . ($cur_sub_question + $cur_level + 1) . "-answers-5" ?>').checked))) {
            alert('문제를 풀어야 합니다.');
            event.preventDefault();
        }
    });
</script>

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
