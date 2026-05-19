<?php
function renderSearchForm($placeholder)
{
    $keyword = trim($_GET['keyword'] ?? '');
    ?>
    <form method="get" class="table-search-form">
        <div class="table-search-box">
            <span class="table-search-icon" aria-hidden="true"></span>
            <input
                type="text"
                name="keyword"
                class="table-search-input"
                placeholder="<?php echo htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8'); ?>"
                value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"
            >
            <button type="submit" class="search-submit-hidden">Cari</button>
        </div>
    </form>
    <?php
}
