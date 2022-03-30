require(['core/first', 'jquery', 'core/ajax'], function(core, $, ajax) {

    $(document).ready(function() {
        
        
        $('#search').click(function(){
            searchUsers();
        });

        function searchUsers(){
            console.log('search users');
            window.open(`/local/staffmanager/index.php?month=${$('#month').val()}&year=${$('#year').val()}`, '_self');
        }

    });
    
    if(params.year){
        $('#year').val(params.year);
    }

    if(params.month) {
        $('#month').val(params.month);
    }

});