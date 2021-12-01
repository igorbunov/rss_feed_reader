const darkSwitch = document.getElementById('darkSwitch');

darkSwitch.checked = getTheme() === 'dark';
darkSwitch.onchange = () => {
	setTheme(darkSwitch.checked ? 'dark' : 'light');
};

themeChangeHandlers.push(theme => darkSwitch.checked = theme === 'dark');
