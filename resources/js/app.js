import './bootstrap';
import '~resources/scss/app.scss';
import * as bootstrap from 'bootstrap';
import.meta.glob([
    '../img/**'
]);
import DataTable from 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';

$(document).ready(function() {
    $('#zanetti-table, .zanetti-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/it-IT.json',
        }
    });
});

$(document).ready(function() {
    $('#zanetti-table-download').DataTable({
        dom: 'Bfrtip',
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/it-IT.json',
        },
        buttons: [
            {
              extend: 'csv',
              text: 'DOWNLOAD CSV'
            },
            {
              extend: 'excel',
              text: 'DOWNLOAD XLSX'
            }
          ]
    });
});