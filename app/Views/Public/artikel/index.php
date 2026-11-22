<h1><?= Security::e($title ?? 'Semua Artikel') ?></h1>

<?php if (!empty($articles)): ?>
    <div style="display: flex; flex-direction: column; gap: 24px; margin-top: 20px;">
        <?php foreach ($articles as $article): ?>
            <article style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background: #fff;">
                <h2 style="margin: 0 0 8px 0; font-size: 1.25rem;">
                    <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/blog/detail/<?= (int) $article['id'] ?>"
                       style="text-decoration: none; color: #1e293b;">
                        <?= Security::e($article['judul'] ?? '') ?>
                    </a>
                </h2>

                <?php if (!empty($article['status'])): ?>
                    <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;
                        background: <?= $article['status'] === 'publish' ? '#dcfce7' : '#fef9c3' ?>;
                        color: <?= $article['status'] === 'publish' ? '#166534' : '#854d0e' ?>;">
                        <?= Security::e(ucfirst($article['status'])) ?>
                    </span>
                <?php endif; ?>

                <p style="color: #64748b; font-size: 0.85rem; margin: 8px 0 12px 0;">
                    <?php if (!empty($article['created_at'])): ?>
                        📅 <?= Security::e($article['created_at']) ?>
                    <?php endif; ?>
                </p>

                <p style="color: #475569; line-height: 1.6;">
                    <?= Security::e(mb_substr(strip_tags($article['konten'] ?? ''), 0, 200)) ?><?php if (mb_strlen(strip_tags($article['konten'] ?? '')) > 200) echo '...'; ?>
                </p>

                <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/artikel/detail/<?= (int) $article['id'] ?>"
                   style="color: #2563eb; text-decoration: none; font-weight: 500;">
                    Baca Selengkapnya &rarr;
                </a>
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div style="text-align: center; padding: 40px; color: #94a3b8;">
        <p style="font-size: 1.1rem;">📝 Belum ada artikel yang dipublikasikan.</p>
        <p style="font-size: 0.9rem;">Silakan tambahkan artikel melalui <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin" style="color: #2563eb;">panel admin</a>.</p>
    </div>
<?php endif; ?>
