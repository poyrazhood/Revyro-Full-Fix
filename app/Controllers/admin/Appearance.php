<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use http\Exception;
use PDO;

class Appearance extends Ana_Controller
{
    function index()
    {
        global $conn;
        global $_SESSION;
        $access = 0;
        $route[2] = "";
        if (!route(3)):
            $route[2] = "pages";
        endif;
        $ayar = array(
            'title' => 'Client',
            'user' => $this->getuser,
            'route' => route(3),
            'route4' => "",
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'search_where' => 'username',

        );

        $menuList = ["Sayfalar" => "pages", "Duyurular" => "news", "Blog" => "blog", "Menü" => "menu", "Temalar" => "themes", "Diller" => "language", "Dosyalar" => "files"];

        if (!array_search(route(3), $menuList)):
            header("Location:" . base_url("admin/appearance"));

        elseif (route(3) == "pages" or $route[2] == "pages"):
            $ayar['route'] = "pages";
            $access = $this->getuser["access"]["pages"];
            if ($access):
                if (route(4) == "edit" && route(4)):
                    $title = "Pages";
                    if ($_POST):
                        $id = route(5);
                        foreach ($_POST as $key => $value) {
                            $$key = $value;

                        }

                        if ($content == "<br>" || $content == "<p><br></p>"): $content = ""; endif;

                        if (!countRow(["table" => "pages", "where" => ["page_get" => $id]])):

                            $ayar['error'] = 1;
                            $ayar['icon'] = "error";
                            $ayar['errorText'] = "Lütfen geçerli ödeme methodu seçin";
                        else:

                            $update = $conn->prepare("UPDATE pages SET page_content=:content WHERE page_get=:id ");
                            $update->execute(array("id" => $id, "content" => $content));
                            if ($update):
                                $ayar['success'] = 1;
                                $ayar['successText'] = "İşlem başarılı";
                            else:
                                $ayar['error'] = 1;
                                $ayar['errorText'] = "İşlem başarısız";
                            endif;
                        endif;
                    endif;
                    $page = $conn->prepare("SELECT * FROM pages WHERE page_get=:get ");
                    $page->execute(array("get" => route(5)));
                    $page = $page->fetch(PDO::FETCH_ASSOC);
                    $ayar['page'] = $page;
                    if (!$page): header("Location:" . base_url("admin/appearance/pages")); endif;
                elseif (!route(4)):
                    $pageList = $conn->prepare("SELECT * FROM pages ");
                    $pageList->execute(array());
                    $pageList = $pageList->fetchAll(PDO::FETCH_ASSOC);
                    $ayar['pageList'] = $pageList;

                else:
                    header("Location:" . base_url("admin/appearance/pages"));
                endif;
            endif;
            if (1 == 0): header("Location:" . base_url("admin/appearance/pages")); endif;

        elseif (route(3) == "menu" && route(3)):
            
            if (route(4) == "add"):
                if ($this->request->getPost('menu_name') && $this->request->getPost('menu_icon') && $this->request->getPost('menu_link')):
                    $menu = new \App\Models\menu();
                    if ($this->request->getPost('menu_link') == "-1"):
                        $link = $this->request->getPost('menu_link_ozel');
                    else:
                        $link = $this->request->getPost('menu_link');
                    endif;
                    if($this->request->getPost('menu_tip') == '1'){
                        $status = 1;
                        $public = 2;
                    }elseif ($this->request->getPost('menu_tip') == '2'){
                        $status = 2;
                        $public = 1;
                    }
    
                    $menu->protect(false)->set(array(
                        'name' => $this->request->getPost('menu_name'),
                        'icon' => $this->request->getPost('menu_icon'),
                        'link' => $link,
                        'public' => $public,
                        'status' => $status
                    ))->insert();
                endif;
            endif;

            $access = $this->getuser["access"]["menu"];
            if ($access):

                $id = route(5);

                if ($id):

                    if (route(4) == "public_true" && route(4)):
                        $id = route(5);
                        $update = $conn->prepare("UPDATE menu SET status=:status WHERE id=:id");
                        $update = $update->execute(array("id" => $id, "status" => 2));

                        header("Location:" . base_url("admin/appearance/menu"));


                    elseif (route(4) == "public_false"):
                        $id = route(5);
                        $update = $conn->prepare("UPDATE menu SET status=:status WHERE id=:id");
                        $update = $update->execute(array("id" => $id, "status" => 1));

                        header("Location:" . base_url("admin/appearance/menu"));


                    ## Burası yangın yeri ##

                    elseif (route(4) == "nopublic_true" && route(4)):

                        $update = $conn->prepare("UPDATE menu SET public=:public WHERE id=:id");
                        $update = $update->execute(array("id" => $id, "public" => 2));

                        header("Location:" . base_url("admin/appearance/menu"));


                    elseif (route(4) == "nopublic_false" && route(4)):

                        $update = $conn->prepare("UPDATE menu SET public=:public WHERE id=:id");
                        $update = $update->execute(array("id" => $id, "public" => 1));
                        header("Location:" . base_url("admin/appearance/menu"));
                        
                    elseif (route(4) == "delete" && route(4)):

                        if (!countRow(["table" => "menu", "where" => ["id" => $id]])):
                            $error = 1;
                            $icon = "error";
                            $errorText = "Hata";
                        else:
                            $delete = $conn->prepare("DELETE FROM menu WHERE id=:id ");
                            $delete->execute(array("id" => $id));
    
                            if ($delete):
                                $error = 1;
                                $icon = "success";
                                $errorText = "İşlem başarılı";
                                $referrer = base_url("admin/settings/menu");
                            else:
                                $error = 1;
                                $icon = "error";
                                $errorText = "İşlem başarısız";
                            endif;
                        endif;
                    header("Location:" . base_url("admin/appearance/menu"));
                
                    elseif (route(4) == "edit" && route(4)):
    
                        if ($_POST):
                           $id = route(5);
                            $post = $conn->prepare("SELECT * FROM menu WHERE id=:id");
                            $post->execute(array("id" => route(5)));
                            $post = $post->fetch(PDO::FETCH_ASSOC);
                            foreach ($_POST as $key => $value) {
                                $$key = $value;
                            }
                            
                            if ($menu_link == "-1"):
                                $link = $menu_link_ozel;
                            else:
                                $link = $menu_link;
                            endif;
                            
                            
                            
                            if($tag){
                                if (empty($menu_name)):
                                $ayar['error'] = 1;
                                $ayar['$errorText'] = "Lütfen Menü ismini doldurunuz yazınız";
                                $ayar['icon'] = "error";
                            else:
                                $update = $conn->prepare("UPDATE menu SET name=:name, icon=:icon WHERE id=:id ");
                                $update->execute(array("id" => $id, "name" => $menu_name, "icon" => $menu_icon));
                                if ($update):
                                    $ayar['success'] = 1;
                                    $ayar['successText'] = "İşlem başarılı";
                                else:
                                    $ayar['error'] = 1;
                                    $ayar['errorText'] = "İşlem başarısız";
                                endif;
                            endif;
                            }else{
                            if (empty($menu_name)):
                                $ayar['error'] = 1;
                                $ayar['$errorText'] = "Lütfen Menü ismini doldurunuz yazınız";
                                $ayar['icon'] = "error";
                            elseif (empty($link)):
                                $ayar['error'] = 1;
                                $ayar['errorText'] = "Lütfen bir link giriniz";
                                $ayar['icon'] = "error";
                            else:
                                $update = $conn->prepare("UPDATE menu SET name=:name, link=:link, icon=:icon WHERE id=:id ");
                                $update->execute(array("id" => $id, "name" => $menu_name, "link" => $link, "icon" => $menu_icon));
                                if ($update):
                                    $ayar['success'] = 1;
                                    $ayar['successText'] = "İşlem başarılı";
                                else:
                                    $ayar['error'] = 1;
                                    $ayar['errorText'] = "İşlem başarısız";
                                endif;
                            endif;
                            }
                        endif;
                        $post = $conn->prepare("SELECT * FROM menu WHERE id=:id");
                        $post->execute(array("id" => route(5)));
                        $post = $post->fetch(PDO::FETCH_ASSOC);
                        if (!$post): header("Location:" . base_url("admin/appearance/menu"));
                        endif;
    
                        $ayar['post'] = $post;
                    endif;
                
                endif;


                $public = $conn->prepare("SELECT * FROM menu WHERE menu.edit=:edit");
                $public->execute(array("edit" => 0));
                $public = $public->fetchAll(PDO::FETCH_ASSOC);
                $ayar['public'] = $public;
                $nopublic = $conn->prepare("SELECT * FROM menu WHERE menu.edit=:edit");
                $nopublic->execute(array("edit" => 0));
                $nopublic = $nopublic->fetchAll(PDO::FETCH_ASSOC);
                $ayar['nopublic'] = $nopublic;
                if (isset($_POST['services'])):
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }
                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE settings SET service_list=:services  WHERE id=:id ");
                    $update = $update->execute(array("id" => 1, "services" => $services));
                    if ($update):
                        $conn->commit();
                        header("Location:" . base_url("admin/appearance/menu"));
                        $_SESSION["client"]["data"]["success"] = 1;
                        $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                    endif;

