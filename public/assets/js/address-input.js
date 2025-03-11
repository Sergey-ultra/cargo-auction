async function getSuggest(name) {
    const response = await fetch(`/api/city/suggest?name=${name}` , {
        "Accept": "application/json",
        "Content-Type": "application/json",
    });
    return await response.json();
}

const addDocumentListener = (inputDomElement, suggestListDom) => {
    const handleClick = ev => suggestOutsideClick(ev, inputDomElement, suggestListDom);
    document.removeEventListener('click', handleClick, { capture: true, once: true });
    document.addEventListener('click', handleClick , { capture: true, once: true });
}

const suggestOutsideClick = (event, inputDomElement, suggestListDom) => {
    event.stopPropagation();
    console.log('fs');
    if (!suggestListDom.contains(event.target)) {

    } else {

        inputDomElement.value = event.target.innerText;
        const addressId = document.querySelector(`[name=${inputDomElement.name}Id]`);

        if (!addressId) {
            inputDomElement.insertAdjacentHTML('afterend', `<input type="hidden" name="${inputDomElement.name}Id" value="${event.target.value}">`);
        } else {
            addressId.value = event.target.value;
        }
    }

    //document.removeEventListener('click', suggestOutsideClick, { capture: true });
    suggestListDom.style.display = 'none';
}

const handleAddressInput = async (e, addressInput, addressSuggest) => {
    if (e.target.value) {
        if (e.target.value.length > 1) {
            const {data} = await getSuggest(e.target.value);

            const ul = addressSuggest.querySelector('.suggest-list');

            ul.innerHTML = '';
            if (data && data.length) {

                addDocumentListener(addressInput, addressSuggest);
                let inner = '';
                data.forEach(el => {
                    inner += `<li value=${el.id}>${el.name}</li>`
                })

                ul.insertAdjacentHTML('afterbegin', inner);
                addressSuggest.style.display = 'flex';

            } else {
                addressSuggest.style.display = 'none';
            }
        } else {
            addressSuggest.style.display = 'none';
        }
    } else {
        addressSuggest.style.display = 'none';
    }
}
