function showComponent(name)
{
    const components = document.querySelectorAll('.component');
    const buttons = document.querySelectorAll('.componentSelect__btn');

    components.forEach(c => c.classList.add('greenhouseComponentHidden'));

    document.getElementById(name).classList.remove('greenhouseComponentHidden');

    buttons.forEach(btn => btn.classList.remove('selectedComponentName'));
    document.getElementById(name + '-btn').classList.add('selectedComponentName');
}

showComponent('overview');