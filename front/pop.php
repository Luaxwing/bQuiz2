<fieldset>
    <legend>目前位置:首頁 > 人氣文章區</legend>
    <table style="width:95%;margin:auto">
        <tr>
            <th width="30%">標題</th>
            <th width="50%">內容</th>
            <th>人氣</th>
        </tr>
        <?php
        $total = $News->count();
        $div = 3;
        $pageInit = pageInit($total, $div);
        $pages = $pageInit['pages'];
        $now = $pageInit['now'];
        $start = $pageInit['start'];

        $rows = $News->all(['sh' => 1], " order by `good` desc limit $start,$div");
        foreach ($rows as $row) {
            ?>
            <tr>
                <td>
                    <div class='title' width="100%">
                        <?= $row['title']; ?>
                    </div>
                </td>
                <td style="position: relative;">
                    <div class="myNews" data-id="<?= $row['id']; ?>">
                        <div>
                            <?= mb_substr($row['news'], 0, 25); ?>...
                        </div>
                        <div id="p<?= $row['id']; ?>" class="pop">
                            <h3 style='color:skyblue'>
                                <?= $row['title']; ?>
                            </h3>
                            <pre><?= $row['news']; ?></pre>
                        </div>
                    </div>
                </td>
                <td><span id='g<?= $row['id'] ?>'>

                        <?=
                            $row['good']
                            ?>
                        個人說
                        <img src="./icon/02B03.jpg" alt="" srcset="" width="20px">

                    </span>

                    <?php
                    if (isset($_SESSION['user'])) {
                        if ($Log->count(['news' => $row['id'], 'acc' => $_SESSION['user']]) > 0) {
                            echo "<a id='n{$row['id']}' href='Javascript:good({$row['id']})'>收回讚</a>";
                        } else {
                            echo "<a id='n{$row['id']}' href='Javascript:good({$row['id']})'>讚</a>";
                        }
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <div>
        <!-- 換頁標籤 -->
        <?php pagetabs($now, $pages, $_GET['do']); ?>
    </div>
</fieldset>
<script>
    $("table,.title").hover(
        function () {
            $(".pop").hide()
        }
    )

    $(".myNews").hover(
        function () {
            $(".pop").hide()
            let id = $(this).data("id")
            $("#p" + id).show();
        }
    )

</script>