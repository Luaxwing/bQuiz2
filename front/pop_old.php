<fieldset>
    <legend>目前位置:首頁>最新文章區</legend>
    <table class="myTable">
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

        $rows = $News->all(['sh' => 1], " order by `good` DESC limit $start,$div");
        foreach ($rows as $row) {
            ?>

            <tr>
                <td class="myTitle">
                    <!-- <div class="myBtn" data-id="<?= $row['id'] ?>" > -->
                    <?=
                        $row['title'];
                    ?>
                    <!-- </div> -->
                </td>
                <td>
                    <div class="myBtn" data-id="<?= $row['id'] ?>">
                        <div id="s<?= $row['id'] ?>">
                            <?=
                                mb_substr($row['news'], 0, 25);
                            ?>...
                        </div>
                        <div id="ex<?= $row['id'] ?>" style="display:none">
                            <?= $row['news'] ?>
                        </div>
                    </div>
                </td>
                <td>
                    <?=
                        $row['good'];
                    ?>
                </td>
            </tr>

            <?php
        }


        ?>

    </table>
    <!-- 換頁標籤 -->
    <?php pagetabs($now, $pages, $_GET['do']); ?>
</fieldset>


<script>

    // $(".myBtn").on('click',(e)=>{
    //     let id=$(e.target).data('id');
    // $("#ex"+id).toggle();
    // $(`#s${id}`).toggle();

    // console.log("#ex"+id);
    // console.log($(`#s${id}`));
    // })


    $(".myBtn").on('click', function () {
        let id = $(this).data('id');
        // $("#ex" + id).toggle();
        // $(`#s${id}`).toggle();
        $(`#ex${id},#s${id}`).toggle();
   

        console.log("#ex" + id);
        console.log($(`#s${id}`));
    })


    

</script>