                endif;
            endif;


        elseif (route(3) == "blog"):

            $titleAdmin = "Blog";
            $access = $this->getuser["access"]["blog"];
            if ($access):

                if (route(4) == "edit" && route(4)):

                    if ($_POST):
                        $id = route(5);
                        $post = $conn->prepare("SELECT * FROM blogs WHERE id=:id ORDER BY blog_created DESC ");
                        $post->execute(array("id" => route(5)));
                        $post = $post->fetch(PDO::FETCH_ASSOC);
                        foreach ($_POST as $key => $value) {
                            $$key = $value;
                        }

                        if ($_FILES["logo"] && ($_FILES["logo"]["type"] == "image/jpeg" || $_FILES["logo"]["type"] == "image/jpg" || $_FILES["logo"]["type"] == "image/png" || $_FILES["logo"]["type"] == "image/gif")):
                            $logo_name = $_FILES["logo"]["name"];
                            $uzanti = substr($logo_name, -4, 4);
                            $avatar = $this->request->getFile('logo');
                            $logo_newname = $avatar->getRandomName();

                            $avatar->move('assets/uploads/blog', $logo_newname);
                            $upload_logo = move_uploaded_file($_FILES["logo"]["tmp_name"], $logo_newname);

                        elseif ($post["blog_image"] != ""):
                            $logo_newname = $post["blog_image"];
                        else:
                            $logo_newname = "";
                        endif;

