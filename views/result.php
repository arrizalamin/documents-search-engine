<!DOCTYPE html>
<html lang="en">
<?php require_once 'head.php' ?>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Search Engine</a>
        </div>
        <div class="navbar-collapse collapse" id="searchbar">
            <form class="navbar-form">
                <div class="form-group" style="display:inline;">
                    <div class="input-group" style="display:table;">
                        <input class="form-control" placeholder="Query" autocomplete="off" autofocus="autofocus" type="text" name="q" value="<?php echo $query ?>">
                        <span class="input-group-btn" style="width:1%;">
                            <button class="btn btn-default">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <p>Result for : <?php echo $query ?></p>
    <?php foreach ($result as $document) : ?>
        <div class="row">
            <div class="result-item well well-sm">
                <h3 class="title m0">
                    <a href="<?php echo $dir . '/' . $document['title'] . '.txt' ?>">
                        <?php echo $document['title'] ?>
                    </a>
                </h3>
                <strong>
                    <small>Cosine Similarity: <?php echo $document['cosine-similarity'] ?></small>
                </strong>
                <p>
                    <?php echo substr($document['content'], 0, 30) . '...' ?>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>