$(function() {
    $('form.toggleCheckbox > input:checkbox').change(function(e) {
        e.preventDefault();
        $.get(
                $(this).parent().attr("action"),
                {
                    checked: $(this).is(":checked")
                },
        function(data) {
            
        }
        );
    });
});
