import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


const type = document.getElementById('type');

if(type){
    type.addEventListener('change', () => {
        document.querySelectorAll('.box').forEach(b => b.style.display = 'none');

        if(type.value){
            document.getElementById(type.value).style.display = 'block';
        }
    });
}
