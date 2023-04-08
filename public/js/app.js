function loadJS(b, c) {
    let a = document.createElement("script");
    a.setAttribute("src", b),
        a.setAttribute("type", "text/javascript"),
        a.setAttribute("async", c),
        document.head.appendChild(a)
}

let datatableSearch = document.querySelector("#datatable-search");
if (datatableSearch) {
    const ordersDatatable = new simpleDatatables.DataTable(datatableSearch, {
        searchable: true,
        fixedHeight: false
    });
}

if (document.querySelector('.datepicker')) {
    flatpickr('.datepicker', {
        maxDate: 'today'
    });
}

if (document.getElementById('quote_filter_form_symbol')) {
    let element = document.getElementById('quote_filter_form_symbol');
    const choices = new Choices(element, {});
}

if (document.getElementById('quote_filter_form_submit')) {
    document.getElementById('quote_filter_form_submit').addEventListener('click', e => {
        let currentDate = moment();
        currentDate = currentDate.format('YYYY-MM-DD');
        let startDate = document.getElementById('quote_filter_form_start_date').value;
        let endDate = document.getElementById('quote_filter_form_end_date').value;

        if (startDate === '' || endDate === '') {
            e.preventDefault();
            alert('Date fields are required.');
            return;
        }

        if (startDate > endDate || startDate > currentDate || endDate > currentDate) {
            e.preventDefault();
            if (startDate > endDate) {
                alert('`Start Date` should be less than `End Date`')
            }
            if (startDate > currentDate) {
                alert('`Start Date` should be less than `Current Date`')
            }
            if (endDate > currentDate) {
                alert('`End Date` should be less than `Current Date`')
            }
        }
    });
}
