<section id="home" class="min-h-screen relative overflow-visible">
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1400&q=80')] bg-cover bg-center opacity-20 rounded-[2rem] shadow-[0_10px_20px_5px_#000000]"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-purple-950/90 via-purple-950/70 to-purple-950/50 border-amber-400/20 rounded-[2rem]"></div>
    <div class="relative z-10 p-10 rounded-[5rem]">
        <div class="flex flex-col xl:flex-row items-start justify-between gap-10">
            <?php if (!$currentUser): ?>
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-amber-500/10 px-4 py-2 text-sm uppercase tracking-[0.2em] text-amber-400">Embee Tech</span>
                <h1 class="mt-8 text-5xl md:text-6xl font-extrabold leading-tight tracking-tight text-white">Tecnologia com velocidade e alto impacto.</h1>
                <p class="mt-6 max-w-2xl text-lg text-slate-300">Transformamos seu ambiente de TI com suporte técnico, formatação, instalação de software e consultorias inteligentes. Abra chamados, acompanhe tarefas e gerencie tudo via painel.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="index.php?page=tickets" class="rounded-full bg-amber-400 px-8 py-4 text-slate-950 font-semibold shadow-[0_0_30px_rgba(245,158,11,0.35)] hover:-translate-y-1 transition">Abrir chamado</a>
                    <a href="<?= $currentUser ? 'index.php?page=tickets' : '#services' ?>" class="rounded-full border border-purple-600 px-8 py-4 text-slate-200 hover:border-amber-400 hover:text-amber-400 transition">Ver serviços</a>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($currentUser): ?>
            <div class="hidden xl:block w-96 h-96 rounded-[3rem] border border-amber-400/20 bg-white/5 p-8 backdrop-blur-2xl shadow-[0_0_90px_rgba(245,158,11,0.12)]">
                <div class="h-full rounded-[2.5rem] bg-gradient-to-br from-amber-300/10 via-violet-500/10 to-slate-900/40 p-6">
                    <div class="mb-6 flex items-center justify-between rounded-3xl border border-white/10 bg-purple-950/60 p-4 backdrop-blur-md">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Chamados</p>
                            <p class="mt-2 text-2xl font-semibold text-white">Painel Rápido</p>
                        </div>
                        <div class="text-4xl">⚡</div>
                    </div>
                    <div class="space-y-4 text-slate-300">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-3xl border border-white/10 bg-purple-950/80 p-4">Users<br><span class="text-3xl font-bold text-amber-400">+12</span></div>
                            <div class="rounded-3xl border border-white/10 bg-purple-950/80 p-4">Tickets<br><span class="text-3xl font-bold text-violet-300"><?= count($tickets) ?></span></div>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-purple-950/80 p-4">Categoria mais requisitada<br><span class="block mt-2 text-xl font-semibold text-white">Suporte Técnico</span></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($currentUser): ?>
            <div class="mt-10 rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
                <h3 class="text-2xl font-bold text-white">Alterar senha</h3>
                <p class="mt-2 text-slate-400">Atualize sua senha para manter sua conta segura.</p>
                <form action="index.php" method="post" class="mt-6 space-y-6">
                    <input type="hidden" name="action" value="change_password">
                    <div class="grid gap-6 md:grid-cols-3">
                        <label class="block text-slate-300">
                            <span>Senha atual</span>
                            <input type="password" name="current_password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="Senha atual" required>
                        </label>
                        <label class="block text-slate-300">
                            <span>Nova senha</span>
                            <input type="password" name="new_password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="Nova senha" required>
                        </label>
                        <label class="block text-slate-300">
                            <span>Confirmar senha</span>
                            <input type="password" name="confirm_password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="Confirmar nova senha" required>
                        </label>
                    </div>
                    <button type="submit" class="rounded-full bg-amber-400 px-6 py-4 font-semibold text-slate-950 hover:bg-amber-300 transition">Salvar nova senha</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($page !== 'tickets' && !$currentUser): ?>
