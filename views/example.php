<?php
$name = ".intro .foo";
?>
<html>
    <head>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        
        <script type="text/javascript">
            $(function(){

                $(".articles").click(function(){
                    $.ajax({
                      url: "ajax/"+ $(this).attr("data-id"),
                      success: function(data) {
                        console.log(data.status)
                        $("#block").append("<p>" + data + "</p>")
                      }
                    })
                })

            })
        </script>
    </head>
    <body>
        <h1>Мой блог</h1>
        <h2>jQuery</h2>
        <div id="block"></div>
        <table>
            <thead>
                    <tr style="width:400px;">
                        <th>id</th>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($articles as $article): ?>
                    <tr>
                        <td><?php echo $article->id ?></td>
                        <td><?php echo $article->title ?></td>
                        <td>
                        <a href="javascript:{}" class="articles" data-id="<?php echo $article->id ?>">Click me!</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </body>
</html>