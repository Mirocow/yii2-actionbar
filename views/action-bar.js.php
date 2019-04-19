function gridBulkActions(self, grid) {
    var ids = $(grid).yiiGridView('getSelectedRows'),
        options = self.options[self.selectedIndex],
        dataConfirm = options.getAttribute('data-confirm'),
        dataModal = options.getAttribute('data-modal'),
        dataContent = options.getAttribute('modal-content'),
        url = options.getAttribute('url');

    if (!ids.length) {
        alert('<?= $alert?>');
        self.value = '';
    } else if (dataConfirm && !confirm(dataConfirm)) {
        self.value = '';
        return;
    }

    if (dataModal) {
        var modal = $("#" + dataModal),
            form = modal.find("form." + dataContent);
        if(url){
            form.attr('action', url);
        }
        form.find("input[type='hidden']").remove();
        form.append('<input type="hidden" name="<?= $csrfParam?>" value="<?= $csrfToken?>" />');
        $.each(ids, function(index, id) {
            form.append('<input type="hidden" name="' + (options.getAttribute('name') ? options.getAttribute('name') : 'ids') + '[]" value=' + id + ' />');
        });
        modal.modal("show");

    } else if (dataContent) {
        var content = $("." + dataContent),
            form = content.find("form");
        if(url){
            form.attr('action', url);
        }
        form.find("input[type='hidden']").remove();
        form.append('<input type="hidden" name="<?= $csrfParam?>" value="<?= $csrfToken?>" />');
        $.each(ids, function(index, id) {
            form.append('<input type="hidden" name="' + (options.getAttribute('name') ? options.getAttribute('name') : 'ids') + '[]" value=' + id + ' />');
        });
        content.removeClass('hide');
        content.find(".cancel").on( "click", function() {
            self.value = '';
            content.addClass('hide');
        });
        content.on("click.cancel");

    } else if (url) {
        var form = $('<form action=' + url + ' method="POST"></form>');

        form.append('<input type="hidden" name="<?= $csrfParam?>" value="<?= $csrfToken?>" />');
        $.each(ids, function(index, id) {
            form.append('<input type="hidden" name="' + (options.getAttribute('name') ? options.getAttribute('name') : 'ids') + '[]" value=' + id + ' />');
        });
        form.appendTo('body').submit();
    }
} 