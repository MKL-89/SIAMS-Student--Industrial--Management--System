<?php if (current_role()): ?>
        </main>
        <footer class="app-footer">
            &copy; <?= date('Y') ?> <?= e(APP_FULL_NAME) ?> · <?= e(INSTITUTION) ?>
        </footer>
    </div>
</div>
<?php else: ?>
</main>
<footer class="public-footer">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span><i class="bi bi-gear-wide-connected me-1"></i><strong><?= APP_NAME ?></strong> · <?= e(APP_FULL_NAME) ?></span>
        <span>&copy; <?= date('Y') ?> <?= e(INSTITUTION) ?></span>
    </div>
</footer>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/app.js?v=<?= filemtime(__DIR__ . "/../assets/js/app.js") ?>"></script>
</body>
</html>
