/**Data TAble Init*/
$(document).ready(function() {
    var table = $('#example').DataTable( {
        //"scrollY": "700px",
        "paging": false,
        "autoWidth": true,
        dom: 'Bfrtip',
        language: {
            searchPlaceholder: "Search any type of records..."
        },
        buttons: [
            {
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

		$('#btn-show-all-children').on('click', function(){
        // Expand row details
        table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
    });
    $('#example').colResizable({liveDrag:true});

    // Handle click on "Collapse All" button
    $('#btn-hide-all-children').on('click', function(){
        // Collapse row details
        table.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
    });


    $('#example tbody tr').each(function(i, obj) {
        //Get the value of sequences and kmers
        var sequence = $(this).find(".genome-cell").text();
        var kmers = $(this).find(".kmers").text();
        var placementOfRec = 0;
        sequence = sequence.replace(/\s/g, '');

        var table = $( "#genome" + i );
        for (var k = 0; k < occurrences(sequence, kmers); k++){
            var indexOf = sequence.indexOf(kmers, k+indexOf);

            if (indexOf === -1){
                indexOf = indexOf + sequence.length;
            }
            placementOfRec = indexOf * 100 / sequence.length;

            var rect = document.createElement('rect');
            $(rect).attr("class", "rectangle").attr("height", "18").attr("width", '0.2%').attr("x", placementOfRec + '%');
            table.append(rect);
        }
        table.html(function(){return this.innerHTML});
    });

    function occurrences(string, subString, allowOverlapping) {

        string += "";
        subString += "";
        if (subString.length <= 0) return (string.length + 1);

        var n = 0,
            pos = 0,
            step = allowOverlapping ? 1 : subString.length;

        while (true) {
            pos = string.indexOf(subString, pos);
            if (pos >= 0) {
                ++n;
                pos += step;
            } else break;
        }
        return n;
    }
});
