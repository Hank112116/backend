var Expert = function(){
    this.$root = $("body");
    this.$group = this.$root.find("#sortable");
    var instance = this;
    this.$root.find(".js-search-form").each( function (kee, form_block){
        instance._setSearchForm(form_block)
    });
    this._setSortable(this.$group);
    this._setExpertBlocks();
    this._setSortTable(this.$group);
    this.btnSubmit();
    this.textareaCount(this.$group);

}
Expert.prototype._setSearchForm = function (block){
    var instance = this,
            $block = $(block),
            $btn = $block.find("button");

        $btn.click(function (event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: block.action,
                data: { id: $block.find(".search_id")[0].value },
                dataType: "JSON",
                success: function success(feeback) {
                    $(block).find(".search_id")[0].value = "";

                    if (feeback.status == "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }

                    Notifier.showTimedMessage("Add successful", "information", 2);
                    instance.$group.append(feeback.new_block);
                    instance._setExpertBlocks();
                    instance._setSortTable(instance.$group);
                    instance.textareaCount(instance.$group);
                }
            });
        });
}
Expert.prototype._setExpertBlocks = function (block){
    var instance = this;
    this.$root.find(".js-block").each(function (kee, block) {
        var $block = $(block);
        var remove_btn = $block.find(".js-remove").first();
        var remove_btn = $block.find(".js-remove")
        remove_btn.unbind("click").click(function () {
            $block.fadeOut("fast", function () {
                $block.remove();
            });
        });
    });
}
Expert.prototype._setSortable = function ($Sortable){
    $Sortable.sortable({
        stop: function () {
        // enable text select on inputs
        $(this).find("textarea")
            .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                e.stopImmediatePropagation();
            });
        },
        revert: true
    }).disableSelection();
    $( "ul, li" ).disableSelection();
}
Expert.prototype._setSortTable = function ($Sortable){
    $Sortable.find("textarea")
        .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
            e.stopImmediatePropagation();
    });
}
Expert.prototype.textareaCount = function ($Sortable){
    $Sortable.find('textarea[maxlength]').keyup(function(){
        var $this = $(this);
        var limit = parseInt($this.attr('maxlength'));
        var text = $this.val();
        var chars = text.length;
        var userId = $this.attr("rel");
        var tag = "count_"+userId.toString();
        $("#"+tag).text(chars+"/"+limit);
    });  
}
Expert.prototype.btnSubmit = function(){
    var instance = this;
    this.$root.find(".btn-submit").click(function(){
        var user = [];
        var description = [];
        $(".panel-body").each(function(index){
            var $this = $(this);
            user[index] = $this.attr("rel");
            description[index] = $this.find("textarea").val();
        });
        $.ajax({
            type: "POST",
            url: "./update-expert",
            data: { 
                user: user,
                description: description
            },
            dataType: "JSON",
            success: function success(feeback) {
                if (feeback.status == "fail") {
                    Notifier.showTimedMessage(feeback.msg, "warning", 2);
                    return;
                }
                Notifier.showTimedMessage("Update successful", "information", 2);
            }
        });

    });
}
module.exports = Expert;
