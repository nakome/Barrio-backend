var btnMenu = document.querySelector('.menu'),
    sidebar = document.querySelector('#menu'),
    page = document.querySelector('#page'),
    main = document.querySelector('#wrapper'),
    dropdown = document.querySelectorAll('.has-dropdown > a');


var tooggleAccordion = function() {
    var elements = document.querySelectorAll('.accordion-title');
    Array.from(elements).forEach(function(item) {
        item.querySelector('a').addEventListener('click', function() {
            if (item.querySelector('a').classList.contains('active')) {
                item.querySelector('a').classList.remove('active');
                item.nextElementSibling.classList.remove('show');
                item.nextElementSibling.classList.add('hide');
            } else {
                item.querySelector('a').classList.add('active');
                item.nextElementSibling.classList.add('show');
                item.nextElementSibling.classList.remove('hide');
            }
        });
    });
}
tooggleAccordion();


var toggleDropdown = function(el) {
    el.addEventListener('click', function(e) {
        e.preventDefault();
        el.classList.toggle('is-active');
        if (el.nextElementSibling)
            el.nextElementSibling.classList.toggle('show_menu');
    });
}

var message = function(title, msg) {
    var div = document.createElement('div');
    div.className = 'notification';
    div.innerHTML = `<div class="p-4 bg-dark text-warning b-dark"><b>${title}</b> ${msg} </div>`;
    document.body.appendChild(div);
    var w = setTimeout(function() {
        div.remove();
        clearTimeout(w);
    }, 3000);
}

Array.from(dropdown).forEach(function(item) {
    if (!item.classList.contains('link')) {
        toggleDropdown(item);
    }
});


if (btnMenu) btnMenu.addEventListener('click', e => {
    e.preventDefault();
    sidebar.classList.toggle('is-opened');
    main.classList.toggle('menu-is-open');
});

page.classList.add('loaded');