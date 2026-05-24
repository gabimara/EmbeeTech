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

        <div class="mt-6 rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
            <h3 class="text-2xl font-bold text-white">Cadastrar Administrador</h3>
            <p class="mt-2 text-slate-400">Crie novas contas de administrador diretamente pelo painel.</p>
            <form action="index.php" method="post" class="mt-6 space-y-4">
                <input type="hidden" name="action" value="create_admin">
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block text-slate-300">
                        <span>Nome completo</span>
                        <input type="text" name="name" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="Nome do administrador" required>
                    </label>
                    <label class="block text-slate-300">
                        <span>Email</span>
                        <input type="email" name="email" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="admin@exemplo.com" required>
                    </label>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block text-slate-300">
                        <span>Senha</span>
                        <input type="password" name="password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="••••••••" required>
                    </label>
                    <label class="block text-slate-300">
                        <span>Confirmar senha</span>
                        <input type="password" name="confirm_password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="••••••••" required>
                    </label>
                </div>
                <div class="flex">
                    <button type="submit" class="ml-auto rounded-full bg-amber-400 px-6 py-3 font-semibold text-slate-950 hover:bg-amber-300 transition">Criar administrador</button>
                </div>
            </form>
        </div>

        <div class="rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
            <h3 class="text-2xl font-bold text-white">Gerenciar cargos de usuários</h3>
            <p class="mt-2 text-slate-400">Altere rapidamente a função de cada conta entre administrador e usuário.</p>
            <div class="mt-6 overflow-x-auto">
                <div id="adminActionMessage" class="mb-6 hidden rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-200"></div>
                <table class="min-w-full text-left text-sm text-slate-300">
                    <thead class="bg-purple-950/95 text-slate-400">
                        <tr>
                            <th class="px-6 py-4">Nome</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Cargo</th>
                            <th class="px-6 py-4">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-purple-900/90 transition">
                                <td class="px-6 py-4 font-semibold text-white"><?= htmlspecialchars($user['name']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="px-6 py-4 capitalize"><?= htmlspecialchars($user['role']) ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <form action="index.php" method="post" class="flex items-center gap-3">
                                            <input type="hidden" name="action" value="update_user_role">
                                            <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                            <select name="role" class="rounded-3xl border border-purple-700 bg-purple-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-400">
                                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Usuário</option>
                                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                            </select>
                                            <button type="submit" class="rounded-full bg-amber-400 px-4 py-3 text-slate-950 font-semibold hover:bg-amber-300 transition">Salvar</button>
                                        </form>
                                        <button type="button" data-toggle-password-row="<?= (int) $user['id'] ?>" class="rounded-full border border-purple-700 bg-slate-900 px-4 py-3 text-slate-200 font-semibold hover:border-amber-400 transition">Alterar senha</button>
                                        <?php if ($user['id'] !== $currentUser['id']): ?>
                                            <form action="index.php" method="post" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                                <button type="submit" class="rounded-full bg-red-500 px-4 py-3 text-white font-semibold hover:bg-red-400 transition">Excluir</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <tr id="passwordRow-<?= (int) $user['id'] ?>" class="hidden bg-purple-950/80">
                                <td colspan="4" class="px-6 py-5">
                                    <form action="index.php" method="post" class="grid gap-4 lg:grid-cols-3 items-end">
                                        <input type="hidden" name="action" value="update_user_password">
                                        <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                        <label class="block text-slate-300">
                                            <span>Nova senha</span>
                                            <input type="password" name="new_password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-400" placeholder="Nova senha" required>
                                        </label>
                                        <label class="block text-slate-300">
                                            <span>Confirmar senha</span>
                                            <input type="password" name="confirm_password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-400" placeholder="Confirmar senha" required>
                                        </label>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <button type="submit" class="rounded-full bg-emerald-500 px-4 py-3 text-slate-950 font-semibold hover:bg-emerald-400 transition">Salvar nova senha</button>
                                            <button type="button" data-toggle-password-row="<?= (int) $user['id'] ?>" class="rounded-full border border-purple-700 bg-slate-900 px-4 py-3 text-slate-200 font-semibold hover:border-amber-400 transition">Cancelar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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

        <?php $showArchived = isset($_GET['archived']) && $_GET['archived'] === '1'; ?>
        <div class="mt-10 overflow-x-auto rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-white"><?= $showArchived ? 'Arquivados' : 'Chamados em aberto' ?></h3>
                <div>
                    <?php if ($showArchived): ?>
                        <a href="index.php?page=admin" class="rounded-full border border-purple-700 bg-slate-900 px-4 py-2 text-sm text-slate-200 hover:border-amber-400 transition">Voltar</a>
                    <?php else: ?>
                        <a href="index.php?page=admin&archived=1" class="rounded-full border border-purple-700 bg-slate-900 px-4 py-2 text-sm text-slate-200 hover:border-amber-400 transition">Mostrar arquivados</a>
                    <?php endif; ?>
                </div>
            </div>
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
                                <?php
                                    $detailsText = $ticket['details'];
                                    $responseText = '';
                                    // Support both old and new response formats
                                    $markerPos = strpos($detailsText, "\n\nResposta do admin:");
                                    if ($markerPos === false) {
                                        $markerPos = strpos($detailsText, "\n\nResposta de ");
                                    }
                                    if ($markerPos !== false) {
                                        $responsePart = substr($detailsText, $markerPos + 2);
                                        // try to remove the marker from details
                                        $detailsText = substr($detailsText, 0, $markerPos);
                                        // remove leading marker text for response display
                                        $responseText = trim($responsePart);
                                    }

                                    $ticketData = htmlspecialchars(json_encode([
                                        'id' => $ticket['id'],
                                        'title' => $ticket['title'],
                                        'category' => $ticket['category_name'],
                                        'service_type' => $ticket['service_type_name'],
                                        'status' => $ticket['status'],
                                        'created_at' => $ticket['created_at'],
                                        'assigned_to' => $ticket['assigned_to'] ?? 'Administrador',
                                        'details' => trim($detailsText),
                                        'response' => trim($responseText),
                                    ], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                                ?>
                                <td class="px-6 py-5">
                                    <button type="button" data-ticket='<?= $ticketData ?>' class="open-ticket-modal w-full rounded-3xl border border-purple-800 bg-purple-950/80 px-4 py-3 text-left text-slate-200 transition hover:bg-purple-900/90">Ver / Responder</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="ticketModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-4">
        <div class="relative w-full max-w-3xl max-h-[90vh] overflow-hidden overflow-y-auto rounded-[2rem] border border-purple-800 bg-purple-950/95 p-6 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
            <button id="closeTicketModal" type="button" class="absolute right-5 top-5 rounded-full bg-purple-900/80 px-4 py-2 text-sm text-slate-200 hover:bg-purple-900 transition">Fechar</button>
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Detalhes do chamado</p>
                    <h3 id="modalTicketTitle" class="mt-3 text-3xl font-extrabold text-white"></h3>
                </div>
                <div id="modalTicketStatus" class="rounded-full bg-amber-500/10 px-4 py-2 text-amber-200 text-sm"></div>
            </div>
            <div class="grid gap-4 md:grid-cols-2 mb-6 text-slate-300">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Categoria</p>
                    <p id="modalTicketCategory" class="mt-2 text-white"></p>
                </div>
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Tipo de serviço</p>
                    <p id="modalTicketType" class="mt-2 text-white"></p>
                </div>
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Abertura</p>
                    <p id="modalTicketCreatedAt" class="mt-2 text-white"></p>
                </div>
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Atribuído</p>
                    <p id="modalTicketAssignedTo" class="mt-2 text-white"></p>
                </div>
            </div>
            <div class="space-y-4 mb-6">
                <div>
                    <p class="font-semibold text-white">Pedido</p>
                    <div id="modalTicketDetails" class="whitespace-pre-line mt-3 rounded-3xl border border-purple-800 bg-purple-900/80 p-4 text-slate-200"></div>
                </div>
                <div id="modalTicketResponseWrapper" class="hidden rounded-3xl border border-violet-600/20 bg-violet-500/5 p-4">
                    <p class="font-semibold text-violet-200">Resposta do admin</p>
                    <div id="modalTicketResponse" class="whitespace-pre-line mt-3 text-slate-200"></div>
                </div>
            </div>
            <form id="modalTicketForm" action="index.php" method="post" class="space-y-4">
                <input type="hidden" name="action" value="update_ticket">
                <input type="hidden" id="modalTicketId" name="ticket_id" value="">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-slate-300">
                            <span>Status</span>
                            <select id="modalTicketStatusSelect" name="status" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-400">
                                <option value="Aberto">Aberto</option>
                                <option value="Em andamento">Em andamento</option>
                                <option value="Concluído">Concluído</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </label>
                    </div>
                    <div>
                        <label class="block text-slate-300">
                            <span>Responsável</span>
                            <input id="modalTicketAssignedToInput" type="text" disabled class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950/60 px-4 py-3 text-slate-200 outline-none" placeholder="Administrador">
                        </label>
                    </div>
                    <div>
                        <label class="block text-slate-300">
                            <span>Quem está respondendo</span>
                            <select name="responder_id" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-400">
                                <option value="">Selecione (opcional)</option>
                                <?php if (!empty($admins)): ?>
                                    <?php foreach ($admins as $admin): ?>
                                        <option value="<?= (int) $admin['id'] ?>"><?= htmlspecialchars($admin['name']) ?> (<?= htmlspecialchars($admin['email']) ?>)</option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </label>
                    </div>
                </div>
                <label class="block text-slate-300">
                    <span>Resposta do admin</span>
                    <textarea id="modalTicketResponseInput" name="admin_response" rows="4" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-400" placeholder="Adicione uma resposta ou comentário para o cliente."></textarea>
                </label>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="submit" class="w-full rounded-full bg-violet-500 px-4 py-3 font-semibold text-white hover:bg-violet-400 transition">Atualizar</button>
                    <button id="modalCancelTicket" type="button" class="w-full rounded-full bg-red-500 px-4 py-3 font-semibold text-slate-950 hover:bg-red-400 transition">Cancelar e arquivar</button>
                </div>
            </form>
        </div>
    </div>
</section>
