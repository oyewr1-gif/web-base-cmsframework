<div style="max-width: 720px; margin: 0 auto;">

    <?php if (!empty($error) || empty($article)): ?>
        <div style="text-align: center; padding: 40px;">
            <h1 style="color: #ef4444;">Artikel Tidak Ditemukan</h1>
            <p style="color: #64748b;"><?= Security::e($error ?? 'Artikel yang Anda cari tidak tersedia.') ?></p>
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/blog"
               style="color: #2563eb; text-decoration: none;">&larr; Kembali ke Daftar Artikel</a>
        </div>
    <?php else: ?>
        <article>
            <h1 style="font-size: 1.75rem; margin-bottom: 8px; color: #1e293b;">
                <?= Security::e($article['judul'] ?? '') ?>
            </h1>

            <div style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 24px; display: flex; gap: 16px; align-items: center;">
                <?php if (!empty($article['created_at'])): ?>
                    <span>📅 <?= Security::e($article['created_at']) ?></span>
                <?php endif; ?>

                <?php if (!empty($article['status'])): ?>
                    <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;
                        background: <?= $article['status'] === 'publish' ? '#dcfce7' : '#fef9c3' ?>;
                        color: <?= $article['status'] === 'publish' ? '#166534' : '#854d0e' ?>;">
                        <?= Security::e(ucfirst($article['status'])) ?>
                    </span>
                <?php endif; ?>
            </div>

            <hr style="border: none; border-top: 1px solid #e2e8f0; margin-bottom: 24px;">

            <div style="line-height: 1.8; color: #334155; font-size: 1rem;">
                <?= $article['konten'] ?? '' ?>
            </div>
        </article>

        <hr style="border: none; border-top: 1px solid #e2e8f0; margin-top: 32px;">

        <div style="margin-top: 16px;">
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/artikel"
               style="color: #2563eb; text-decoration: none; font-weight: 500;">
                &larr; Kembali ke Daftar Artikel
            </a>
        </div>
    <?php endif; ?>

</div>
