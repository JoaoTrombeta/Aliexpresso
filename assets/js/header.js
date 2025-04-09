document.addEventListener('DOMContentLoaded', () => {
    const NavM = document.getElementById('main-nav')
    const NavL = document.getElementById('nav-links')
    const ODoc = document.querySelector('body')

    NavM.addEventListener('click', e => {
        if(NavM.style.height != ""){
            NavM.style.height = ""
        }else{
            NavM.style.height = ((ODoc.offsetHeight / 100) * 7.7) + NavL.offsetHeight + "px"
            console.log(NavL.offsetHeight + "px")
            console.log((ODoc.offsetHeight / 100) * 7.8 + "px")
        }
    })
})