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

    const registerSection = document.getElementById('registerSection');
    const showRegisterForm = document.getElementById('showRegisterForm');
    const hideRegisterForm = document.getElementById('hideRegisterForm');

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
