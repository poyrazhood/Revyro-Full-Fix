<?php

if( countRow(["table"=>"blogs"])<1 ){
  Header("Location:".base_url(''));
}

if( !route(2)){
$title = $languageArray["blog.title"];
  $blogs = $conn->prepare("SELECT * FROM blogs ORDER BY blog_created DESC ");
  $blogs-> execute(array());
  $blogs = $blogs->fetchAll(PDO::FETCH_ASSOC);
  $blogList = [];
    foreach ($blogs as $blog) {
          if(isset($blog['blog_image'])){
              $blog['blog_image'] = "assets/uploads/blog/".$blog['blog_image'];
          }
      foreach ($blog as $key => $value) {
          $t[$key] = $value;

      }
      array_push($blogList,$t);
    }

}elseif( route(2) ){
    $routes  = "blogpost";
   $blogs = $conn->prepare("SELECT * FROM blogs WHERE url=:url ORDER BY blog_created DESC ");
  $blogs-> execute(array("url"=>route(2)));
  $blogs = $blogs->fetchAll(PDO::FETCH_ASSOC);
  $blogList = [];
    foreach ($blogs as $blog) {
                  if(isset($blog['blog_image'])){
              $blog['blog_image'] = "assets/uploads/blog/".$blog['blog_image'];
          }
      foreach ($blog as $key => $value) {
          if(isset($value['blog_image'])){
              $value['blog_image'] = "assets/uploads/blog/".$value['blog_image'];
          }
        if( $key == "blog_title" ){
          $title = $value;
          $t[$key] = $value;
        }else{
          $t[$key] = $value;
        }

      }
      array_push($blogList,$t);
    }

}

