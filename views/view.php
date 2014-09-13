<html>
    <head>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(function(){

                $("#comments-form input[type=button]").click(function(){

                    $.ajax({
                        url: $("#comments-form").attr("action"),
                        type: "post",
                        data: $("#comments-form").serialize(),
                        success: function(data) {
                            $("#comments-list").html(data)
                      }
                    })
                })
            })
        </script>
    </head>
    <body>
        <h1>Мой блог</h1>
        <h2><?php echo $article->title; ?></h2>
        <?php echo $article->body; ?>
        <hr />
        <?php if (Auth::instance()->isAuth()):  ?>
        <form action="/index.php/comments/add/<?php echo $article->id ?>" 
            id="comments-form" method="post">
            <label>Subject</label>
            <input type="text" name="subject" /><br />
            <label>Comment</label>
            <textarea name="comment" ></textarea>
            <input type="button" value="Добавить" />
        </form>
        <?php endif; ?>
        <div id="comments-list">
            <?php foreach ($article->comments()->orderBy("id", "DESC")->get() as $comment): ?>
                <h4><?php echo $comment->subject; ?></h4>
                <p><?php echo $comment->comment; ?></p>
                <hr />
            <?php endforeach; ?>
        </div>
    </body>
</html>