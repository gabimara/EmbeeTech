<section id="reset-password" class="min-h-screen relative px-6 py-20 lg:px-16">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-[2rem] border border-purple-800 bg-purple-900/80 p-10 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
            <div class="mb-8">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Recuperação de senha</p>
                <h1 class="mt-3 text-4xl font-extrabold text-white">Redefina sua senha</h1>
                <p class="mt-4 text-slate-300">Informe uma nova senha para sua conta usando o link de recuperação.</p>
            </div>
            <form action="index.php" method="post" class="space-y-6">
                <input type="hidden" name="action" value="reset_password">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <label class="block text-slate-300">
                    <span>Nova senha</span>
                    <input type="password" name="password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="Nova senha" required>
                </label>
                <label class="block text-slate-300">
                    <span>Confirmar nova senha</span>
                    <input type="password" name="confirm_password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="Confirmar nova senha" required>
                </label>
                <button type="submit" class="w-full rounded-full bg-amber-400 px-6 py-4 font-semibold text-slate-950 hover:bg-amber-300 transition">Redefinir senha</button>
            </form>
        </div>
    </div>
</section>
