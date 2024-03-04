<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>URL Shortener</title>
</head>
<body>
<main>
    <form method="POST" action="/">
        <div>
            <label for="url">URL to shorten <span style="color: red" title="Required">*</span></label>
            <input id="url" type="text" name="url" autofocus value="">
        </div>
        <div>
            <button type="submit" name="submit" value="generate">Generate</button>
        </div>
    </form>
    <div>
        <?php if (isset($shortUrl) && isset($srcUrl)) {echo 'Your short URL for <b>' . $srcUrl . '</b> is <b>' . $shortUrl . '</b>';} ?>
        <?php if (isset($error)) {echo '<span style="color: red">' . $error . '</span>';} ?>
    </div>
</main>
</body>
</html>
