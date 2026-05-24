document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.menu-link');
    links.forEach((link) => {
        const href = link.getAttribute('href');
        if (href && href.startsWith('#')) {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }
    });

    const toggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    if (toggle && mobileMenu) {
        toggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    document.querySelectorAll('.logo-link').forEach((link) => {
        const beeWrapper = link.querySelector('.bee-wrapper');
        if (!beeWrapper) {
            return;
        }

        const restartBeeAnimation = (duration) => {
            beeWrapper.style.animation = 'none';
            // Force reflow to restart animation
            void beeWrapper.offsetWidth;
            beeWrapper.style.animation = `bee-flight ${duration}ms ease-in-out 1 forwards`;
        };

        restartBeeAnimation(2000);

        link.addEventListener('mouseenter', () => {
            restartBeeAnimation(2000);
        });

        link.addEventListener('mousemove', (event) => {
            const rect = link.getBoundingClientRect();
            const offsetX = (event.clientX - rect.left - rect.width / 2) * 0.12;
            const offsetY = (event.clientY - rect.top - rect.height / 2) * 0.1;
            beeWrapper.style.setProperty('--bee-hover-x', `${offsetX}px`);
            beeWrapper.style.setProperty('--bee-hover-y', `${offsetY}px`);
        });

        link.addEventListener('mouseleave', () => {
            beeWrapper.style.setProperty('--bee-hover-x', '0px');
            beeWrapper.style.setProperty('--bee-hover-y', '0px');
        });
    });

    const registerSection = document.getElementById('registerSection');
    const showRegisterForm = document.getElementById('showRegisterForm');
    const hideRegisterForm = document.getElementById('hideRegisterForm');

    const forgotPasswordSection = document.getElementById('forgotPasswordSection');
    const showForgotPasswordForm = document.getElementById('showForgotPasswordForm');
    const hideForgotPasswordForm = document.getElementById('hideForgotPasswordForm');

    if (showRegisterForm && registerSection) {
        showRegisterForm.addEventListener('click', () => {
            registerSection.classList.toggle('hidden');
            registerSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    if (hideRegisterForm && registerSection) {
        hideRegisterForm.addEventListener('click', () => {
            registerSection.classList.add('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    if (showForgotPasswordForm && forgotPasswordSection) {
        showForgotPasswordForm.addEventListener('click', () => {
            forgotPasswordSection.classList.toggle('hidden');
            forgotPasswordSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    if (hideForgotPasswordForm && forgotPasswordSection) {
        hideForgotPasswordForm.addEventListener('click', () => {
            forgotPasswordSection.classList.add('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    const adminRoleForms = document.querySelectorAll('form input[name="action"][value="update_user_role"]');
    const adminActionMessage = document.getElementById('adminActionMessage');

    const showAdminMessage = (message, isError = false) => {
        if (!adminActionMessage) {
            return;
        }
        adminActionMessage.textContent = message;
        adminActionMessage.classList.toggle('border-rose-500/20', isError);
        adminActionMessage.classList.toggle('border-emerald-500/20', !isError);
        adminActionMessage.classList.toggle('bg-rose-500/10', isError);
        adminActionMessage.classList.toggle('bg-emerald-500/10', !isError);
        adminActionMessage.classList.toggle('text-rose-200', isError);
        adminActionMessage.classList.toggle('text-emerald-200', !isError);
        adminActionMessage.classList.remove('hidden');
        setTimeout(() => {
            if (adminActionMessage) {
                adminActionMessage.classList.add('hidden');
            }
        }, 4000);
    };

    adminRoleForms.forEach((input) => {
        const form = input.closest('form');
        if (!form) {
            return;
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(form);
            const userId = formData.get('user_id');
            const role = formData.get('role');
            const row = form.closest('tr');
            const roleCell = row ? row.querySelector('td:nth-child(3)') : null;

            try {
                const response = await fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                const result = await response.json();
                if (result.success) {
                    if (roleCell && typeof role === 'string') {
                        roleCell.textContent = role;
                    }
                    showAdminMessage(result.message || 'Cargo atualizado com sucesso.');
                } else {
                    showAdminMessage(result.message || 'Falha ao atualizar cargo.', true);
                }
            } catch (error) {
                showAdminMessage('Erro ao enviar a solicitação. Tente novamente.', true);
            }
        });
    });

    document.querySelectorAll('[data-toggle-password-row]').forEach((button) => {
        button.addEventListener('click', () => {
            const userId = button.dataset.togglePasswordRow;
            if (!userId) {
                return;
            }
            const passwordRow = document.getElementById(`passwordRow-${userId}`);
            if (!passwordRow) {
                return;
            }
            passwordRow.classList.toggle('hidden');
            if (!passwordRow.classList.contains('hidden')) {
                passwordRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });

    const ticketModal = document.getElementById('ticketModal');
    const closeTicketModal = document.getElementById('closeTicketModal');
    const openTicketButtons = document.querySelectorAll('.open-ticket-modal');
    const modalTitle = document.getElementById('modalTicketTitle');
    const modalStatus = document.getElementById('modalTicketStatus');
    const modalCategory = document.getElementById('modalTicketCategory');
    const modalType = document.getElementById('modalTicketType');
    const modalCreatedAt = document.getElementById('modalTicketCreatedAt');
    const modalAssignedTo = document.getElementById('modalTicketAssignedTo');
    const modalDetails = document.getElementById('modalTicketDetails');
    const modalResponseWrapper = document.getElementById('modalTicketResponseWrapper');
    const modalResponse = document.getElementById('modalTicketResponse');
    const modalTicketId = document.getElementById('modalTicketId');
    const modalStatusSelect = document.getElementById('modalTicketStatusSelect');
    const modalAssignedToInput = document.getElementById('modalTicketAssignedToInput');
    const modalCancelTicket = document.getElementById('modalCancelTicket');

    const closeModal = () => {
        if (ticketModal) {
            ticketModal.classList.add('hidden');
        }
    };

    const openModal = (ticket) => {
        if (!ticketModal) {
            return;
        }

        if (modalTitle) modalTitle.textContent = ticket.title;
        if (modalStatus) modalStatus.textContent = ticket.status;
        if (modalCategory) modalCategory.textContent = ticket.category;
        if (modalType) modalType.textContent = ticket.service_type;
        if (modalCreatedAt) modalCreatedAt.textContent = ticket.created_at;
        if (modalAssignedTo) modalAssignedTo.textContent = ticket.assigned_to;
        if (modalDetails) modalDetails.textContent = ticket.details;
        if (modalTicketId) modalTicketId.value = ticket.id;
        if (modalStatusSelect) modalStatusSelect.value = ticket.status;
        if (modalAssignedToInput) modalAssignedToInput.value = ticket.assigned_to;

        if (ticket.response) {
            if (modalResponseWrapper) modalResponseWrapper.classList.remove('hidden');
            if (modalResponse) modalResponse.textContent = ticket.response;
        } else {
            if (modalResponseWrapper) modalResponseWrapper.classList.add('hidden');
            if (modalResponse) modalResponse.textContent = '';
        }

        ticketModal.classList.remove('hidden');
    };

    openTicketButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const ticketJson = button.dataset.ticket;
            if (!ticketJson) {
                return;
            }
            const ticket = JSON.parse(ticketJson);
            openModal(ticket);
        });
    });

    if (closeTicketModal) {
        closeTicketModal.addEventListener('click', closeModal);
    }

    if (ticketModal) {
        ticketModal.addEventListener('click', (event) => {
            if (event.target === ticketModal) {
                closeModal();
            }
        });
    }

    if (modalCancelTicket) {
        modalCancelTicket.addEventListener('click', () => {
            const cancelForm = document.createElement('form');
            cancelForm.method = 'post';
            cancelForm.action = 'index.php';
            cancelForm.style.display = 'none';

            const actionInput = document.createElement('input');
            actionInput.name = 'action';
            actionInput.value = 'cancel_ticket';
            cancelForm.appendChild(actionInput);

            const ticketIdInput = document.createElement('input');
            ticketIdInput.name = 'ticket_id';
            ticketIdInput.value = modalTicketId.value;
            cancelForm.appendChild(ticketIdInput);

            document.body.appendChild(cancelForm);
            cancelForm.submit();
        });
    }
});
