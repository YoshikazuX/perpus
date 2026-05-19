<?php
function getPaginationData($koneksi, $countQuery, $perPage = 5, $pageParam = 'page')
{
    $currentPage = isset($_GET[$pageParam]) ? (int) $_GET[$pageParam] : 1;
    if ($currentPage < 1) {
        $currentPage = 1;
    }

    $countResult = mysqli_query($koneksi, $countQuery);
    $countRow = $countResult ? mysqli_fetch_assoc($countResult) : ['total' => 0];
    $totalRows = (int) ($countRow['total'] ?? 0);
    $totalPages = max(1, (int) ceil($totalRows / $perPage));

    if ($currentPage > $totalPages) {
        $currentPage = $totalPages;
    }

    return [
        'current_page' => $currentPage,
        'per_page' => $perPage,
        'total_rows' => $totalRows,
        'total_pages' => $totalPages,
        'offset' => ($currentPage - 1) * $perPage,
    ];
}

function paginationUrl($page, $pageParam = 'page')
{
    $params = $_GET;
    $params[$pageParam] = $page;

    return '?' . htmlspecialchars(http_build_query($params), ENT_QUOTES, 'UTF-8');
}

function renderPagination($pagination, $pageParam = 'page')
{
    if ($pagination['total_pages'] <= 1) {
        return;
    }

    $currentPage = $pagination['current_page'];
    $totalPages = $pagination['total_pages'];
    ?>
    <nav class="pagination" aria-label="Pagination">
        <?php if ($currentPage > 1) { ?>
            <a href="<?php echo paginationUrl($currentPage - 1, $pageParam); ?>" class="pagination-link">Sebelumnya</a>
        <?php } else { ?>
            <span class="pagination-link disabled">Sebelumnya</span>
        <?php } ?>

        <?php for ($page = 1; $page <= $totalPages; $page++) { ?>
            <?php if ($page === $currentPage) { ?>
                <span class="pagination-link active"><?php echo $page; ?></span>
            <?php } else { ?>
                <a href="<?php echo paginationUrl($page, $pageParam); ?>" class="pagination-link"><?php echo $page; ?></a>
            <?php } ?>
        <?php } ?>

        <?php if ($currentPage < $totalPages) { ?>
            <a href="<?php echo paginationUrl($currentPage + 1, $pageParam); ?>" class="pagination-link">Berikutnya</a>
        <?php } else { ?>
            <span class="pagination-link disabled">Berikutnya</span>
        <?php } ?>
    </nav>
    <?php
}
