const request = async (url, body = null) => {
    const options = {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }

    if (body !== null) {
        options.body = JSON.stringify(body);
        options.method = "POST";
    }

    const response = await fetch(url, options);
    if (response.ok) {
        return await response.json();
    } else {
        throw new Error(String(response.status));
    }
}

function handleModal(e, targetString) {
    const modalDom = document.querySelector(targetString);

    function outSideClick(e) {
        if (!modalDom.querySelector('.modal__body').contains(e.target)) {
            modalDom.classList.remove('show');
        }
    }

    if (!modalDom.classList.contains('show')) {
        modalDom.classList.add('show');

        document.addEventListener('click', outSideClick, {capture: true, once: true});
    } else {
        modalDom.classList.remove('show');
        document.removeEventListener('click', outSideClick);
    }
}

const modalButtons = document.querySelectorAll('[data-toogle="modal"]');
modalButtons.forEach(el => {
    const targetString = el.getAttribute('data-target');
    el.onclick = e => handleModal(e, targetString);
}) ;



let currentOrderId;

const modalBidButtons = document.querySelectorAll('[data-toogle="modalBid"]');
modalBidButtons.forEach(el => {
    const targetString = el.getAttribute('data-target');
    const orderId = el.getAttribute('data-order-id');

    el.onclick = e => {
        currentOrderId = orderId;
        handleModal(e, targetString);
    }
});


const sendBidForm = document.getElementById('sendBidForm');
sendBidForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    if (currentOrderId) {
        const formData = new FormData(e.target);

        const object = {};
        for (let [name, value] of formData.entries()) {
            object[name] = value;
        }
        const { data } = await request(`/api/sendBid/${currentOrderId}`, object);
        if (data) {
            document.getElementById('sendBidModal').classList.remove('show');
            this.reset();
        }
    }
});
