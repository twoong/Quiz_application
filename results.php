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
    <h1>정답률 결과 확인</h1>
    <?php

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

    for ($num_question = 0; $num_question < 10; $num_question++) {
        $sql[$num_question] = "SELECT * FROM level_" . ($num_question + 1);
        $result[$num_question] = mysql_query($sql[$num_question]);

        if (!$result[$num_question]) {
            echo "Could not successfully run query ($sql) from DB: " . mysql_error();
            exit;
        }

        if (mysql_num_rows($result[$num_question]) == 0) {
            echo "No rows found, nothing to print so am exiting";
            exit;
        }

        echo "<div id='results'> Level " . ($num_question + 1) . "</div>";

        for ($num_subquestion = 0; $num_subquestion < 3; $num_subquestion++) {
            $row[$num_question][$num_subquestion] = mysql_fetch_assoc($result[$num_question]);
            $question[$num_question][$num_subquestion] = $row[$num_question][$num_subquestion]['question'];
            $total_cnt[$num_question][$num_subquestion] = $row[$num_question][$num_subquestion]['total'];
            $correct_cnt[$num_question][$num_subquestion] = $row[$num_question][$num_subquestion]['correct'];


            echo "<div id='results2'>" . $question[$num_question][$num_subquestion]
                . " 정답률 " . $correct_cnt[$num_question][$num_subquestion] . "/" . $total_cnt[$num_question][$num_subquestion]
                . " (" . round($correct_cnt[$num_question][$num_subquestion] / $total_cnt[$num_question][$num_subquestion] * 100) . "%)</div>";

        }

        echo "<div id='results2'>" . round(100 * ($correct_cnt[$num_question][0] + $correct_cnt[$num_question][1] + $correct_cnt[$num_question][2])
                / ($total_cnt[$num_question][0] + $total_cnt[$num_question][1] + $total_cnt[$num_question][2])) . "%</div>";

        echo "</br>";
    }
    echo "<div id='results2'> 총 참석자수 " . ($total_cnt[0][0] + $total_cnt[0][1] + $total_cnt[0][2]) . "</div>";

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