$(function() {

    $('a#addFotoLink').click(function(e) {
        e.preventDefault();
        $("ul#fotosList").append(addNewField(newfieldscount));
        newfieldscount = newfieldscount + 1;
    });
    
    $('.removenew').live("click", function(e) {
        e.preventDefault();
        $(this).parents("li").remove();
    })
    
    $('.removeold').live("click", function(e) {
        e.preventDefault();
        $(e.target).parents("li").find("input[id*=filename_delete]").attr("checked","CHECKED");
        $(e.target).parents("li").hide(750,function(){
            $(this).remove();
        });
        
        var url = $(e.target).parents("li").attr("data-delete-url");
        
        $.get(url);
        
    })
});

newfieldscount = 0;

function addNewField(num) {
    return $.ajax({
        type: 'GET',
        url: '/addNewFoto?num=' + num,
        async: false
    }).responseText;
}
