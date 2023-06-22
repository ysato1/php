<?php

require('../app/functions.php');

// $colorFromGet = filter_input(INPUT_GET, "color") ?? "transparent";
// setcookie("color", $colorFromGet);


include('../app/_parts/_header.php');

?>


<p>入力が完了しました!</p>
<!-- <p><?= nl2br(h($message)); ?> </p> -->
<p><a href="index.php">フォームに戻る</a></p>

<?php

include('../app/_parts/_footer.php');