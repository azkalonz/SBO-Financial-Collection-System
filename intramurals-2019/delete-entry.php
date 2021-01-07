<?php
    session_start();
    (function(){
        global $success;
        $id = isset($_SESSION['account']['student_id'])?$_SESSION['account']['student_id']:$_SESSION['account']['officer_id'];
        $img = "./entries/{$id}.png";
        $thumb = "./entries/{$id}-thumb.png";
        $json = file_get_contents('./votes.js');
        $votes_js = json_decode("$json",true);
        foreach($votes_js as $key => $value){
            if($key == "s{$key}"){
                unset($votes_js[$key]);
                return;
            }
        }
        
        unlink($img);
        unlink($thumb);
    })();
    header('Location: '.$_SERVER['HTTP_REFERER']);
?>