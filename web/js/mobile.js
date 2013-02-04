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
function showNextItems( url, pageNumber, addContentIn ) {
    o = document.getElementById('loading');
    o.style.display = 'block';
    r = new XMLHttpRequest();
    d = document.getElementById(addContentIn);
    r.open("GET", url + "/" + pageNumber + "/1", true);
    r.onreadystatechange = function () {
        if (r.readyState != 4 || r.status != 200) return;
        d.innerHTML = d.innerHTML + r.responseText;
        o.style.display = 'none';
        processing = 0;
    };
    r.send();
}
function isBottom(){
    if (processing > 0)
        { return false; }

    var totalHeight, currentScroll, visibleHeight;

    if (document.documentElement.scrollTop)
        { currentScroll = document.documentElement.scrollTop; }
    else
        { currentScroll = document.body.scrollTop; }

    totalHeight   = document.body.offsetHeight;
    visibleHeight = document.documentElement.clientHeight;

    if ( totalHeight <= currentScroll + visibleHeight )
    {
        processing = 1;
        showNextItems( baseURL, pageToLoad, "content");
        pageToLoad = pageToLoad + 1;
    }
}

t = document.getElementById('menuToggle');
var p = t.parentNode;
t.addEventListener(
    'click', toggleVisibility, false
);