<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>行先掲示板</title>
    <link rel="stylesheet" href="css01.css?<?php echo date('YmdHis'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <main>
            <h1>行先掲示板</h1>
            <?php
            $name = ["木下　吉彦", "遠藤　隆幸", "沖中　宏之", "澤村　駿", "土橋　高明", "鶴岡　祐樹", "佐々木　千鶴", "中山　菜都美", "渡辺　亜美", "大森　満"];
            $sts = ["在所", "外出", "退勤"];
            ?>

            <div class="top">
                <p><time class="time" id="now_time"></time></p>
                <nav class="nav">
                    <button class="inputbtn" id="inputbtn">入力フォームへ↓</button>
                    <button type="button" class="reload" id="reload">最新の情報に更新</button>
                </nav>
            </div>

            <div class="list">
                <?php
                include "funcs.php";
                $pdo = db_con();
                $stmt = $pdo->prepare("SELECT * FROM wb");
                $status = $stmt->execute();

                $view = '';
                if ($status == false) {
                    sqlError($stmt);
                } else {
                    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $status_class = '';
                        switch ($result['status']) {
                            case '在所':
                                $status_class = 'zaisho';
                                break;
                            case '外出':
                                $status_class = 'gaishutu';
                                break;
                            case '退勤':
                                $status_class = 'taikin';
                                break;
                        }

                        $time01 = new DateTime($result["time01"]);
                        $time01_f = $time01->format('H:i');
                        $time02 = new DateTime($result["time02"]);
                        $time02_f = $time02->format('H:i');

                        if ($status_class == 'zaisho' || $status_class == 'taikin') {
                            $time01_f = '';
                            $time02_f = '';
                        }


                        $view .= '<div class="list_1">';
                        $view .= '<div><span id="status" class="jt-' . $status_class . '">' . $result["status"] . '</div>';
                        $view .= '<div class="list_3"><span class="name">' . $result["name"] . '</span></span>';
                        $view .= '<div class="list_2"><span class="time_sp"><span class="time01">' . $time01_f . '</span>' . '<span class="until"> ～ </span>' . '<span class="time02">' . $time02_f . '</span></span>' . '<span class="youken">' .  $result["youken"] . '</span></div></div>';
                        $view .= '</div>';
                    }
                }
                ?>
                <?= $view ?>

            </div>
            <div class="edit_sp">
            <h3 class="inform">入力フォーム</h3>

            <div>

                <form action="insert.php" method="post" class="form_id" id="form_id">
                    <div>
                        <p>
                            <span class="name_mgn"><label class="nameLabel" for="name_select">名前<span style="color: red;">※</span></label></span><br>
                            <select name="name" class="name_select" id="name_select" required>
                                <option value="" disabled selected>名前を選択</option>
                                <?php
                                foreach ($name as $n) {
                                    echo '<option value="' . $n . '">' . $n . '</option>';
                                }
                                ?>
                            </select>
                        </p>
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

                    <div class="hissu">
                        <span style="color: red;">※</span>は入力必須項目
                    </div>
                    <p>
                        <input type="submit" class="inbtn" value="送信する">
                    </p>
                </form>
            </div>

            <h3 class="newName_title">新規追加</h3>
            <div class="newName" action>
                <form action="newName.php" method="post">
                    <div class="newName_in">
                        <label for="new">名前(15文字以内で入力)</label><br>
                        <input name="newName" type="text" class="new" id="new" maxlength="15" required>
                    </div>
                    <p>
                        <input type="submit" class="newbtn" value="送信する">
                    </p>
                </form>
            </div>


            <h3 class="delete_title">削除</h3>
            <div class="delete">
                <form action="delete.php" method="post">
                    <div class="dltCenter">
                        <select name="name_dlt" class="select_dlt">
                            <option disabled selected>名前を選択</option>
                            <?php

                            $pdo = db_con();
                            $stmt = $pdo->prepare("SELECT * FROM wb");
                            $status = $stmt->execute();

                            $dlt = '';
                            if ($status == false) {
                                sqlError($stmt);
                            } else {
                                //Selectデータの数だけ自動でループしてくれる
                                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $dlt = $result["name"];
                                    echo '<option value="' . $dlt . '">' . $dlt . '</option>';
                                }
                            }

                            ?>
                        </select>
                    </div>
                    <p>
                        <input type="submit" class="dltbtn" value="送信する">
                    </p>
                </form>
            </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        //時刻表示
        setInterval(function() {
            //インスタンス化
            let ins = new Date();

            //日付
            let y = ins.getFullYear();
            let M = ins.getMonth() + 1;
            let d = ins.getDate();

            //時間
            let h = ('0' + ins.getHours()).slice(-2);
            let m = ('0' + ins.getMinutes()).slice(-2);
            let s = ('0' + ins.getSeconds()).slice(-2);

            //JQueryでdivに表示
            $('.time').text(y + "年" + M + "月" + d + "日　" + h + ":" + m + ":" + s);
        }, 100)

        $("#reload").on("click", function() {
            location.reload();
        });

        $("#inputbtn").on("click", function() {
            $("html,body").animate({
                scrollTop: $('h3').offset().top
            });
        });

        //バリデーション

        //用件30文字アラート
        $("#text").change(function() {
            let youken = $("#text").val();
            if (youken.length > 30) {
                alert("30文字以内で入力してください。");
            }
            console.log(youken);
        });

        //-----------ChatGPT使用--------------------------------------------------------------------------------
        <?php
        $stmt = $pdo->prepare("SELECT * FROM wb");
        $status = $stmt->execute();

        if ($status == false) {
            sqlError($stmt);
        } else {
            $x_name = array(); // 配列を初期化

            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $x_name[] = $result['name'];
            }

            // JSONエンコード
            $param_json = json_encode($x_name, JSON_HEX_QUOT | JSON_HEX_TAG);

            // データが存在するか確認してJavaScriptに渡す
            echo "let param = " . (!empty($x_name) ? "JSON.parse('" . $param_json . "')" : "null") . ";"; //三項演算子を初めて知った。
        }
        ?>
        //--------------------------------------------------------------------------------------------------------
        console.log(param);

        if (param == null) {
            $('#time01, #time02, #sts_select').change(function() {
                let sts = $('#sts_select').val();
                let s_time = $('#time01').val();
                let r_time = $('#time02').val();

                if (sts == '外出') {
                    $('.inbtn').prop('disabled', true).css('background-color', '#ff0000').val('送信できません');
                    if (s_time != '' && r_time != '' && s_time < r_time) {
                        $('.inbtn').prop('disabled', false).css('background-color', '#333333').val('送信する');
                    }
                }
            });
        }

        $('#name_select, #time01, #time02, #sts_select').change(function() {
            let s_name = $('#name_select').val();
            let sts = $('#sts_select').val();
            let s_time = $('#time01').val();
            let r_time = $('#time02').val();

            if ($.inArray(s_name, param) != -1) {
                $('.inbtn').prop('disabled', true).css('background-color', '#ff0000').val('送信できません');
                if (sts == '外出' || sts == '在所' || sts == '退勤') {
                    $('.inbtn').prop('disabled', true).css('background-color', '#ff0000').val('送信できません');
                }
            }
            if ($.inArray(s_name, param) == -1) {
                $('.inbtn').prop('disabled', false).css('background-color', '#333333').val('送信する');
                if (sts == '外出') {
                    $('.inbtn').prop('disabled', true).css('background-color', '#ff0000').val('送信できません');
                    if (s_time != '' && r_time != '' && s_time < r_time) {
                        $('.inbtn').prop('disabled', false).css('background-color', '#333333').val('送信する');
                    }
                }
            }

            console.log(s_name);
            console.log(sts);
            console.log(s_time);
            console.log(r_time);
        });

        $('#new').change(function() {
            let n_name = $('#new').val();
            param
            if ($.inArray(n_name, param) != -1) {
                $('.newbtn').prop('disabled', true).css('background-color', '#ff0000').val('送信できません');
            } else {
                $('.newbtn').prop('disabled', false).css('background-color', '#333333').val('送信する');
            }
            console.log(n_name);
            console.log(param);
        });
    </script>
</body>

</html>