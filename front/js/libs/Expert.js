var Expert = function(){
    this.$root = $("body");
    this.$group = this.$root.find("#sortable");
    var instance = this;
    this.$root.find(".js-search-form").each( function (kee, formBlock){
        instance._setSearchForm(formBlock);
    });
    this._setSortable(this.$group);
    this._setExpertBlocks();
    this._setSortTable(this.$group);
    this.btnSubmit();
    this.textareaCount(this.$group);

};
Expert.prototype._setSearchForm = function (block){
    var instance = this,
            $block = $(block),
            $btn = $block.find("button");

        $btn.click(function (event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: "/landing/find-expert/expert",
                data: { id: $block.find(".search_id")[0].value },
                dataType: "JSON",
                statusCode: {
                    200: function (feeback) {
                        $(block).find(".search_id")[0].value = "";

                        if (feeback.status === "fail") {
                            Notifier.showTimedMessage(feeback.msg, "warning", 2);
                            return;
                        }

                        Notifier.showTimedMessage("Add successful", "information", 2);
                        instance.$group.append(feeback.newBlock);
                        instance._setExpertBlocks();
                        instance._setSortTable(instance.$group);
                        instance.textareaCount(instance.$group);
                    },
                    412: function () {
                        location.href = "/";
                    }
                }
            });
        });
};
Expert.prototype._setExpertBlocks = function (){
    this.$root.find(".js-block").each(function (kee, block) {
        var $block = $(block);
        var removeBtn = $block.find(".js-remove").first();
        removeBtn.unbind("click").click(function () {
            $block.fadeOut("fast", function () {
                $block.remove();
            });
        });
    });
};
Expert.prototype._setSortable = function ($Sortable){
    $Sortable.sortable({
        stop: function () {
        // enable text select on inputs
        $(this).find("textarea")
            .bind("mousedown.ui-disableSelection selectstart.ui-disableSelection", function(e) {
                e.stopImmediatePropagation();
            });
        },
        revert: true
    }).disableSelection();
    $( "ul, li" ).disableSelection();
};
Expert.prototype._setSortTable = function ($Sortable){
    $Sortable.find("textarea")
        .bind("mousedown.ui-disableSelection selectstart.ui-disableSelection", function(e) {
            e.stopImmediatePropagation();
    });
};
Expert.prototype.textareaCount = function ($Sortable){
    $Sortable.find("textarea[maxlength]").keyup(function(){
        var $this = $(this);
        var limit = parseInt($this.attr("maxlength"));
        var text = $this.val();
        var chars = text.length;
        var userId = $this.attr("rel");
        var tag = "count_"+userId.toString();
        $("#"+tag).text(chars+"/"+limit);
    });  
};
Expert.prototype.btnSubmit = function(){
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
            url: "/landing/update-expert",
            data: { 
                user: user,
                description: description
            },
            dataType: "JSON",
            statusCode: {
                200: function (feeback) {
                    if (feeback.status === "fail") {
                        Notifier.showTimedMessage(feeback.msg, "warning", 2);
                        return;
                    }
                    Notifier.showTimedMessage("Update successful", "information", 2);
                },
                412: function () {
                    location.href = "/";
                }
            }
        });

    });
};
module.exports = Expert;
