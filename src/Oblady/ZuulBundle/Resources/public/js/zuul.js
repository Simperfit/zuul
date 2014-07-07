function filterInputCallback($input) {

    //split the current value of searchInput
    var data = $input.val().split(" ");
    //create a jquery object of the rows
    var $rows = $($input.data('target'));
    if ($input.val() === "") {
        $rows.show();
        return;
    }
    //hide all the rows
    $rows.hide();

    //Recusively filter the jquery object to get results.
    $rows.filter(function () {
        var $t = $(this);
        for (var d = 0; d < data.length; ++d) {
            if ($t.is(":containsi('" + data[d] + "')")) {
                return true;
            }
        }
        return false;
    })
    //show the rows that match.
    .show();
}
$.extend($.expr[':'], {
  'containsi': function(elem, i, match, array)
  {
    return (elem.textContent || elem.innerText || '').toLowerCase()
    .indexOf((match[3] || "").toLowerCase()) >= 0;
  }
});
$(function() {
    
    //
    var $left = $("#associeted"),
    $right = $("#unassocieted");

    // let the left items be draggable
    $("li", $left ).draggable({
        revert: "invalid", // when not dropped, the item will revert back to its initial position
     //   containment: "#unassocieted", // stick to is container
        helper: "clone",
        cursor: "move"
    });
    // let the right items be draggable
    $("li", $right ).draggable({
        revert: "invalid", // when not dropped, the item will revert back to its initial position
      //  containment: "#associeted", // stick to is container
        helper: "clone",
        cursor: "move"
    });

    // let the right be droppable, accepting the left items
    $right.droppable({
        accept: "#associeted li",
        //activeClass: ""
        drop: function( event, ui ) {
            var object_id = ui.draggable.data('id');
            var url = $right.data('url');
            $('#status').html('').removeClass('success').removeClass('error');
            $.getJSON(url, {
                fk: object_id
            } ,
            function(data, textStatus, jqXHR) {
                if(0 == data.error) {
                    $('#status').html(data.msg).addClass('success');
                    ui.draggable.appendTo($right);
                } else {
                    $('#status').html(data.msg).addClass('error');
                }
            });
        }
    });

    // let the left be droppable as well, accepting items from the right
    $left.droppable({
        accept: "#unassocieted li",
        //activeClass: ""
        drop: function( event, ui ) {
            var object_id = ui.draggable.data('id');
            var url = $left.data('url');
            $('#status').html('').removeClass('success').removeClass('error');
            $.getJSON(url, {
                fk: object_id
            } ,
            function(data, textStatus, jqXHR) {
                
                if(0 == data.error) {
                    $('#status').html(data.msg).addClass('success');
                    ui.draggable.appendTo($left);
                } else {
                    $('#status').html(data.msg).addClass('error');
                }
            }
            );

        }
    });
    
    $('.tree-toggle').click(function (evt) {
        evt.preventDefault();
	$(this).parent().children('ul.tree').toggle(200);
    });
});
