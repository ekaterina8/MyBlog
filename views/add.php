<html>
	<head>
		<title></title>
	</head>
	<body>
		<h1>Мой блог</h1>
		<h2>Новая статья</h2>
		<form method="post" action="/index.php/blog/add">

			<label>title</label>
			<input type="text" name="title"/><br />

			<label>slug</label>
			<input type="slug" name="slug"/><br />

			<label>text</label>
			<textarea type="body" name="body"></textarea><br />
			<input type="submit" />
			
		</form>
	</body>
</html>