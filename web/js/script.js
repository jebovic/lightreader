function navigate( id ) {
   if ( link = document.getElementById( id ) )
   {
        window.location.href = link.href;
   }
}

document.onkeydown = function(evt) {
    evt = evt || window.event;
    switch (evt.keyCode) {
        case 37:
            navigate('prev');
            break;
        case 39:
            navigate('next');
            break;
    }
};