<section id="services" class="relative px-6 py-20 lg:px-16">
    <div class="max-w-6xl mx-auto">
        <div class="mb-12 text-center">
            <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Nossos serviços</p>
            <h2 class="mt-4 text-4xl font-extrabold text-white">Soluções pensadas para cada necessidade de TI</h2>
            <p class="mt-4 text-slate-300">Atendemos com tecnologia, expertise e um visual moderno que combina com o futuro da sua empresa.</p>
        </div>
        <div class="grid gap-6 lg:grid-cols-2 xl:grid-cols-4">
            <?php foreach ($services as $service): ?>
                <article class="group rounded-[2rem] border border-purple-800 bg-purple-900/70 p-8 shadow-[0_0_80px_rgba(168,85,247,0.15)] transition hover:-translate-y-2 hover:border-amber-400/40">
                    <div class="text-5xl mb-6"><?= $service['icon'] ?></div>
                    <h3 class="text-2xl font-bold text-white"><?= $service['title'] ?></h3>
                    <p class="mt-4 text-slate-300"><?= $service['description'] ?></p>
                    <div class="mt-6 inline-flex items-center gap-2 text-amber-400 font-semibold">Saiba mais <span class="transition group-hover:translate-x-1">→</span></div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section id="tickets" class="relative px-6 py-20 lg:px-16">
    <div class="max-w-6xl mx-auto grid gap-10 lg:grid-cols-[1.7fr_1fr]">
        <div class="space-y-8">
            <div class="rounded-[2rem] border border-purple-800 bg-purple-900/80 p-10 shadow-[0_0_80px_rgba(168,85,247,0.2)] backdrop-blur-2xl">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Gestão de chamados</p>
                        <h2 class="mt-3 text-3xl font-extrabold text-white">Central de Atendimento</h2>
                    </div>
                    <div class="rounded-full bg-amber-500/10 px-4 py-2 text-amber-200 text-sm">Seguro e moderno</div>
                </div>
                <p class="mt-6 text-slate-300">Abra um chamado, acompanhe o status e permita que administradores controlem cada etapa com eficiência e transparência.</p>
            </div>

            <?php if (!$currentUser): ?>
                <div class="rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
                    <h3 class="text-2xl font-bold text-white">Login</h3>
                    <p class="mt-2 text-slate-400">Entre com sua conta para abrir ou administrar chamados.</p>
                    <form action="index.php" method="post" class="mt-8 space-y-6">
                        <input type="hidden" name="action" value="login">
                        <label class="block text-slate-300">
                            <span>Email</span>
                            <input type="email" name="email" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="admin@embee.com" required>
                        </label>
                        <label class="block text-slate-300">
                            <span>Senha</span>
                            <input type="password" name="password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="••••••••" required>
                        </label>
                        <div class="flex flex-col gap-4 sm:flex-row">
                            <button type="submit" class="flex-1 rounded-full bg-amber-400 px-6 py-4 font-semibold text-slate-950 hover:bg-amber-300 transition">Entrar</button>
                            <button id="showRegisterForm" type="button" class="flex-1 rounded-full border border-purple-700 bg-slate-900 px-6 py-4 text-slate-100 font-semibold hover:border-amber-400 transition">Cadastrar</button>
                        </div>
                    </form>
                    <p class="mt-4 text-sm text-slate-400">Esqueceu a senha? <button id="showForgotPasswordForm" type="button" class="text-amber-400 underline">Clique aqui</button></p>
                </div>

                <div id="forgotPasswordSection" class="mt-6 hidden rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
                    <h3 class="text-2xl font-bold text-white">Recuperar senha</h3>
                    <p class="mt-2 text-slate-400">Digite seu email e enviaremos um link para redefinir sua senha.</p>
                    <form action="index.php" method="post" class="mt-6 space-y-4">
                        <input type="hidden" name="action" value="send_password_reset">
                        <label class="block text-slate-300">
                            <span>Email</span>
                            <input type="email" name="email" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="seu@email.com" required>
                        </label>
                        <div class="flex flex-col gap-4 sm:flex-row">
                            <button type="submit" class="flex-1 rounded-full bg-amber-400 px-6 py-4 font-semibold text-slate-950 hover:bg-amber-300 transition">Enviar link</button>
                            <button id="hideForgotPasswordForm" type="button" class="flex-1 rounded-full border border-purple-700 bg-slate-900 px-6 py-4 text-slate-100 font-semibold hover:border-amber-400 transition">Cancelar</button>
                        </div>
                    </form>
                </div>

                <div id="registerSection" class="mt-6 hidden rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
                    <h3 class="text-2xl font-bold text-white">Cadastro</h3>
                    <p class="mt-2 text-slate-400">Crie sua conta e começe a abrir chamados agora mesmo.</p>
                    <form action="index.php" method="post" class="mt-8 space-y-6">
                        <input type="hidden" name="action" value="register">
                        <div class="grid gap-6 md:grid-cols-2">
                            <label class="block text-slate-300">
                                <span>Nome completo</span>
                                <input type="text" name="name" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="Seu nome" required>
                            </label>
                            <label class="block text-slate-300">
                                <span>Email</span>
                                <input type="email" name="email" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="seu@email.com" required>
                            </label>
                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            <label class="block text-slate-300">
                                <span>Senha</span>
                                <input type="password" name="password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="••••••••" required>
                            </label>
                            <label class="block text-slate-300">
                                <span>Confirmar senha</span>
                                <input type="password" name="confirm_password" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="••••••••" required>
                            </label>
                        </div>
                        <div class="flex flex-col gap-4 sm:flex-row">
                            <button type="submit" class="flex-1 rounded-full bg-emerald-400 px-6 py-4 font-semibold text-slate-950 hover:bg-emerald-300 transition">Cadastrar</button>
                            <button id="hideRegisterForm" type="button" class="flex-1 rounded-full border border-purple-700 bg-slate-900 px-6 py-4 text-slate-100 font-semibold hover:border-amber-400 transition">Voltar ao login</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <?php if ($currentUser && $currentUser['role'] === 'user'): ?>
                <div class="rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
                    <h3 class="text-2xl font-bold text-white">Abrir novo chamado</h3>
                    <p class="mt-2 text-slate-400">Escolha a categoria e o tipo de serviço para agilizar a resposta da nossa equipe.</p>
                    <form action="index.php" method="post" class="mt-8 space-y-6">
                        <input type="hidden" name="action" value="create_ticket">
                        <div class="grid gap-6 md:grid-cols-2">
                            <label class="block text-slate-300">
                                <span>Título</span>
                                <input type="text" name="title" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="Nome do problema" required>
                            </label>
                            <label class="block text-slate-300">
                                <span>Categoria</span>
                                <select name="category_id" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" required>
                                    <option value="">Selecione</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                        <label class="block text-slate-300">
                            <span>Tipo de Serviço</span>
                            <select name="service_type_id" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" required>
                                <option value="">Selecione</option>
                                <?php foreach ($serviceTypes as $type): ?>
                                    <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="block text-slate-300">
                            <span>Detalhes</span>
                            <textarea name="details" rows="5" class="mt-2 w-full rounded-3xl border border-purple-700 bg-purple-950 px-5 py-4 text-slate-100 outline-none focus:border-amber-400" placeholder="Descreva o problema ou necessidade." required></textarea>
                        </label>
                        <button type="submit" class="w-full rounded-full bg-emerald-400 px-6 py-4 font-semibold text-slate-950 hover:bg-emerald-300 transition">Enviar chamado</button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if ($currentUser && $currentUser['role'] === 'admin'): ?>
                <div class="rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
                    <h3 class="text-2xl font-bold text-white">Painel administrativo</h3>
                    <p class="mt-2 text-slate-400">Visualize categorias e tipos de serviço disponíveis no sistema.</p>
                    <div class="mt-8 space-y-4">
                        <?php foreach ($categories as $category): ?>
                            <span class="inline-flex rounded-full border border-amber-500/20 bg-amber-500/10 px-4 py-2 text-sm text-amber-200"><?= htmlspecialchars($category['name']) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-3">
                        <?php foreach ($serviceTypes as $type): ?>
                            <span class="inline-flex rounded-full border border-violet-500/20 bg-violet-500/10 px-4 py-2 text-sm text-violet-200"><?= htmlspecialchars($type['name']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <aside class="space-y-8">
            <div class="rounded-[2rem] border border-purple-800 bg-purple-900/80 p-10 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
                <h3 class="text-2xl font-bold text-white">Status atual</h3>
                <p class="mt-2 text-slate-400">Acompanhe a evolução dos chamados pela interface moderna e responsiva.</p>
                <div class="mt-8 grid gap-4">
                    <div class="rounded-3xl border border-purple-800 bg-purple-950/80 p-5">
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Total</p>
                        <p class="mt-3 text-3xl font-bold text-white"><?= count($tickets) ?></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-3xl border border-purple-800 bg-purple-950/80 p-5">
                            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Abertos</p>
                            <p class="mt-3 text-2xl font-bold text-amber-400"><?= Ticket::countByStatus('Aberto', $currentUser && $currentUser['role'] === 'user' ? $currentUser['id'] : null) ?></p>
                        </div>
                        <div class="rounded-3xl border border-purple-800 bg-purple-950/80 p-5">
                            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Em andamento</p>
                            <p class="mt-3 text-2xl font-bold text-violet-300"><?= Ticket::countByStatus('Em andamento', $currentUser && $currentUser['role'] === 'user' ? $currentUser['id'] : null) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
                <h3 class="text-2xl font-bold text-white">Serviços disponíveis</h3>
                <ul class="mt-6 space-y-3 text-slate-300">
                    <?php foreach ($serviceTypes as $type): ?>
                        <li class="rounded-3xl border border-purple-800 bg-purple-950/80 px-4 py-3"><?= htmlspecialchars($type['name']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </div>

    <?php if ($currentUser): ?>
    <?php $showArchived = isset($_GET['archived']) && $_GET['archived'] === '1'; ?>
    <div class="mt-16 overflow-x-auto rounded-[2rem] border border-purple-800 bg-purple-900/80 p-8 shadow-[0_0_80px_rgba(15,23,42,0.45)]">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-white">Lista de chamados</h3>
            <div>
                <?php if ($showArchived): ?>
                    <a href="index.php?page=tickets" class="rounded-full border border-purple-700 bg-slate-900 px-4 py-2 text-sm text-slate-200 hover:border-amber-400 transition">Voltar</a>
                <?php else: ?>
                    <a href="index.php?page=tickets&archived=1" class="rounded-full border border-purple-700 bg-slate-900 px-4 py-2 text-sm text-slate-200 hover:border-amber-400 transition">Mostrar arquivados</a>
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
                        <th class="px-6 py-4">Proprietário</th>
                        <th class="px-6 py-4">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php if (empty($tickets)): ?>
                        <tr><td class="px-6 py-8 text-center text-slate-500" colspan="6">Nenhum chamado registrado ainda.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr class="hover:bg-purple-900/90 transition">
                            <td class="px-6 py-5 font-semibold text-white"><?= htmlspecialchars($ticket['title']) ?></td>
                            <td class="px-6 py-5"><?= htmlspecialchars($ticket['category_name']) ?></td>
                            <td class="px-6 py-5"><?= htmlspecialchars($ticket['service_type_name']) ?></td>
                            <td class="px-6 py-5 text-amber-400"><?= htmlspecialchars($ticket['status']) ?></td>
                            <td class="px-6 py-5"><?= htmlspecialchars($ticket['created_at']) ?></td>
                            <td class="px-6 py-5"><?= htmlspecialchars($ticket['owner_name']) ?></td>
                            <?php
                                $detailsText = $ticket['details'];
                                $responseText = '';
                                $markerPos = strpos($detailsText, "\n\nResposta do admin:");
                                if ($markerPos === false) {
                                    $markerPos = strpos($detailsText, "\n\nResposta de ");
                                }
                                if ($markerPos !== false) {
                                    $responsePart = substr($detailsText, $markerPos + 2);
                                    $detailsText = substr($detailsText, 0, $markerPos);
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
                                <button type="button" data-ticket='<?= $ticketData ?>' class="open-ticket-modal w-full rounded-3xl border border-purple-800 bg-purple-950/80 px-4 py-3 text-left text-slate-200 transition hover:bg-purple-900/90">Ver</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</section>

<!-- User ticket modal (read-only) -->
<?php if ($currentUser): ?>
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
    </div>
</div>
<?php endif; ?>