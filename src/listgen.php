<!DOCTYPE HTML>
<html>
    <head>
        <title>リストジェネレータ</title>      
    </head>
    <body>
        <form action = "listgen.php" method = "post" >
            <br>
            <label>F:</label>
            <input type="text" name="F" size=2>
            <label>E:</label>
            <input type="text" name="E" size=2>
            <label>S:</label>
            <input type="text" name="S" size=2>
            <br>
            <br>
            <label>Pre:</label>
            <input type="text" name="Pre" size=2>
            <label>Post:</label>
            <input type="text" name="Post" size=2>
            <br>
            <input type="submit" value="送信">
		    <input type="reset" value="クリア">
        </form>

            <?php
            $F = $_POST['F'];
            $E = $_POST['E'];
            $S = $_POST['S'];
            $Pre = $_POST['Pre'];
            $Post = $_POST['Post'];

            if($F == ""){
                $F = 0;
            }
            if($S == ""){
                $S = 1;
            }

            if(is_numeric($F) && is_numeric($E) && is_numeric($S) && $Pre != "" && $Post != ""){
                for($i = $F; $i < $E; $i = $i + $S){
                    print $Pre;
                    print $i;
                    print $Post;
                }
            }
            else{
                print "エラー";
            }
            ?>
    </body>
</html>



