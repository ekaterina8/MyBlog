<?php
$comments = $app['controllers_factory'];

$comments->post('/add/{id}', function ($id) use ($app) {
    if (!Auth::instance()->isAuth()) {
        return $app->abort(403, "go on.");
    }

    $comment = new Comments();
    $comment->subject = $_POST["subject"];
    $comment->comment = $_POST["comment"];
    $comment->article_id = $id;
    $comment->save();

    $result = "";
    foreach (Articles::find($id)->comments()->orderBy("id", "DESC")->get() as $comment) {
        $result .= "<h4>" . $comment->subject . "</h4>";
        $result .= "<p>" . $comment->comment . "</p><hr />";
    }
    return $result;
});

$comments->get('/list', function () use ($app) {
    // return render('registration');
});

return $comments;