                        if (empty($content)):
                            $ayar['error'] = 1;
                            $ayar['$errorText'] = "Lütfen blog yazınız";
                            $ayar['icon'] = "error";
                        elseif (empty($name)):
                            $ayar['error'] = 1;
                            $ayar['errorText'] = "İsim Yaz";
                            $ayar['icon'] = "error";
                        else:
                            $update = $conn->prepare("UPDATE blogs SET blog_content=:content, blog_title=:name, blog_image=:logo WHERE id=:id ");
                            $update->execute(array("id" => $id, "content" => $content, "name" => $name, "logo" => $logo_newname));
                            if ($update):
                                $ayar['success'] = 1;
                                $ayar['successText'] = "İşlem başarılı";
                            else:
                                $ayar['error'] = 1;
                                $ayar['errorText'] = "İşlem başarısız";
                            endif;
                        endif;
                    endif;
                    $post = $conn->prepare("SELECT * FROM blogs WHERE id=:id ORDER BY blog_created DESC ");
                    $post->execute(array("id" => route(5)));
                    $post = $post->fetch(PDO::FETCH_ASSOC);
                    if (!$post): header("Location:" . base_url("admin/appearance/blog"));
                    endif;

                    $ayar['post'] = $post;

                elseif (!route(4)):

                    if ($_POST):

                        foreach ($_POST as $key => $value) {
                            $$key = $value;
                        }

                        if ($_FILES["logo"] && ($_FILES["logo"]["type"] == "image/jpeg" || $_FILES["logo"]["type"] == "image/jpg" || $_FILES["logo"]["type"] == "image/png" || $_FILES["logo"]["type"] == "image/gif")):
                            $post = $_POST;
                            $logo_name = $_FILES["logo"]["name"];
                            $uzanti = substr($logo_name, -4, 4);


