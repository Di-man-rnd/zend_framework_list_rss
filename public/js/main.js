$(document).ready(function(){   

    grid = $('.grid').isotope({
        itemSelector : '.grid-item',
        sortAscending: false ,
        sortBy : 'number', 
        getSortData: {
            name: '.name', 
            number: '.number parseInt'   
        }
    });
    
    $(".sort_source").click(function(){
        grid.isotope({
            sortBy: 'name',
            sortAscending: true
        });
    });
    $(".sort_date").click(function(){
        grid.isotope({
            sortBy: 'number',
            sortAscending: false
        });
    });

    // return if like == 10 
    $("form").submit(function(){       
        if($(this).find('[name="count"]').val() == '10' )
            return false;
    });
    
    // css style button after turn
    $('#menu ').click(function(){
        $('.alert-dismissible.alert-info a#menu').removeClass('selected');
        $(this).addClass('selected');
    });
    
    
    
    

 
});