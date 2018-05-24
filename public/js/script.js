/**Data TAble Init*/
$(document).ready(function() {
    var table = $('#example').DataTable({
        //"scrollY": "700px",
        "paging": false,
        "autoWidth": true,
        dom: 'Bfrtip',
        language: {
            searchPlaceholder: "Search any type of records..."
        },
        "order": [
            [2, "desc"]
        ],

        "columnDefs": [
            { "visible": false, "targets": [3] },
            { "visible": false, "targets": [6] },
            { "visible": false, "targets": [7] },
            { "visible": false, "targets": [8] },
            { "visible": false, "targets": [9] },
            { "visible": false, "targets": [10] },
            { "visible": false, "targets": [11] },
            { "visible": false, "targets": [12] }
        ],
        buttons: [{
                extend: 'collection',
                text: 'Export <i class="fa fa-download"></i>',
                className: 'btn btn-default export-button',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ]
            },
            {
                extend: 'collection',
                text: 'Show/Hide Fields <i class="fa fa-chevron-down"></i>',
                className: 'btn btn-default show-hide-button',
                buttons: [
                    'columnsToggle'
                ]
            }
        ],
        'responsive': true
    });

    $('#example tbody tr').each(function(i, obj) {
        //Get the value of sequences and kmers
        var lenght = $(this).find(".rangeLncRNA").text();
        var motif_lists = $(this).find(".motifList").text();
        var id = $(this).find(".transcript_id").text();


        draw_rect(lenght, motif_lists, "#genome" + id);
    });
    $(document).on('click', '#example tbody tr', function(e) {
        var id = "#bigGenome" + $(this).find(".transcript_id").text();
        var graph = $(this).find('.genome-cell').html();
        $(this).closest('tr').next().find(id).append(graph);
    });

    function draw_rect(lenght, motif_lists, id){
        if (motif_lists != "") {
            var motifs = motif_lists.split(",");
            var placementOfRec = 0;
            var table = $(id);

            $.each(motifs, function(index, value) {
                var indexOf = value.split(":");
                placementOfRec = parseInt(indexOf[0]) * 100 / parseInt(lenght);
                var rect = document.createElement('rect');
                $(rect).attr("class", "rectangle").attr("height", "18").attr("width", '0.4%').attr("x", placementOfRec + '%');

                table.append(rect);
            });
            table.html(function() { return this.innerHTML });
        }
    };

    //Material Button
    $('.button').mousedown(function(e) {
        var target = e.target;
        var rect = target.getBoundingClientRect();
        var ripple = target.querySelector('.ripple');
        $(ripple).remove();
        ripple = document.createElement('span');
        ripple.className = 'ripple';
        ripple.style.height = ripple.style.width = Math.max(rect.width, rect.height) + 'px';
        target.appendChild(ripple);
        var top = e.pageY - rect.top - ripple.offsetHeight / 2 - document.body.scrollTop;
        var left = e.pageX - rect.left - ripple.offsetWidth / 2 - document.body.scrollLeft;
        ripple.style.top = top + 'px';
        ripple.style.left = left + 'px';
        return false;
    });

});
