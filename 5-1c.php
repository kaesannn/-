      <!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>mission_3-5</title>
    </head>
    <body>
        
   <font size="5" font color="#4682B4"><strong>データベース 簡易掲示板</strong></font><br>
   ・投稿番号とパスワードは全角・半角の区別があります。</br>
   ・編集時にパスワードは上書きできます。<br>
   <br>
   
    <?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //tableをつくる
     $sql = "CREATE TABLE IF NOT EXISTS tb1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name TEXT,"
    . "comment TEXT,"
    . "date TEXT,"
    . "password TEXT"
    .");";
    $stmt = $pdo->query($sql);    
    
    //掲示板の内容を表示しておく

    ?>
    
    
    
    
            <?php
              if (empty($_POST["delnum"])){
                    if(!empty($_POST["name"]) && !empty($_POST["comment"])){
                        if (empty($_POST["edinum"])){
                            //パスワード入力されていない時は書き込まない
                             if(empty ($_POST["password1"])){
                            echo " Please enter your password.";
                        }
                             elseif(!empty($_POST["password1"])){
   //新規投稿のとき
    $name = $_POST["name"];//変数を定義
    $comment = $_POST["comment"];
    $password=$_POST["password1"];
    $date=date("Y年m月d日 H時i分s秒"); 
    
    $sql = $pdo->prepare("INSERT INTO tb1 (name, comment,date,password) VALUES (:name, :comment,:date,:password)");//投稿を保存
    $sql -> bindParam(':name',$name, PDO::PARAM_STR);
    $sql -> bindParam(':comment',$comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
    $sql -> execute();
    
   
                             }
    
    
                          //
                        }
                        elseif (!empty($_POST["edinum"])){ 
                            if(!empty($_POST["name"])&&!empty($_POST["comment"])){
                                if(!empty($_POST["password1"])){//新規投稿ではなく編集
                                      
                                        $id =$_POST["edinum"] ; //変更する投稿番号
                                        $name = $_POST["name"];
                                        $comment =$_POST["comment"]; 
                                        $date=date("Y年m月d日 H時i分s秒"); 
                                        $password=$_POST["password1"];//変更したい名前、変更したいコメントは自分で決めること
                                        
                                        $sql = 'UPDATE tb1 SET name=:name,comment=:comment, date=:date, password=:password WHERE id=:id';
                                        $stmt = $pdo->prepare($sql);
                                        
                                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                                        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                                        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                        
                                        $stmt->execute();

                                }
                                elseif(empty($_POST["password1"])){
                                    echo "Please enter the password.";
                                
                            }
                        }
                        }
                    }elseif(!empty($_POST["number2"])){ 
                        
                        if(empty($_POST["password3"])){
                            echo "Please enter the password.";}
                        elseif(!empty($_POST["password3"])){
                            
                            $number2 = $_POST["number2"]; //変更する投稿番号
                            $edipass=$_POST["password3"];
                                   
                                   $id=$number2;
                                   $password=$edipass;
                                   
    $sql = 'SELECT * FROM tb1 WHERE id=:id && password=:password ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt -> bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
    
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
        foreach ($results as $row){
            
        $ediid= $row['id'];
        $ediname= $row['name'];
        $edicomment=$row['comment'];
        $edidate= $row['date'];
        $edipass=$row['password'];
        
        }
                    }
                    
                    
                    }
 }elseif(!empty($_POST["delnum"])){
      if(empty($_POST["password2"])){
          echo "Please enter your password.";
      }
      elseif(!empty($_POST["password2"])){
    
    $delnum=$_POST["delnum"];
    $delpass=$_POST["password2"];
    
    $id = $delnum;
    $password=$delpass;
    
    $sql = 'delete from tb1 where id=:id AND password=:password';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
                          }
  }
  ?>
  
   <form action="" method="post">
            名前：<br>
            <input type="text" name="name" value="<?php if (isset($ediname)){ echo $ediname; } ?>"></br>
            コメント：<br>
            <input type="text" name="comment" value="<?php if (isset($edicomment)){ echo $edicomment;} ?>"></br>
            <!--投稿番号-->
            <input type="hidden" name="edinum" value="<?php if (isset($ediid)){ echo $ediid;} ?>"></br> 
            パスワード：<br>
            <input type="password" name="password1">
            <input type="submit"  name="submit1" value="送信" ></br>
            
            <br>削除番号指定用フォーム：</br>
            <input type="text" name="delnum"></br>
            パスワード：<br>
            <input type="password" name="password2"></br>
            <input type="submit" name="submit2" value="送信">
          
            <br> 編集番号指定フォーム：</br>
            <input type="text" name="number2" >
            
            <br>パスワード：</br>
            <input type="password" name="password3"><br>
            <input type="submit" name="submit3" value="編集"></br>
        </form>        </br>
  
  
  <?php
   $sql = 'SELECT * FROM tb1 ORDER BY id DESC'; //投稿内容を表示
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date']. '<br>';
        echo "<hr>";
    }
                            
                ?>
                
                


 
   </body>
</html>