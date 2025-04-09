document.addEventListener('DOMContentLoaded', () => {
    const NavM = document.getElementById('main-nav')
    const NavL = document.getElementById('nav-links')
    const ODoc = document.querySelector('body')

    NavM.addEventListener('click', e => {
        NavM.style.height = ((document.clientHeight / 100) * 7.5) + NavL.clientHeight + "vh"
        console.log(((ODoc.clientHeight / 100) * 7.5) + NavL.clientHeight + "px")
        NavM.style.overflow = "visible"
        NavL.style.bottom = "0"
    })
})