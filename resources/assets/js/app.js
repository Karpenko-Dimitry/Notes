(function() {
    'use strict';

    let filesInput = document.querySelector('.form-control-file');
    let ul = document.querySelector('.files-list')
    let perPageSelect = document.querySelector('.per-page-select');
    let sharedNotesSelect = document.querySelector('.range-shared');
    let perPageForm = document.querySelector('.per-page-form');
    let categoryForm = document.querySelector('.category-range');
    let categoryCheckBox = document.querySelectorAll('.category-checkbox');
    let gridNotesCheckBox = document.querySelector('.grid-notes');
    let languageCard = $('.language-card');
    let languageBody = document.querySelectorAll('.language-body');
    let avatarInput = document.getElementById('avatar_image');
    let avatarForm = document.querySelector('.avatar-store-form');


    $(function() {
        $('.chosen-select').magicSuggest({
            allowDuplicates: false,
        });
    });

    languageCard.each(() => {
        for (let i = 0; i < languageCard.length; i++) {
            $(languageCard[i]).bind('click', showCard);
        }

        for (let i = 0; i < languageBody.length; i++) {
            languageBody[i].classList.add('d-none');
        }

        languageCard[0].classList.add('active');
        languageBody[0].classList.remove('d-none');

        function showCard(e) {
            e.preventDefault();

            for (let i = 0; i < languageCard.length; i++) {
                languageCard[i].classList.remove('active');
            }

            for (let i = 0; i < languageBody.length; i++) {
                languageBody[i].classList.add('d-none');
            }
            let locale = this.getAttribute('id');

            this.classList.add('active');
            $(`#${locale}_body`).removeClass('d-none')

        }
    });

    if (avatarInput) {
        avatarInput.addEventListener('change', submitStoreAvatarForm);
    }

    if (filesInput) {
        filesInput.addEventListener('change', getValue, false);

        function getValue(){
            for (let i = 0; i < this.files.length; i++) {
                let li = document.createElement('li');
                li.innerHTML = this.files[i].name;
                ul.appendChild(li);
            }
        }
    }

    if (categoryCheckBox) {
        for (let i = 0; i < categoryCheckBox.length; i++) {
            categoryCheckBox[i].addEventListener('change', submitCategoryForm)
        }
    }

    if (perPageSelect) {
        perPageSelect.addEventListener('change', submitForm, false);
    }

    if (gridNotesCheckBox) {
        gridNotesCheckBox.addEventListener('click', submitForm, false);
    }

    if (sharedNotesSelect) {
        sharedNotesSelect.addEventListener('change', submitForm, false);
    }

    function submitForm() {
        perPageForm.submit();
    }

    function submitCategoryForm(e) {
        let data = [...(new URLSearchParams(window.location.search)).entries()].reduce(
            (obj, item) => ({ ...obj, ...{ [item[0]]: item[1] } }), {category: e.target.value}
        );

        categoryForm.submit();

    }

    function submitStoreAvatarForm(e) {
        e.preventDefault();
        avatarForm.submit();
    }
})()