                            $avatar = $this->request->getFile('logo');
                            $logo_newname = $avatar->getRandomName();

                            $avatar->move('assets/uploads/blog', $logo_newname);
                            $upload_logo = move_uploaded_file($_FILES["logo"]["tmp_name"], $logo_newname);

                        elseif (isset($_POST["blog_image"]) && $_POST["blog_image"] != ""):
                            $logo_newname = $_POST["blog_image"];
                        else:
                            $logo_newname = "";
                        endif;

                        if (empty($content)):
                            $error = 1;
                            $errorText = "Lütfen blog yazınız";
                            $icon = "error";
                        elseif (empty($name)):
                            $error = 1;
                            $errorText = "İsim Yaz";
                            $icon = "error";
                        else:


                            $insert = $conn->prepare("INSERT INTO blogs SET blog_content=:content, blog_title=:name, blog_image=:logo, blog_created=:date, url=:url ");
                            $insert = $insert->execute(array("content" => $content, "name" => $name, "logo" => $logo_newname, "date" => date("Y-m-d H:i:s"), "url" => permalink($name)));

                            if ($insert):
                                $success = 1;
                                $successText = "İşlem başarılı";
                                $referrer = base_url("admin/settings/blog");
                            else:
                                $error = 1;
                                $errorText = "İşlem başarısız";
                            endif;
                        endif;
                    endif;


                    $postList = $conn->prepare("SELECT * FROM blogs ORDER BY blog_created DESC ");
                    $postList->execute(array());
                    $postList = $postList->fetchAll(PDO::FETCH_ASSOC);
                    $ayar['postList'] = $postList;
                elseif (route(4) == "delete" && route(4)):

                    $id = route(5);
                    if (!countRow(["table" => "blogs", "where" => ["id" => $id]])):
                        $error = 1;
                        $icon = "error";
                        $errorText = "Lütfen geçerli ödeme bonusu seçin";
                    else:
                        $delete = $conn->prepare("DELETE FROM blogs WHERE id=:id ");
                        $delete->execute(array("id" => $id));

                        if ($delete):
                            $error = 1;
                            $icon = "success";
                            $errorText = "İşlem başarılı";
                            $referrer = base_url("admin/settings/blog");
                        else:
                            $error = 1;
                            $icon = "error";
                            $errorText = "İşlem başarısız";
                        endif;
                    endif;
                    header("Location:" . base_url("admin/appearance/blog"));
                    exit();
                else:
                    header("Location:" . base_url("admin/appearance/blog"));
                endif;
            endif;
            if (1 == 0): header("Location:" . base_url("admin/appearance/blog")); endif;


        elseif (route(3) == "language"):

            $titleAdmin = "Language";
            $access = $this->getuser["access"]["language"];
            if ($access):

                if (route(4) && route(4) != "new" && !countRow(["table" => "languages", "where" => ["language_code" => route(4)]])):
                    header("Location:" . base_url("admin/appearance/language"));
                elseif (route(4) == "new" && route(4)):
                    include APPPATH . 'ThirdParty/dil/default.php';
                    $ayar['languageArray'] = $languageArray;
                else:
                    if (route(4)) {
                        $language = $conn->prepare("SELECT * FROM languages WHERE language_code=:code");
                        $language->execute(array("code" => route(4)));
                        $language = $language->fetch(PDO::FETCH_ASSOC);
                        $ayar['language'] = $language;

                        include APPPATH . 'ThirdParty/dil/' . route(4) . '.php';
                        $ayar['languageArray'] = $languageArray;
                    }
                endif;
                if ($_POST && route(4) != "new" && countRow(["table" => "languages", "where" => ["language_code" => route(4)]])):


                    $isim = $_POST["language"];

                    $update = $conn->prepare("UPDATE languages SET language_name=:name WHERE language_code=:code ");
                    $update->execute(array("code" => route(4), "name" => $isim));

                    $html = '<?php ' . PHP_EOL . PHP_EOL;
                    $html .= '$languageArray= [';
                    foreach ($_POST["Language"] as $key => $value):

                        $value = str_replace('"', "'", $value);

