<?php
require_once __DIR__ . '/vendor/autoload.php';





class faceSpiderObj
{
    

    private $postLikeArray;
    private $postCommentsArray;
    private $postId;
    private $fb;
    private $app_id;
    private $app_secret;
    private $graphVr;
    private $appToken;
    private $pool;
    private $winningNum;
    
    

    function __construct($aId, $aS, $grVr, $pId, $aT)
    {
        $this->app_id = $aId;
        $this->app_secret = $aS;
        $this->graphVr = $grVr;
        $this->fb = new Facebook\Facebook([
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'default_graph_version' => $this->graphVr,
        ]);
        $this->postLikeArray = NULL;
        $this->postComments = NULL;
        $this->postId = $pId;
        $this->appToken = $aT;
    }



    public function pick($fromLikes, $fromComments, $andOr, $unique){
        $i = 0;
        do{
            $b = $this->pickRandWinner($fromLikes, $fromComments, $andOr, $unique);
            $i++;
        }while( $b != NULL && $i < 2);
        print 'DEBUG:</br>';
        print 'Pool: ' . $this->pool . '</br>';
        print 'Index: ' . $this->winningNum . '</br>';

        if($i > 4){
            $b = "error with request, please try again";
        }      
        return $b;
    }  
    
    public function pickRandWinner($fromLikes, $fromComments, $andOr, $unique)
    {

        if ($fromLikes == 1) {
            $tmpLArr = $this->getLikes();
            $this->winningNum = (rand(0, count($tmpLArr) - 1));
            return $tmpLArr[$this->winningNum];
        } elseif ($fromComments == 1) {
            $tmpCArr = $this->getComments();
            $this->winningNum = (rand(0, count($tmpCArr) - 1));
            return $tmpCArr[$this->winningNum];

        } elseif ($fromComments == 0 && $fromLikes == 0) {

        } else {
            return 'invalid options for fromLikes or fromComments';
        }
	if ($andOr == 0) {
	   $tmpArr = ($unique == 0) ? (array_merge($this->getComments(), $this->getLikes())) : ($this->unique_multidim_array(array_merge($this->getComments(), $this->getLikes()), "id"));
        $t1 = $tmpArr;
        $this->pool = count($t1);
        $this->winningNum = (rand(0, count($tmpArr) - 1));



        return $t1[$this->winningNum];
        } else if ($andOr == 1) {
            $tmpBArr = array_intersect($this->getComments(), $this->getLikes());
        $this->pool = count($tmpBArr);

        $this->winningNum = (rand(0, count($tmpBArr) - 1));
            return $tmpBArr[$this->winningNum];
        } else {
        return 'invalid and or option' . $andOr . '!';
        }

    }


    public function getLikes($limit = 1100)
    {
        if ($this->postId != 'N/A') {
            $bool = false;
            $request = $this->fb->get('/' . $this->postId . '/likes?access_token=' . $this->appToken . '&limit=' . $limit);
            $requestData = $request->getDecodedBody();
            $this->postLikeArray = $requestData['data'];
            if (array_key_exists("next", $requestData['paging'])) {
                $nextPage = $requestData['paging']['next'];
                while ($bool == false) {
                    $request = $this->fb->get(str_replace("https://graph.facebook.com/v2.8", "", $nextPage));
                    $requestData = $request->getDecodedBody();
                    $this->postLikeArray = array_merge($this->postLikeArray, $requestData['data']);
                    if (!array_key_exists("next", $requestData['paging'])) {
                        $bool = true;
                    } else {
                        $nextPage = $requestData['paging']['next'];
                    }
                }
            }
            $this->pool = count($this->postLikeArray);
	    return $this->postLikeArray;

        } else {
            return "POST ID NOT SET";

        }
    }

    public function getComments($limit = 1100)
    {
        if ($this->postId != 'N/A') {
            $bool = false;
            $request = $this->fb->get('/' . $this->postId . '/comments?access_token=' . $this->appToken . '&limit=' . $limit);
            $requestData = $request->getDecodedBody();
	    $this->postCommentsArray = $requestData['data'];

            if (array_key_exists("next", $requestData['paging'])) {
                $nextPage = $requestData['paging']['next'];
                while ($bool == false) {
                    $request = $this->fb->get(str_replace("https://graph.facebook.com/v2.8", "", $nextPage));
                    $requestData = $request->getDecodedBody();
                    $this->postCommentsArray = array_merge($this->postCommentsArray, $requestData['data']);
                    if (!array_key_exists("next", $requestData['paging'])) {
                        $bool = true;
                    } else {
                        $nextPage = $requestData['paging']['next'];
                    }
                }
	     }
	     $tempArray = NULL;
	     $i = 0;
	     foreach($this->postCommentsArray as $val){
		$tempArray[$i] =  $val['from'];
		$i++;
	     }
	     $this->postCommentsArray =  $tempArray;
            $this->pool = count($this->postCommentsArray);

            return $this->postCommentsArray;
        } else {
            return "POST ID NOT SET";
        }
    }

    private function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();
        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
	       $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
	 }
        return $temp_array;
    }
}
