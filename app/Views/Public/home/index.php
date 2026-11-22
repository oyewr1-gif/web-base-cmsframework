<h1><?= Security::e($title ?? 'Selamat Datang di Beranda Artikel') ?></h1>
<p>Halaman depan publik berhasil dimuat menggunakan arsitektur Modular MVC.</p>

<?php if (!empty($articles)): ?>
    <h2 style="margin-top: 32px;">Artikel Terbaru</h2>
    <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 12px;">
        <?php
        // Tampilkan maksimal 5 artikel terbaru di beranda
        $latestArticles = array_slice($articles, 0, 5);
        foreach ($latestArticles as $article):
        ?>
            <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; background: #fff;">
                <h3 style="margin: 0 0 6px 0; font-size: 1.1rem;">
                    <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/artikel/detail/<?= (int) $article['id'] ?>"
                       style="text-decoration: none; color: #1e293b;">
                        <?= Security::e($article['judul'] ?? '') ?>
                    </a>
                </h3>
                <p style="color: #94a3b8; font-size: 0.8rem; margin: 0 0 8px 0;">
                    📅 <?= Security::e($article['created_at'] ?? '') ?>
                </p>
                <p style="color: #475569; margin: 0; line-height: 1.5;">
                    <?= Security::e(mb_substr(strip_tags($article['konten'] ?? ''), 0, 150)) ?><?php if (mb_strlen(strip_tags($article['konten'] ?? '')) > 150) echo '...'; ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($articles) > 5): ?>
        <p style="margin-top: 16px;">
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/blog"
               style="color: #2563eb; text-decoration: none; font-weight: 500;">
                Lihat Semua Artikel &rarr;
            </a>
        </p>
    <?php endif; ?>
<?php endif; ?>
