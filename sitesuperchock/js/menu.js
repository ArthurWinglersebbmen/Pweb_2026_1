function showMenu() {
    const menulateral = document.getElementById("menu-lateral")
    const iconmenu = document.getElementById("img-menu")

    menulateral.classList.toggle('ativa');

    if(menulateral.classList.contains('ativa')){
        iconmenu.src = "img/icon-close-menu.png"
    } else {
        iconmenu.src = "img/icon-hamburger-menu.png"
    }
}
    
