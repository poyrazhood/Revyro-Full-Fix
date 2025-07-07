<?php

$title = $languageArray["faq.title"];

if(isset($user["client_type"]) && $user["client_type"] == 1  ){
  Header("Location:".site_url('logout'));
}
