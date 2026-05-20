<section id="admin" class="relative px-6 py-20 lg:px-16">
    <div class="max-w-6xl mx-auto">
        <div class="mb-12 rounded-[2rem] border border-purple-800 bg-purple-900/80 p-10 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Painel Admin</p>
                    <h2 class="mt-3 text-4xl font-extrabold text-white">Gerencie chamados e mantenha a equipe alinhada</h2>
                    <p class="mt-4 text-slate-300">Atualize status, atribua responsabilidades e acompanhe o fluxo de tickets em tempo real.</p>
                </div>
                <div class="rounded-full bg-amber-500/10 px-5 py-3 text-amber-200 text-sm">Acesso exclusivo</div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
                <h3 class="text-2xl font-bold text-white">Categorias</h3>
                <div class="mt-6 flex flex-wrap gap-3">
                    <?php foreach ($categories as $category): ?>
                        <span class="rounded-full border border-amber-500/20 bg-amber-500/10 px-4 py-2 text-sm text-amber-200"><?= htmlspecialchars($category['name']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
                <h3 class="text-2xl font-bold text-white">Tipos de Serviço</h3>
                <div class="mt-6 flex flex-wrap gap-3">
                    <?php foreach ($serviceTypes as $type): ?>
                        <span class="rounded-full border border-violet-500/20 bg-violet-500/10 px-4 py-2 text-sm text-violet-200"><?= htmlspecialchars($type['name']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="mt-10 overflow-x-auto rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
            <h3 class="text-2xl font-bold text-white">Chamados em aberto</h3>
            <div class="mt-6 min-w-full overflow-hidden rounded-3xl border border-purple-800">
                <table class="min-w-full text-left text-sm text-slate-300">
                    <thead class="bg-purple-950/95 text-slate-400">
                        <tr>
                            <th class="px-6 py-4">Título</th>
                            <th class="px-6 py-4">Categoria</th>
                            <th class="px-6 py-4">Tipo</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Abertura</th>
                            <th class="px-6 py-4">Atribuído</th>
                            <th class="px-6 py-4">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php if (empty($tickets)): ?>
                            <tr><td class="px-6 py-8 text-center text-slate-500" colspan="7">Nenhum chamado registrado ainda.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr class="hover:bg-purple-900/90 transition">
                                <td class="px-6 py-5 font-semibold text-white"><?= htmlspecialchars($ticket['title']) ?></td>
                                <td class="px-6 py-5"><?= htmlspecialchars($ticket['category_name']) ?></td>
                                <td class="px-6 py-5"><?= htmlspecialchars($ticket['service_type_name']) ?></td>
                                <td class="px-6 py-5 text-amber-400"><?= htmlspecialchars($ticket['status']) ?></td>
                                <td class="px-6 py-5"><?= htmlspecialchars($ticket['created_at']) ?></td>
                                <td class="px-6 py-5"><?= htmlspecialchars($ticket['assigned_to'] ?? 'Não atribuído') ?></td>
                                <td class="px-6 py-5">
                                    <details class="rounded-3xl border border-purple-800 bg-purple-950/80 p-4">
                                        <summary class="cursor-pointer font-medium text-slate-200">Editar</summary>
                                        <form action="index.php" method="post" class="mt-4 space-y-3">
                                            <input type="hidden" name="action" value="update_ticket">
                                            <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticket['id']) ?>">
                                            <select name="status" class="w-full rounded-3xl border border-purple-700 bg-purple-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-400">
                                                <option value="Aberto" <?= $ticket['status'] === 'Aberto' ? 'selected' : '' ?>>Aberto</option>
                                                <option value="Em andamento" <?= $ticket['status'] === 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
                                                <option value="Concluído" <?= $ticket['status'] === 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                                            </select>
                                            <label class="block text-slate-300">
                                                <span>Responsável</span>
                                                <input type="text" name="assigned_to" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-400" value="<?= htmlspecialchars($ticket['assigned_to'] ?? 'Administrador') ?>">
                                            </label>
                                            <button type="submit" class="w-full rounded-full bg-violet-500 px-4 py-3 font-semibold text-white hover:bg-violet-400 transition">Atualizar</button>
                                        </form>
                                    </details>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
