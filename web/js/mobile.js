function toggleVisibility(e) {
    e.preventDefault();
    l = document.getElementById('menuList');
    a = document.getElementById('menuAction');
    if(l.style.display == 'block') {
        l.style.display        = 'none';
        p.className            = 'collapsed';
        a.firstChild.nodeValue = '+';
    }
    else {
        l.style.display        = 'block';
        p.className            = 'expanded';
        a.firstChild.nodeValue = '-';
    }
}

t = document.getElementById('menuToggle');
var p = t.parentNode;
t.addEventListener(
    'click', toggleVisibility, false
);