                        $html .= ' "' . $key . '" => "' . $value . '", ' . PHP_EOL;
                    endforeach;
                    $html .= '];';
                    file_put_contents('app/ThirdParty/dil/' . route(4) . '.php', $html);
                    //sleep(2);
                    include APPPATH . 'ThirdParty/dil/' . route(4) . '.php';
                    return json_encode(["t" => "error", "m" => "Başarıyla Dil Düzenlendi", "s" => "success", "r" => base_url("admin/appearance/language/" . route(4))]);

                elseif (route(4) == "new" && isset($_POST["language"])):
                    $name = $_POST["language"];
                    $code = $_POST["languagecode"];
                    if (countRow(["table" => "languages", "where" => ["language_code" => $code]])):
                        $error = 1;
                        $errorText = "Bu dil kodu zaten kullanılıyor.";
                    else:
                        $insert = $conn->prepare("INSERT INTO languages SET language_name=:name, language_code=:code ");
                        $insert->execute(array("name" => $name, "code" => $code));
                        if ($insert):
                            $html = '<?php ' . PHP_EOL . PHP_EOL;
                            $html .= '$languageArray= [';
                            foreach ($_POST["Language"] as $key => $value):
                                $value = str_replace('"', "'", $value);

                                $html .= ' "' . $key . '" => "' . $value . '", ' . PHP_EOL;
                            endforeach;
                            $html .= '];';
                            file_put_contents('app/ThirdParty/dil/' . $code . '.php', $html);
                            header("Location:" . base_url("admin/appearance/language/"));
                        endif;
                    endif;
                elseif (isset($_GET["lang-default"]) && $_GET["lang-default"] && $_GET["lang-id"]):
                    $update = $conn->prepare("UPDATE languages SET default_language=:default");
                    $update->execute(array("default" => 0));
                    
                    $update = $conn->prepare("UPDATE settings SET site_language=:default");
                    $update->execute(array("default" => $_GET['lang-short']));
                    $update = $conn->prepare("UPDATE languages SET default_language=:default WHERE language_code=:code ");
                    $update->execute(array("code" => $_GET["lang-id"], "default" => 1));
                    header("Location:" . base_url("admin/appearance/language"));
                elseif (isset($_GET["lang-type"]) && isset($_GET["lang-id"]) && $_GET["lang-type"] && $_GET["lang-id"]):
                    if (countRow(["table" => "languages", "where" => ["language_type" => "2"]]) > 1 && $_GET["lang-type"] == 1):
                        $update = $conn->prepare("UPDATE languages SET language_type=:type WHERE language_code=:code ");
                        $update->execute(array("code" => $_GET["lang-id"], "type" => $_GET["lang-type"]));
                    elseif (isset($_GET["lang-type"]) && $_GET["lang-type"] == 2):
                        $update = $conn->prepare("UPDATE languages SET language_type=:type WHERE language_code=:code ");
                        $update->execute(array("code" => $_GET["lang-id"], "type" => $_GET["lang-type"]));
                    endif;
                    header("Location:" . base_url("admin/appearance/language"));
                endif;
            endif;
            $languages = $conn->prepare('SELECT * FROM languages WHERE language_type=:type');
            $languages->execute(array(
                'type' => 2
            ));
            $languages = $languages->fetchAll(PDO::FETCH_ASSOC)[0];
            $languageList = $conn->prepare("SELECT * FROM languages");
            $languageList->execute(array());
            $languageList = $languageList->fetchAll(PDO::FETCH_ASSOC);
            $ayar['languageList'] = $languageList;
            $ayar['languages'] = $languages;

        elseif (route(3) == "themes"):
            ob_end_clean();
            $titleAdmin = "Themes";
            $access = $this->getuser["access"]["themes"];
            $ayar['theme']["theme_name"] = 'lightblue';
            $codeType = "twig";
            $ayar['codeType'] = $codeType;
            if ($access):
                if (route(4) && route(4) == "active" && countRow(["table" => "themes", "where" => ["theme_dirname" => route(5)]])):
                    $update = $conn->prepare("UPDATE settings SET site_theme=:theme WHERE id=:id ");
                    $update->execute(array("id" => 1, "theme" => route(5)));

