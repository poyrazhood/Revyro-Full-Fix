$(document).ready(function() {
    $("#serv-inp").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".pnd-mtable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#serv-cat").on("keyup", function(e) {
        var value = $(e.relatedTarget).data('data-filter-category-id');
        $(".pnd-mtable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    $('select').select2();
    
});


function dashMenuToggle() {
    $('.app-sidebar').toggleClass('sidebar-inact');
    $('.app-header').toggleClass('sidebar-inact');
    $('.app-content').toggleClass('sidebar-inact');
    $('body').toggleClass('body-pause');
}

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});

$('.services-list-filter').click(function(){

	var dataFilter=$(this).data("services-filter");
    console.log(dataFilter);
    var value = dataFilter;
    
    $(".pnd-mtable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });

});