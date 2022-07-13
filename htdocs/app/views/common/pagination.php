<nav class="pagination" role="navigation" aria-label="pagination" data-page="<?php echo $data['page'] ?>" data-pages="<?php echo $data['pages'] ?>">
    <a class="pagination-previous button is-white" title="This is the first page" href="/posts/page/<?php echo $data['page'] - 1 ?>">Previous</a>
    <a class="pagination-next button is-primary" href="/posts/page/<?php echo $data['page'] + 1 ?>">Next page</a>
</nav>