                    $themes = $conn->prepare("SELECT * FROM themes ORDER BY id DESC");
                    $themes->execute(array());
                    $themes = $themes->fetchAll(PDO::FETCH_ASSOC);
                    $ayar['themes'] = $themes;

                elseif (route(4) && route(4) && countRow(["table" => "themes", "where" => ["theme_dirname" => route(4)]])):


                    $theme = $conn->prepare("SELECT * FROM themes WHERE theme_dirname=:name");
                    $theme->execute(array("name" => route(4)));
                    $theme = $theme->fetch(PDO::FETCH_ASSOC);
                    $ayar['theme'] = $theme;
                    if (isset($_GET['file'])) {
                        $lyt = $_GET["file"];
                        if (substr($lyt, -3) == "css") {
                            $fn = "assets/css/panel/" . $theme["theme_dirname"] . "/" . $lyt;
                            $codeType = "css";
                            $dir = "CSS";
                        } elseif (substr($lyt, -2) == "js") {
                            $fn = "assets/js/panel/" . $theme["theme_dirname"] . "/" . $lyt;
                            $codeType = "js";
                            $dir = "JS";
                        } else {
                            $fn = "app/Views/main/" . $theme["theme_dirname"] . "/" . $lyt;
                            $codeType = "twig";
                            $dir = "HTML";
                        }
                    } else {
                        $lyt = "";
                        $fn = "";
                        $dir = "";
                        $codeType = "";
                    }
                    $ayar['fn'] = $fn;
                    $ayar['codeType'] = $codeType;
                    $ayar['dir'] = $dir;
                    $ayar['lyt'] = $lyt;
                    $codeType = "twig";
                    $ayar['codeType'] = $codeType;
                    if ($_POST):
                        $text = $_POST["code"];
                        $text = str_replace("&lt;", "<", $text);
                        $text = str_replace("&gt;", ">", $text);
                        $text = str_replace("&quot;", '"', $text);
                        $updated_file = fopen($fn, "w");
                        fwrite($updated_file, $text);
                        fclose($updated_file);
                        header("Location:" . base_url("admin/appearance/themes/" . $theme["theme_dirname"] . "?file=" . $lyt));
                    endif;
                elseif ("" && !countRow(["table" => "themes", "where" => ["theme_dirname" => ""]])):
                    header("Location:" . base_url("admin/appearance/themes"));
                else:
                    $themes = $conn->prepare("SELECT * FROM themes ORDER BY id DESC");
                    $themes->execute(array());
                    $themes = $themes->fetchAll(PDO::FETCH_ASSOC);
                    $ayar['themes'] = $themes;
                endif;
            endif;

        elseif (route(3) == "news" && route(3)):

            $access = $this->getuser["access"]["providers"];
            if ($access):

