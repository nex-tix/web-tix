$(document).ready(function() {
    if (!$.fn.DataTable) return;
    
    $("#laporanAuditTable").DataTable({
        pageLength: 6,
        lengthChange: false,
        searching: true,
        ordering: false,
        info: true,
        paging: true,
        language: {
            search: "Cari:",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            paginate: {
                previous: "<i class='bi bi-chevron-left'></i>",
                next: "<i class='bi bi-chevron-right'></i>"
            }
        },
        columnDefs: [
            { orderable: false, targets: [5] }
        ]
    });
});
