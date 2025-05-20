function toggleMenu() {
  const menu = document.getElementById('dropdownMenu');
  const icon = document.getElementById('menuIcon');
  const isOpen = menu.style.display === 'block';

  menu.style.display = isOpen ? 'none' : 'block';
  icon.classList.toggle('active');
}

document.addEventListener('click', function (event) {
  const menu = document.getElementById('dropdownMenu');
  const icon = document.getElementById('menuIcon');

  if (!menu.contains(event.target) && !icon.contains(event.target)) {
    menu.style.display = 'none';
    icon.classList.remove('active');
  }
});

document.addEventListener('DOMContentLoaded', () => {
    const NavM = document.getElementById('main-nav');
    const NavL = document.getElementById('nav-links');
    const ODoc = document.querySelector('body');

    if (NavM && NavL) {
        NavM.addEventListener('click', e => {
            if (NavM.style.height !== "") {
                NavM.style.height = "";
            } else {
                NavM.style.height = ((ODoc.offsetHeight / 100) * 7.7) + NavL.offsetHeight + "px";
                console.log(NavL.offsetHeight + "px");
                console.log((ODoc.offsetHeight / 100) * 7.8 + "px");
            }
        });
    }
});