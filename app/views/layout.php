<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embee Tech | Soluções em TI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
    <script defer src="script.js"></script>
</head>
<body class="bg-gradient-to-br from-purple-950 via-purple-900 to-violet-950 text-slate-100 overflow-x-hidden">
    <div class="fixed inset-0 pointer-events-none bg-[radial-gradient(ellipse_at_top_left,_rgba(245,158,11,0.15),_transparent_40%),radial-gradient(ellipse_at_bottom_right,_rgba(168,85,247,0.12),_transparent_50%)]"></div>
    <div class="min-h-screen flex">
        <aside class="w-80 hidden lg:flex flex-col justify-between border-r border-purple-800 bg-purple-950/90 backdrop-blur-2xl px-8 py-10">
            <div>
                <div class="mb-10">
                    <div class="text-4xl font-black tracking-tight text-amber-400">embee</div>
                    <div class="text-sm uppercase text-slate-400">tecnologia</div>
                </div>
                <nav class="space-y-4">
                    <a href="index.php" class="menu-link block rounded-2xl px-4 py-3 <?= $page === 'home' ? 'bg-amber-500/20' : 'bg-slate-900/80' ?> hover:bg-amber-500/20 transition">Início</a>
                    <a href="index.php?page=tickets" class="menu-link block rounded-2xl px-4 py-3 <?= $page === 'tickets' ? 'bg-violet-500/20' : 'bg-slate-900/80' ?> hover:bg-violet-500/20 transition">Chamados</a>
                    <?php if ($currentUser && $currentUser['role'] === 'admin'): ?>
                        <a href="index.php?page=admin" class="menu-link block rounded-2xl px-4 py-3 <?= $page === 'admin' ? 'bg-emerald-500/20' : 'bg-slate-900/80' ?> hover:bg-emerald-500/20 transition">Admin</a>
                    <?php endif; ?>
                </nav>
            </div>
            <div class="space-y-3">
                <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 shadow-[0_0_60px_rgba(245,158,11,0.1)]"></
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Olá</p>
                    <?php if ($currentUser): ?>
                        <p class="mt-3 text-lg font-semibold text-white"><?= htmlspecialchars($currentUser['name']) ?></p>
                        <p class="text-sm text-slate-400">Função: <?= htmlspecialchars($currentUser['role']) ?></p>
                    <?php else: ?>
                        <p class="mt-3 text-lg font-semibold text-white">Visitante</p>
                        <p class="text-sm text-slate-400">Faça login para abrir ou administrar chamados.</p>
                    <?php endif; ?>
                </div>
                <?php if ($currentUser): ?>
                    <form method="post">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="w-full rounded-2xl bg-red-500 px-4 py-3 text-slate-950 font-semibold hover:bg-red-400 transition">Sair</button>
                    </form>
                <?php endif; ?>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5">
                    <p class="text-sm text-slate-400">Acessos de teste</p>
                    <p class="mt-2 text-sm"><span class="font-semibold">Admin:</span> admin@embee.com / admin123</p>
                    <p class="mt-1 text-sm"><span class="font-semibold">Usuário:</span> user@embee.com / user123</p>
                </div>
            </div>
        </aside>

        <main class="flex-1 relative">
            <div class="relative z-10 px-6 py-10 lg:px-16 lg:py-14">
                <?php if ($flashMessage): ?>
                    <div class="mb-6 rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-5 text-emerald-200"><?= htmlspecialchars($flashMessage) ?></div>
                <?php endif; ?>
                <?php if ($flashError): ?>
                    <div class="mb-6 rounded-3xl border border-rose-500/20 bg-rose-500/10 p-5 text-rose-200"><?= htmlspecialchars($flashError) ?></div>
                <?php endif; ?>
                <?= $content ?>
            </div>
        </main>
    </div>
</body>
</html>
