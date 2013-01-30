function toggleVisibility(e) {
    e.preventDefault();
    l = document.getElementById('menuList');
    a = document.getElementById('menuAction');
    if(l.style.display == 'block') {
        l.style.display        = 'none';
        t.className            = 'collapsed';
        a.firstChild.nodeValue = '+';
    }
    else {
        l.style.display        = 'block';
        t.className            = 'expanded';
        a.firstChild.nodeValue = '-';
    }
}

var t = document.getElementById('menuToggle');
t.addEventListener(
    'click', toggleVisibility, false
);