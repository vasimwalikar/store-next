jQuery(function ($) {
    'use strict';

    $('#hotbuystable').dataTable({
        "bPaginate": false,
            "bFilter": false,
            "bInfo": false,
        'aoColumnDefs': [{
            'bSortable': false,
            'aTargets': [1,2,3,4], /* 1st colomn, starting from the right */
        }]
            //"bSort": false
    }).rowReordering();
 });

//$("#hotbuystable").tablesorter( {
//    headers:{
//        2:{sorter:false},
//        3:{sorter:false},
//        4:{sorter:false}
//    }
//});

$(document).ready(function() {
    $('#make').typeahead({
        name: 'make',
        remote: 'api.php?make=%QUERY',

    });

    $('th').removeClass('sorting_asc');
});
$('#update_priority').bind('click',function(){
    var j=0;
    $('#hotbuystable tbody tr').each(function(i,val){
        j++;
        var formData = new FormData();
        formData.append('priority_update','');
        formData.append('id',$(this).attr('id'));
        formData.append('priority',j);
        $.ajax({
            url: "api.php",
            data:formData,
            type:'POST',
            mimeType:"multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            async:false,
            success:function(data){
                //window.location.reload();

            }

        })
    })
    $.toast({
        heading: 'Updated',
        text: 'Priority Updated',
        position: 'bottom-right',
        icon: 'success'
    });
});
function update_priority(){
    var j=0;
    $('#hotbuystable tbody tr').each(function(i,val){
        j++;
        var formData = new FormData();
        formData.append('priority_update','');
        formData.append('id',$(this).attr('id'));
        formData.append('priority',j);
        $.ajax({
            url: "api.php",
            data:formData,
            type:'POST',
            mimeType:"multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            async:false,
            success:function(data){
                window.location.reload();
            }

        })
    })
}
$('#add_phone').on('click', function(){
    var phone = $('#make').val();
    var lastId = $('table tr:last-child td:first-child').html();
    lastId++;
    var formData1 = new FormData();
    formData1.append('add', '');
    formData1.append('phone', phone);
    formData1.append('priority', lastId);
    $.ajax({
        dataType: 'json',
        url: 'api.php',
        type: 'post',
        data: formData1,
        cache: false,
        processData: false,
        contentType: false,
        success: function (data){
            var json_x = data;
            var id = json_x[0]['phone_id'];
            var brand = json_x[0]['brand_name'];
            var product = json_x[0]['product_name'];
            var url = json_x[0]['image'];
            $('#hotbuystable').append("<tr><td>"+lastId+"</td><td><img src="+url+" width='50px' height='50px'></td><td>"+brand+"</td>" +
                "<td>"+product+"</td><td id='"+id+"'><a href='#' class='rowtag' id="+id+"><button type='button' class='btn-danger' value='Remove' name='Remove'>Remove</button></a></td></tr>");
            $.toast({
                heading: 'Success',
                text: 'Added Phone',
                position: 'bottom-right',
                icon: 'success'
            });
        }
    })
});
$('a.rowtag').on('click', function () {
    var contentid = $(this).attr('id');
    $(this).closest('tr').remove();
    var formData = new FormData();
    formData.append('remove', '');
    formData.append('phoneid', contentid);
    $.ajax({
        url: 'api.php',
        type: 'post',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data == 'success') {
                $.toast({
                    heading: 'Success',
                    text: 'Removed',
                    position: 'bottom-right',
                    icon: 'success'
                });
                //update_priority();
            }
            else {
                $.toast({
                    heading: 'ERROR',
                    text: 'Not Updated',
                    position: 'bottom-right',
                    icon: 'error'
                });
            }
        }
    })
});
