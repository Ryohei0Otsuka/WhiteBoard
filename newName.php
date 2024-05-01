<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録画面</title>
    <link rel="stylesheet" href="css01.css?<?php echo date('YmdHis'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    $newName = $_POST["newName"];
    $sts     = ["在所", "外出", "退勤"];
    $name = $newName;
    ?>

    <div class="wrapper">
        <h3 class="inform">新規追加入力フォーム</h3>
        <div class="edit">

            <form action="insert.php" class="form_id" method="post">
                <div>
                    <span class="name_mgn"><label for="name_select" class="">名前</label></span><br>
                    <select name="name" id="name_select" class="name_select" required>
                        <?php echo '<option selected value="' . $name . '">' . $name . '</option>'; ?>
                    </select>
                </div>

                <div class="sts_sp">

                    <span class="sts_mgn"><label for="sts_select" class="">ステータス<span style="color: red;">※</span></label></span><br>
                    <select name="status" class="sts_select" id="sts_select" required>
                        <option value="" disabled selected>ステータスを選択</option>
                        <?php
                        foreach ($sts as $s) {
                            echo '<option value="' . $s . '">' . $s . '</option>';
                        }
                        ?>
                    </select>
                    </p>
                </div>

                <div class="time_in">
                    <p>
                        <span class="time_mgn"><label for="time01">時間(15分単位で入力)</label></span><br>
                        <span class="time_sp2">
                            <input type="time" name="time01" class="time_i1" id="time01" step="900">
                            <span class="time0">～</span>
                            <input type="time" name="time02" class="time_i1" id="time02" step="900">
                        </span>
                    </p>
                </div>

                <div class="text_sp">
                    <p class="ykn_mgn2">
                        <span class="ykn_mgn"><label class="ykn_mgn" for="text">用件(30文字以内で入力)</label></span><br>
                        <input type="text" class="text" id="text" name="youken" maxlength="30">
                    </p>
                </div>
        </div>
        <div class="hissu">
            <span style="color: red;">※</span>は入力必須項目
        </div>
        <p>
            <input type="submit" class="inbtn" value="送信する">
        </p>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        //バリデーション

        //外出時のみ時刻入力必須及び時間矛盾

        $('#sts_select').change(function() {
            let sts = $('#sts_select').val();
            if (sts == '外出') {
                $('.inbtn').prop('disabled', true).css('background-color', '#ff0000').val('送信できません');
            }
        });

        $('#sts_select').change(function() {
            let sts = $('#sts_select').val();
            if (sts == '在所' || sts == '退勤') {
                $(".inbtn").prop('disabled', false).css('background-color', '#333333').val('送信する');
            }
        });

        $('#time01,#time02,#sts_select').change(function() {
            let s_time = $('#time01').val();
            let r_time = $('#time02').val();
            let sts = $('#sts_select').val();
            if (sts == '外出') {
                if (s_time != '' && r_time != '' && s_time < r_time) {
                    $(".inbtn").prop("disabled", false).css('background-color', '#333333').val('送信する');
                } else {
                    $('.inbtn').prop('disabled', true).css('background-color', '#ff0000').val('送信できません');
                }
            }
            console.log(sts);
            console.log(s_time);
            console.log(r_time);
        });

        //用件30文字アラート
        $("#text").change(function() {
            let youken = $("#text").val();
            if (youken.length > 30) {
                alert("30文字以内で入力してください。");
            }
            console.log(youken);
        });
    </script>
</body>

</html>