var Expert = function(){
    $( "#sortablettt" ).sortable({
        revert: true
    });
    $( "ul, li" ).disableSelection();
    this.$root = $("body");
    this.$group = this.$root.find("#sortablettt").first();
    var instance = this;
    this.$root.find(".js-search-form").each( function (kee, form_block){
        instance._setSearchForm(form_block)
    });
    this._setExpertBlocks();
    this.btnSubmit();
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
Expert.prototype.btnSubmit = function(){
    var instance = this;
    this.$root.find(".btn-submit").click(function(){
        var sort=[];
        $(".panel-body").each(function(index){
            sort[index] = $(this).attr("rel");         
        });
        $.ajax({
            type: "POST",
            url: "./update-expert",
            data: { sort: sort },
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