                if (route(4) == "new" && route(4) && $_POST):
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }

                    if (empty($icon)):
                        $error = 1;
                        $errorText = "İkon seçiniz.";
                        $icon = "error";
                    elseif (empty($title)):
                        $error = 1;
                        $errorText = "Duyuru adı boş olamaz";
                        $icon = "error";
                    elseif (empty($content)):
                        $error = 1;
                        $errorText = "Duyuru içeriği boş olamaz";
                        $icon = "error";
                    else:

                        $conn->beginTransaction();
                        $insert = $conn->prepare("INSERT INTO news SET news_icon=:icon, news_title=:title, news_content=:content, news_date=:date ");
                        $insert = $insert->execute(array("icon" => $icon, "title" => $title, "content" => $content, "date" => date("Y-m-d H:i:s")));
                        if ($insert):
                            $conn->commit();
                            $referrer = base_url("admin/appearance/news");
                            $error = 1;
                            $errorText = "İşlem başarılı";
                            $icon = "success";
                        else:
                            $conn->rollBack();
                            $error = 1;
                            $errorText = "İşlem başarısız";
                            $icon = "error";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                elseif (route(4) == "edit" && route(4) && $_POST):
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }
                    $id = route(5);

                    if (empty($icon)):
                        $error = 1;
                        $errorText = "İkon seçiniz.";
                        $icon = "error";
                    elseif (empty($title)):
                        $error = 1;
                        $errorText = "Duyuru adı boş olamaz";
                        $icon = "error";
                    elseif (empty($content)):
                        $error = 1;
                        $errorText = "Duyuru içeriği boş olamaz";
                        $icon = "error";
                    else:

                        $conn->beginTransaction();
                        $update = $conn->prepare("UPDATE news SET news_icon=:icon, news_title=:title, news_content=:content WHERE id=:id ");
                        $update = $update->execute(array("icon" => $icon, "title" => $title, "content" => $content, "id" => $id));
                        if ($update):
                            $conn->commit();
                            $referrer = base_url("admin/appearance/news");
                            $error = 1;
                            $errorText = "İşlem başarılı";
                            $icon = "success";
                        else:
                            $conn->rollBack();
                            $error = 1;
                            $errorText = "İşlem başarısız";
                            $icon = "error";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                elseif (route(4) == "delete" && route(4)):
                    $id = route(5);
                    if (!countRow(["table" => "news", "where" => ["id" => $id]])):
                        $error = 1;
                        $icon = "error";
                        $errorText = "Lütfen geçerli duyuru seçin";
                    else:
                        $delete = $conn->prepare("DELETE FROM news WHERE id=:id ");
                        $delete->execute(array("id" => $id));
                        if ($delete):
                            $error = 1;
                            $icon = "success";
                            $errorText = "İşlem başarılı";
                            $referrer = base_url("admin/appearance/news");
                        else:
                            $error = 1;
                            $icon = "error";
                            $errorText = "İşlem başarısız";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 0]);
                    exit();
                elseif (!route(4)):
                    $newsList = $conn->prepare("SELECT * FROM news ");
                    $newsList->execute(array());
                    $newsList = $newsList->fetchAll(PDO::FETCH_ASSOC);
                    $ayar['newsList'] = $newsList;
                else:
                    header("Location:" . base_url("admin/appearance/news"));
                endif;
            endif;
            if (1 == 0): header("Location:" . base_url("admin/appearance/news")); endif;

        elseif (route(3) == "files"):

            $access = $this->getuser["access"]["blog"];
            if ($access):

                if (isset($_FILES["logo"])):

                    if ($_FILES["logo"] && ($_FILES["logo"]["type"] == "image/jpeg" || $_FILES["logo"]["type"] == "image/jpg" || $_FILES["logo"]["type"] == "image/png" || $_FILES["logo"]["type"] == "image/gif")):
                        $logo_name = $_FILES["logo"]["name"];
                        $uzanti = substr($logo_name, -4, 4);
                        $logo_newname = "img/files/" . md5(rand(1, 999999)) . $uzanti;
                        $avatar = $this->request->getFile('logo');
                        $logo_newname = $avatar->getRandomName();
                        $avatar->move('assets/uploads/sites', $logo_newname);

                        $url = base_url("assets/uploads/sites/" . $logo_newname);

                        $insert = $conn->prepare("INSERT INTO files SET link=:link, date=:date");
                        $insert = $insert->execute(array("link" => $url, "date" => date("Y-m-d H:i:s")));

                    endif;

                endif;

                $fileList = $conn->prepare("SELECT * FROM files ORDER BY date DESC ");
                $fileList->execute(array());
                $fileList = $fileList->fetchAll(PDO::FETCH_ASSOC);
                $ayar['fileList'] = $fileList;
                //1
                if (route(4) == "delete" && route(4)):
                    $id = route(5);

                    if (countRow(["table" => "files", "where" => ["id" => $id]])):
                        $delete = $conn->prepare("DELETE FROM files WHERE id=:id ");
                        $delete->execute(array("id" => $id));
                    endif;

                    header("Location:" . base_url("admin/appearance/files"));
                    exit();
                endif;
                //1

            endif;

            if (1 == 0): header("Location:" . base_url("admin/appearance/files"));
            endif;

        endif;
        $ayar['menuList'] = $menuList;
        $ayar['access'] = $access;
        $settins_s = new \App\Models\settings();
        $ayar['settings'] = $settins_s->where('id', '1')->get()->getResultArray()[0];
        //return view('admin/appearance', $ayar);
        return view('admin/yeni_admin/appearance', $ayar);
    }
}