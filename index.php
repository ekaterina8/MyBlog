<?php
session_start();

require_once __DIR__.'/vendor/autoload.php';			//для автоматической загрузки файлов

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'myblog',
    'username'  => 'root',
    'password'  => '1111',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// $capsule->setAsGlobal();

$capsule->bootEloquent();


// class Example
// {
// 	public function __get($param)
// 	{
// 		return $param;
// 	}

// 	public function __set($param, $value)
// 	{
// 		var_dump($param, $value);
// 	}
// }

function render($view, $params = array())
{
	ob_start();
	extract($params);

	// var_dump($foo);
	// var_dump($lorem);

	include "views/".$view.".php";
	$view = ob_get_contents();
	ob_end_clean();
	return $view;
}

function slug($str, $delimiter='-') 
{
    $cyrylicFrom = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $cyrylicTo   = array('A', 'B', 'W', 'G', 'D', 'Ie', 'Io', 'Z', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'Ch', 'C', 'Tch', 'Sh', 'Shtch', '', 'Y', '', 'E', 'Iu', 'Ia', 'a', 'b', 'w', 'g', 'd', 'ie', 'io', 'z', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'ch', 'c', 'tch', 'sh', 'shtch', '', 'y', '', 'e', 'iu', 'ia'); 

    $clean = str_replace($cyrylicFrom, $cyrylicTo, $str); 
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower($clean);
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

    return trim($clean, '-');
}

$app = new Silex\Application();
$app["debug"] = true;

$app->get('/', function () {
	
	// $example = new Example();
	// $name = $example->name;
	// // var_dump($name);

	// $example->fname = "kate";
	// // var_dump($example->fname);

	// $tasks = Capsule::table('tasks')->get();
	// var_dump($tasks);

	// ob_start();
	// include "views/home.php";
	// $view = ob_get_contents();
	// ob_end_clean();
		// return $view;

	// return render("home", array(
	// 	"foo" => "bar",
	// 	"lorem" => "ipsum"
	// ));

	$articles = Articles::all();
    return render("home", array(
        "articles" => $articles
    ));
});

$app->get("/blog/add", function () use ($app){

	if (!Auth::instance()->isAuth()){
		return $app->abort(404, "Вы не авторизированны!");
	}
	return render("add");
});

$app->post("/blog/add", function () use ($app) {

	if (!Auth::instance()->isAuth()){
		return $app->abort(404, "Вы не авторизированны!");
	}

	$article = new Articles();
	$article->title = $_POST['title'];
	$article->body = $_POST['body'];
	$article->slug = $_POST['slug'];
	$article->user_id = Auth::instance()->id();
	
	$article->save();
	// var_dump($article->save());
	// die();
	return $app->redirect('/index.php/blog/add');
	// ob_start();
	// include "views/add.php";
	// $view = ob_get_contents();
	// ob_end_clean();
	// return $view;
});

$app->get('/blog/view/{slug}', function ($slug) {
	$article = Articles::where("slug", "=", $slug)->first();
    return render("view", array(
        "article" => $article
    ));
});

$app->get('/blog/edit/{id}', function ($id) use ($app) {
	
    if (!Auth::instance()->isAuth()) {
        return $app->abort(404, "Это не ваша статья.");
    }

    $article = Articles::find($id);

    if (!Auth::instance()->isOwner($article)) {
        return $app->abort(404, "Это не ваша статья.");
    }

    return render("edit", array(
        "article" => $article
    ));
});

$app->post('/blog/edit/{id}', function ($id) use ($app) {
    
	if (!Auth::instance()->isAuth()) {
        return $app->abort(404, "Это не вашаа статья.");
    }

    $article = Articles::find($id);

    if (!Auth::instance()->isOwner($article)) {
        return $app->abort(404, "Это не ваша статья.");
    }

    $article->title = $_POST['title'];
    $article->body = $_POST['body'];
    $article->slug = slug($_POST['title']);
    $article->save();

    return $app->redirect('/index.php/blog/edit/'.$id);
});

$app->get('/blog/remove/{id}', function ($id) use ($app) {
    $article = Articles::find($id); 
    $article->delete();
    return $app->redirect('/');
});

$app->get('/example', function () use ($app) {
	return render ("example");
});

$app->get('/ajax/{id}', function ($id) use ($app) {

    $article = Articles::find($id); 
    return $article->title;
});

$user = include "user.php";
$comments = include "comments.php";

$app->mount('/user', $user);
$app->mount('/comments', $comments);

$app->run();
