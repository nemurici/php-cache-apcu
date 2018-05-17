<?php
class CacheMagic{
  var $expiryTime = 30;
  var $cacheKey = "default";
  function __call($functionName, $args){
    $argsString = json_encode($args);
    $argsCheckSum = md5($argsString);

    $cacheString = $this->cacheKey."/".$functionName."/".$argsCheckSum;
    $return = apcu_fetch($cacheString, $success);
    if($success){
      return json_decode($return, true);
    }else{
      $return = (call_user_func_array($functionName, $args));
      apcu_store($cacheString, json_encode($return), $this->expiryTime);
      return $return;
    }
  }

  function set_expiry_time($time){
    $this->expiryTime = $time;
  }

  function set_cache_key($key){
    $this->cacheKey = $key;
  }
}
