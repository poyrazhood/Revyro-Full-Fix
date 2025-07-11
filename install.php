<?php

if($_POST && empty($_GET["q"])){

    $db_host = $_POST["db_host"];
    $db_name = $_POST["db_name"];
    $db_user = $_POST["db_user"];
    $db_pass = $_POST["db_pass"];
	$lsynkey = $_POST["lisans_key"];
    
    $url = "https://".$_SERVER['SERVER_NAME']."/";
	


    $conn = new mysqli($db_host, $db_user, $db_pass , $db_name);
    $conn->set_charset("utf8");
    
    if( $conn->connect_errno ):
        
      $errorText = $conn->connect_error;
    
    else:
        $query = '';
        $sqlScript = file('database.sql');
        
        foreach ($sqlScript as $line){
        	
        	$startWith = substr(trim($line), 0 ,2);
        	$endWith = substr(trim($line), -1 ,1);
        	
        	if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
        		continue;
        	}
        		
        	$query = $query . $line;
        	if ($endWith == ';') {
        		mysqli_query($conn,$query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
        		$query= '';		
        	}
        }
        
        $config_file = fopen('Glycon.php', 'w');
        fwrite($config_file, "<?php 
		define('LISANCEKEY', '$lsynkey');
		define('SITEURLS', '$url');
		define('DBHOSTS', '$db_host');
		define('DBNAMES', '$db_name');
		define('DBUSERS', '$db_user');
		define('DBPASSS', '$db_pass');");
        fclose($config_file);
        
         header("Location:" . "/install.php?q=1");
        
    endif;

}

if($_GET["q"] == 1){    
    unlink("mysql_sql");
    unlink("error_log");   
    unlink("install.php");
	unlink("database.sql");
    header("Location:" . "/");
}

?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Glycon | Kurulum Sistemi</title>
	<style type="text/css">.container{position:relative;width:100%;max-width:960px;margin:0 auto;padding:0 20px;box-sizing:border-box}.column,.columns{width:100%;float:left;box-sizing:border-box}@media (min-width:400px){.container{width:85%;padding:0}}@media (min-width:550px){.container{width:80%}.column,.columns{margin-left:4%}.column:first-child,.columns:first-child{margin-left:0}.one.column,.one.columns{width:4.66666666667%}.two.columns{width:13.3333333333%}.three.columns{width:22%}.four.columns{width:30.6666666667%}.five.columns{width:39.3333333333%}.six.columns{width:48%}.seven.columns{width:56.6666666667%}.eight.columns{width:65.3333333333%}.nine.columns{width:74%}.ten.columns{width:82.6666666667%}.eleven.columns{width:91.3333333333%}.twelve.columns{width:100%;margin-left:0}.one-third.column{width:30.6666666667%}.two-thirds.column{width:65.3333333333%}.one-half.column{width:48%}.offset-by-one.column,.offset-by-one.columns{margin-left:8.66666666667%}.offset-by-two.column,.offset-by-two.columns{margin-left:17.3333333333%}.offset-by-three.column,.offset-by-three.columns{margin-left:26%}.offset-by-four.column,.offset-by-four.columns{margin-left:34.6666666667%}.offset-by-five.column,.offset-by-five.columns{margin-left:43.3333333333%}.offset-by-six.column,.offset-by-six.columns{margin-left:52%}.offset-by-seven.column,.offset-by-seven.columns{margin-left:60.6666666667%}.offset-by-eight.column,.offset-by-eight.columns{margin-left:69.3333333333%}.offset-by-nine.column,.offset-by-nine.columns{margin-left:78%}.offset-by-ten.column,.offset-by-ten.columns{margin-left:86.6666666667%}.offset-by-eleven.column,.offset-by-eleven.columns{margin-left:95.3333333333%}.offset-by-one-third.column,.offset-by-one-third.columns{margin-left:34.6666666667%}.offset-by-two-thirds.column,.offset-by-two-thirds.columns{margin-left:69.3333333333%}.offset-by-one-half.column,.offset-by-one-half.columns{margin-left:52%}}html{font-size:62.5%}body{font-size:1.5em;line-height:1.6;font-weight:400;font-family:Raleway,HelveticaNeue,"Helvetica Neue",Helvetica,Arial,sans-serif;color:#222}h1,h2,h3,h4,h5,h6{margin-top:0;margin-bottom:2rem;font-weight:300}h1{font-size:4rem;line-height:1.2;letter-spacing:-.1rem}h2{font-size:3.6rem;line-height:1.25;letter-spacing:-.1rem}h3{font-size:3rem;line-height:1.3;letter-spacing:-.1rem}h4{font-size:2.4rem;line-height:1.35;letter-spacing:-.08rem}h5{font-size:1.8rem;line-height:1.5;letter-spacing:-.05rem}h6{font-size:1.5rem;line-height:1.6;letter-spacing:0}@media (min-width:550px){h1{font-size:5rem}h2{font-size:4.2rem}h3{font-size:3.6rem}h4{font-size:3rem}h5{font-size:2.4rem}h6{font-size:1.5rem}}p{margin-top:0}a{color:#1eaedb}a:hover{color:#0fa0ce}.button,button,input[type=button],input[type=reset],input[type=submit]{display:inline-block;height:38px;padding:0 30px;color:#555;text-align:center;font-size:11px;font-weight:600;line-height:38px;letter-spacing:.1rem;text-transform:uppercase;text-decoration:none;white-space:nowrap;background-color:transparent;border-radius:4px;border:1px solid #bbb;cursor:pointer;box-sizing:border-box}.button:focus,.button:hover,button:focus,button:hover,input[type=button]:focus,input[type=button]:hover,input[type=reset]:focus,input[type=reset]:hover,input[type=submit]:focus,input[type=submit]:hover{color:#333;border-color:#888;outline:0}.button.button-primary,button.button-primary,input[type=button].button-primary,input[type=reset].button-primary,input[type=submit].button-primary{color:#fff;background-color:#33c3f0;border-color:#33c3f0}.button.button-primary:focus,.button.button-primary:hover,button.button-primary:focus,button.button-primary:hover,input[type=button].button-primary:focus,input[type=button].button-primary:hover,input[type=reset].button-primary:focus,input[type=reset].button-primary:hover,input[type=submit].button-primary:focus,input[type=submit].button-primary:hover{color:#fff;background-color:#1eaedb;border-color:#1eaedb}input[type=email],input[type=number],input[type=password],input[type=search],input[type=tel],input[type=text],input[type=url],select,textarea{height:38px;padding:6px 10px;background-color:#fff;border:1px solid #d1d1d1;border-radius:4px;box-shadow:none;box-sizing:border-box}input[type=email],input[type=number],input[type=password],input[type=search],input[type=tel],input[type=text],input[type=url],textarea{-webkit-appearance:none;-moz-appearance:none;appearance:none}textarea{min-height:65px;padding-top:6px;padding-bottom:6px}input[type=email]:focus,input[type=number]:focus,input[type=password]:focus,input[type=search]:focus,input[type=tel]:focus,input[type=text]:focus,input[type=url]:focus,select:focus,textarea:focus{border:1px solid #33c3f0;outline:0}label,legend{display:block;margin-bottom:.5rem;font-weight:600}fieldset{padding:0;border-width:0}input[type=checkbox],input[type=radio]{display:inline}label>.label-body{display:inline-block;margin-left:.5rem;font-weight:400}ul{list-style:circle inside}ol{list-style:decimal inside}ol,ul{padding-left:0;margin-top:0}ol ol,ol ul,ul ol,ul ul{margin:1.5rem 0 1.5rem 3rem;font-size:90%}li{margin-bottom:1rem}code{padding:.2rem .5rem;margin:0 .2rem;font-size:90%;white-space:nowrap;background:#f1f1f1;border:1px solid #e1e1e1;border-radius:4px}pre>code{display:block;padding:1rem 1.5rem;white-space:pre}td,th{padding:12px 15px;text-align:left;border-bottom:1px solid #e1e1e1}td:first-child,th:first-child{padding-left:0}td:last-child,th:last-child{padding-right:0}.button,button{margin-bottom:1rem}fieldset,input,select,textarea{margin-bottom:1.5rem}blockquote,dl,figure,form,ol,p,pre,table,ul{margin-bottom:2.5rem}.u-full-width{width:100%;box-sizing:border-box}.u-max-full-width{max-width:100%;box-sizing:border-box}.u-pull-right{float:right}.u-pull-left{float:left}hr{margin-top:3rem;margin-bottom:3.5rem;border-width:0;border-top:1px solid #e1e1e1}.container:after,.row:after,.u-cf{content:"";display:table;clear:both}</style>
</head>
<body>
	<div class="container" style="margin:2rem auto;">
		<img style="padding: 50 0;" height="50" src="https://demo.glycon.xyz/logo.png"><hr><h5>Glycon | Kurulum Sistemi</h5>
<?php if($errorText): ?>
	<label>Kurulum Kayıtları:</label>
	<textarea style="height:130px;resize:none;" class="u-full-width" disabled>
	    
            <?=$errorText?>
            
	</textarea>
<?php endif; ?>
		<form action="" method="POST">
		    <div class="row">
		<div class="twelve columns">
			<label for="lisans_key">Lisans Kodunuz:</label>
			<input name="lisans_key" class="u-full-width" type="text" placeholder="GLYCON-XXXX-XXXX-XXXX-XXXX" required>
		</div>
		</div>
		<div class="row">
		<div class="six columns">
			<label for="dbHost">Veri Tabanı Sunucusu:</label>
			<input name="db_host" class="u-full-width" type="text" value="localhost" required>
		</div>
		<div class="six columns">
			<label for="dbName">Veri Tabanı Adı:</label>
			<input name="db_name" class="u-full-width" type="text" required>
		</div>
		</div>
		<div class="row">
		<div class="six columns">
			<label for="dbUser">Veri Tabanı Kullanıcı Adı:</label>
			<input name="db_user" class="u-full-width" type="text" required>
		</div>
		<div class="six columns">
			<label for="dbPass">Veri Tabanı Kullanıcı Parolası:</label>
			<input name="db_pass" class="u-full-width" type="text" required>
		</div>
		</div>
		<input class="button-primary" type="submit" value="Scripti Kur">
		</form>
	</div>
</body>
</html>
