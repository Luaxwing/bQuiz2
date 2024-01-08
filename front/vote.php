<?php

$que=$Que->find($_GET['id']);

?>
<fieldset>
    <legend>目前位置:首頁 > 問卷調查 > <?=$que['text'];?></legend>
<h3><?=$que['text'];?></h3>

<form action="./api/vote.php" method="post">
<?php 

$opts=$Que->all(['subject_id'=>$_GET['id']]);
foreach($opts as $key=>$opt){
    echo "<div>";
    $checkOpt=($key==0)?"checked":"";
    echo "<input type='radio' id='vote[]' name='opt' value='{$opt['id']}' $checkOpt>";
    echo $opt['text'];
    echo "</div>";
}
?>
<div class="ct">
    <input type="submit" value="我要投票">
</div>
</form>
</fieldset>