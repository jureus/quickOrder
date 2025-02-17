document.addEventListener('DOMContentLoaded', function () {
    const fastOrderBtn = document.querySelector('.fast-order-button');
    const modal = document.getElementById('fastOrderModal');
    const closeBtn = document.querySelector('.fastorder-modal-close');
    const form = document.getElementById('fastOrderForm');

    if (!fastOrderBtn || !modal) {
        return;
    }

    fastOrderBtn.addEventListener('click', function (event) {
        event.preventDefault();
        modal.style.display = 'block';
        modal.classList.add('fastorder-fade-in');
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            modal.style.display = 'none';
            modal.classList.remove('fastorder-fade-in');
        });
    }

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            modal.classList.remove('fastorder-fade-in');
        }
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        if (!validateForm(form)) {
            return;
        }

        const formData = new FormData(form);
        formData.append('action', 'addOrder');
        formData.append('sessid', BX.bitrix_sessid());
        formData.append('productId', window.location.href);

        fetch("/bitrix/components/mycompany/fast.order/ajax.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('fastorder-modal-content').innerHTML = '<p>' + data.message + '</p>';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error(error);
            alert('Произошла ошибка при отправке запроса.');
        });
    });

    function validateForm(form) {
        let isValid = true;
        const name = form.querySelector('[name="name"]');
        const phone = form.querySelector('[name="phone"]');
        const email = form.querySelector('[name="email"]');

        const errorElements = form.querySelectorAll('.fastorder-error');
        errorElements.forEach(el => el.remove());

        if (name.value.trim().length < 2) {
            displayError(name, 'Имя должно содержать минимум 2 символа');
            isValid = false;
        }

        if (!/^\+?\d[\d\(\)\-\s]*$/.test(phone.value)) {
            displayError(phone, 'Введите корректный номер телефона');
            isValid = false;
        }

        if (email.value.trim() !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            displayError(email, 'Введите корректный email');
            isValid = false;
        }

        return isValid;
    }

    function displayError(input, message) {
        const errorSpan = document.createElement('span');
        errorSpan.className = 'fastorder-error';
        errorSpan.textContent = message;
        errorSpan.style.color = 'red';
        errorSpan.style.display = 'block';
        input.parentNode.insertBefore(errorSpan, input.nextSibling);
    }
});