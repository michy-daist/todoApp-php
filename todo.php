<?php

//エスケープ処理
function h($s){
  return htmlspecialchars($s,ENT_QUOTES,'UTF-8');
}

//変数準備
$FILE = 'todoList.txt';

//ランダムにidを作る
$id=uniqid();

//タイムゾーン
date_default_timezone_set('Japan');
$date = date('Y年m月d日H時i分');

$text = '';

//一回分の投稿データ
$DATUM = [];

//全ての投稿データ
$DATA = [];

//file exists
if (file_exists($FILE)) {
  // code...
  $DATA = json_decode(file_get_contents($FILE));
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

  //リクエストが空でない場合
  if(!empty($_POST['txt'])){
    $text = $_POST['txt'];

    $DATUM = [$id, $date, $text];
    //$DATA = array();
    $DATA[] = $DATUM;

    //全体をファイルに保存
    file_put_contents($FILE,json_encode($DATA));
  }else if (isset($_POST['del'])) {
    // code...
    $NEWDATA=[];

    foreach ($DATA as $DATUM) {
      // code...
      if($DATUM[0] !== $_POST['del']){
        $NEWDATA[] = $DATUM;

      }
    }
    file_put_contents($FILE,json_encode($NEWDATA));
  }

  header('LOCATION: '.$_SERVER['SCRIPT_NAME']);
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>todoApp</title>
  </head>
  <body>
    <h1>タスク管理アプリ</h1>

    <section class="main">
      <h2>追加する</h2>

      <form method="post">
        <input type="text" name="txt">
        <input type="submit" value="投稿" >
      </form>

      <table style="border-collapse: collapse">
      <?php foreach($DATA as $DATUM): ?>
        <tr>
        <form method="post">
        <td>
          <!-- テキスト -->
          <?php echo h($DATUM[2]); ?>
        </td>
        <td>
          <!-- 日時 -->
          <?php echo h($DATUM[1]); ?>
        </td>
        <td>
          <!-- 削除 -->
          <!--投稿のIDがサーバーへ-->
          <input type="hidden" name="del" value="<?php echo $DATUM[0]; ?>">
          <input type="submit" value="削除">
        </td>
       </form>
       </tr>
     <?php endforeach; ?>
      </table>
    </section>
  </body>
</html>
