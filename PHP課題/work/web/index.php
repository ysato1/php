<?php

require("../app/functions.php");

createToken();

define('FILENAME', '../app/messages.txt');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();

  $name = trim(filter_input(INPUT_POST, 'name'));
  $email = trim(filter_input(INPUT_POST, 'email'));

  $message = trim(filter_input(INPUT_POST, 'message'));
  // $message = $message !== '' ? $message : '...';

  $weight = trim(filter_input(INPUT_POST, 'weight'));

  $data = "$name,$email,$weight,$message. \n";


  $fp = fopen(FILENAME, 'a');
  fwrite($fp, $data);
  fclose($fp);

  header('Location: http://localhost:8080/result.php');
  exit;
}

$messages = file(FILENAME, FILE_IGNORE_NEW_LINES);

include('../app/_parts/_header.php');


?>

<h1>健康管理</h1>
<form action="" method="post" onsubmit="return validateForm();">
  <label for="name">名前</label>
  <input type="text" name="name" id="name">

  <label for="email">メールアドレス</label>
  <input type="email" name="email" id="email">

  <label for="weight">体重</label>
  <input type="number" name="weight" id="weight">

  <label for="message">今の気分を一言で！</label>
  <textarea name="message" id="message"></textarea>
  <button>Send</button>
  <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
  <!-- <a href="reset.php">[reset]</a> -->
</form>

入力内容表示
<table>
  <tr>
    <th>名前</th>
    <th>メールアドレス</th>
    <th>体重</th>
    <th>メッセージ</th>
  </tr>
  <?php foreach ($messages as $message) : ?>
    <?php list($name, $email, $weight, $messageText) = explode(',', $message); ?>
    <tr>
      <td><?= h($name); ?></td>
      <td><?= h($email); ?></td>
      <td><?= h($weight); ?></td>
      <td><?= h($messageText); ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<canvas id="myChart"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // 名前、体重のデータを取得
  let messages = <?php echo json_encode($messages); ?>;
  let labels = [];
  let weightData = [];

  // メッセージから体重のデータを抽出
  for (let i = 0; i < messages.length; i++) {
    let parts = messages[i].split(',');
    let name = parts[0]; // 名前
    let weight = parts[2]; // 体重
    labels.push(name); // 名前を配列に追加
    weightData.push(weight);
  }

  // Chart.jsのコード（ループの外に移動）
  let ctx = document.getElementById('myChart').getContext('2d');
  let myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: '体重',
        data: weightData,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });




  //入力内容チェック
  function validateForm() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const weightInput = document.getElementById('weight');
    const messageInput = document.getElementById('message');

    if (nameInput.value === '') {
      alert('名前を入力してください');
      return false;
    }

    if (emailInput.value === '') {
      alert('メールアドレスを入力してください');
      return false;
    }

    if (weightInput.value === null) {
      alert('体重を入力してください');
      return false;
    }

    if (messageInput.value === '') {
      alert('メッセージを入力してください');
      return false;
    }

    return true;
  }
</script>


<?php



include('../app/_parts/_footer.php');
