<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="alternate" href="http://www.michaellapan.com" hreflang="en-us" />
    <title>Facebook Contest Winner</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
    <script src="js/vendor/jquery.js"></script>
<?php
header('X-Frame-Options: DENY');
?>

    <?php
    require_once('radioStruct.php');
        $like = new radioStruct("likes", 0);
        $comment  = new radioStruct("comments", 0);
        $or  = new radioStruct("or", 0);
        $and  = new radioStruct("and", 1);
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $val = $_POST['ao'];
        if ($val == 0) {
            $like->one();
            $comment->zero();
            $or->zero();
            $and->zero();
        } else if ($val == 1) {
            $like->zero();
            $comment->one();
            $or->zero();
            $and->zero();
        } else if ($val == 2) {
            $like->zero();
            $comment->zero();
            $or->one();
            $and->zero();
        } else if ($val == 3) {
            $like->zero();
            $comment->zero();
            $or->zero();
            $and->one();
        }
    }
    ?>
</head>
<body>
<div class="row">
    <div class="large-12 columns">
        <h1>Facebook Contest Winner Picker</h1>
    </div>
</div>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="f1">
    <div class="row">
        <div class="large-12 columns">
            <label>Post ID</label>
            <input id="pid" name="pid" type="text"   <?php (isset($_REQUEST['pid'])) ? print("value=". htmlspecialchars($_REQUEST['pid'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")) : print("placeholder=\"post id\"")  ?> />
        </div>
    </div>

    <div class="row">
        <div class="large-6 medium-6 columns">
         <label>Choose Your Favorite</label>
            <input type="radio" name="ao" value="0"
                    <?php if($like->getValue() == 1) {echo "checked"; } ?>><label for="Pick from Likes">Pick
                from likes
                only </label>
            </br>
            <input type="radio" name="ao" value="1"
                    <?php if($comment->getValue() == 1) {echo "checked"; } ?>><label for="Pick from Comments">Pick
                from comments
                only </label>
            </br>

            <input type="radio" name="ao" value="2"  <?php if($or->getValue() == 1) {echo "checked"; }//($_REQUEST['ao'] == 0) ? print'checked' : "" ?>><label
            >like OR comment</label>
            </br>
            <input type="radio" name="ao" value="3"  <?php if($and->getValue() == 1) {echo "checked"; }//($_REQUEST['ao'] == 1) ? print'checked' : "" ?>><label
            >like AND comment</label>
            </br>
        </div>
</form>
<button type="submit" class="button" form="f1"> pick winner</button>
<p>Dont know how to find the postID? <a href="postId.php">Click here!</a></p>

<div class="row">
    <div class="large-8 medium-8 columns">
        <?php

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $val = $_POST['ao'];
        if ($val == 0){
            $like->one();
            $comment->zero();
        }else if($val == 1){
            $like->zero();
            $comment->one();
        }else if($val == 2){
            //or
            $like->zero();
            $comment->zero();
            $andorval = 0;
        }else if($val == 3){
            //and
            $like->zero();
            $comment->zero();
            $andorval = 1;
        }
            require_once('faceSpiderObj.php');
		$pid = htmlspecialchars($_REQUEST['pid'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
            $a = new faceSpiderObj('app_id_here', 'app_secret_here', 'v2.8', $pid, 'app_token_here');


        $b = $a->pick($like->getValue(), $comment->getValue(), $andorval, 1);

        ?>
        <div class="callout">
            <?php
            print '<p>And the winner is: ' . $b['name'] . '</p><p><img src="http://graph.facebook.com/' . $b['id'] . '/picture"> <a href="https://www.facebook.com/' . $b['id'] . '"> Link to winner!</a></p>';


        } ?>
        </div>
  
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.js"></script>
<script src="js/app.js"></script>
</body>
</html>
