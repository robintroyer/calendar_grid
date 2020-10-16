function showNav()
{
    var nav = document.getElementById('nav');
    if (
        nav.className.includes('navbar')
        && !nav.className.includes('responsive')
    ) {
        nav.className += ' responsive';
    } else {
        nav.className = nav.className.replace('responsive', '');
        nav.className = nav.className.trim();
    }
}
function showForm()
{
    var div = document.getElementById('form_dropdown_div');
    var form = document.getElementById('new_entry_form');
    var upload = document.getElementById('ics_form');
    if (form.className.includes('disabled')) {
        form.className = form.className.replace('disabled', 'active');
    } else if (form.className.includes('active')) {
        form.className = form.className.replace('active', 'disabled');
    }
    if (upload.className.includes('disabled')) {
        upload.className = upload.className.replace('disabled', 'active');
    } else if (upload.className.includes('active')) {
        upload.className = upload.className.replace('active', 'disabled');
    }
}
function addClass()
{
    var cells = document.getElementsByTagName('h6');
    console.log(cells);
}